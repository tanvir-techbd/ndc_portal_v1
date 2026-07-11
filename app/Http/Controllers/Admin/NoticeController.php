<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use App\Models\Notice;
use App\Services\NoticeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoticeController extends Controller
{
    public function __construct(private NoticeService $notices)
    {
    }

    private function attachmentOptions()
    {
        return MediaAsset::where('category', 'notice')->orderByDesc('created_at')->get();
    }

    public function index(Request $request): View
    {
        $query = Notice::query()->orderByDesc('created_at');

        if ($category = $request->query('category')) {
            $query->where('category', $category);
        }

        return view('admin.notices.index', [
            'notices' => $query->paginate(20),
            'categories' => ['maintenance', 'services', 'tender', 'policy', 'security', 'general'],
            'category' => $category,
        ]);
    }

    public function create(): View
    {
        return view('admin.notices.form', ['notice' => new Notice(), 'categories' => ['maintenance', 'services', 'tender', 'policy', 'security', 'general'], 'attachmentOptions' => $this->attachmentOptions()]);
    }

    public function edit(Notice $notice): View
    {
        return view('admin.notices.form', ['notice' => $notice, 'categories' => ['maintenance', 'services', 'tender', 'policy', 'security', 'general'], 'attachmentOptions' => $this->attachmentOptions()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $notice = $this->notices->createOrUpdate($data, $request->user());

        return redirect()->route('admin.notices.index')->with('status', "Notice \"{$notice->title}\" created.");
    }

    public function update(Request $request, Notice $notice): RedirectResponse
    {
        $data = $this->validated($request);
        $this->notices->createOrUpdate($data, $request->user(), $notice);

        return redirect()->route('admin.notices.index')->with('status', 'Notice updated.');
    }

    public function publish(Request $request, Notice $notice): RedirectResponse
    {
        $this->notices->publish($notice, $request->user());

        return back()->with('status', 'Notice published.');
    }

    public function destroy(Request $request, Notice $notice): RedirectResponse
    {
        $this->notices->bulkAction([$notice->id], 'delete', $request->user());

        return back()->with('status', 'Notice deleted.');
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'notice_ids' => ['required', 'array'],
            'notice_ids.*' => ['integer'],
            'action' => ['required', 'in:publish,draft,delete'],
        ]);

        $count = $this->notices->bulkAction($data['notice_ids'], $data['action'], $request->user());

        return back()->with('status', "{$count} notice(s) updated.");
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'body_html' => ['required', 'string'],
            'category' => ['required', 'in:maintenance,services,tender,policy,security,general'],
            'published_at' => ['required', 'date'],
            'visibility' => ['required', 'in:public,internal'],
            'status' => ['required', 'in:draft,review,published'],
            'attachment_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
        ]);
    }
}
