<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Inbox for the Contact page form and the Account Access page's message
 * box — both write to ContactInquiry (see Public\ContactInquiryController).
 */
class MessageController extends Controller
{
    private const SORTABLE = ['created_at', 'full_name', 'status'];

    public function index(Request $request): View
    {
        $sort = in_array($request->query('sort'), self::SORTABLE, true) ? $request->query('sort') : 'created_at';
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';
        $status = $request->query('status');

        $query = ContactInquiry::query()->orderBy($sort, $direction);

        if (in_array($status, ['new', 'in_progress', 'resolved'], true)) {
            $query->where('status', $status);
        }

        return view('admin.messages.index', [
            'inquiries' => $query->paginate(20)->withQueryString(),
            'sort' => $sort,
            'direction' => $direction,
            'status' => $status,
            'newCount' => ContactInquiry::where('status', 'new')->count(),
            'sortLinks' => $this->sortLinks($request, $sort, $direction),
        ]);
    }

    /**
     * @return array<string, array{url: string, label: string}>
     */
    private function sortLinks(Request $request, string $sort, string $direction): array
    {
        $links = [];

        foreach (['full_name' => 'From', 'status' => 'Status', 'created_at' => 'Received'] as $column => $label) {
            $nextDirection = ($sort === $column && $direction === 'asc') ? 'desc' : 'asc';
            $arrow = $sort === $column ? ($direction === 'asc' ? ' &#8593;' : ' &#8595;') : '';

            $links[$column] = [
                'url' => route('admin.messages.index', array_merge($request->query(), ['sort' => $column, 'direction' => $nextDirection])),
                'label' => $label . $arrow,
            ];
        }

        return $links;
    }

    public function show(ContactInquiry $inquiry): View
    {
        if ($inquiry->status === 'new') {
            $inquiry->update(['status' => 'in_progress']);
        }

        return view('admin.messages.show', ['inquiry' => $inquiry]);
    }

    public function updateStatus(Request $request, ContactInquiry $inquiry, AuditService $audit): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,in_progress,resolved'],
        ]);

        $inquiry->update($data);
        $audit->record($request->user(), 'message.status_update', $inquiry, ['status' => $data['status']]);

        return back()->with('status', 'Message status updated.');
    }

    public function destroy(Request $request, ContactInquiry $inquiry, AuditService $audit): RedirectResponse
    {
        $audit->record($request->user(), 'message.delete', $inquiry, ['full_name' => $inquiry->full_name, 'email' => $inquiry->email]);
        $inquiry->delete();

        return redirect()->route('admin.messages.index')->with('status', 'Message deleted.');
    }
}
