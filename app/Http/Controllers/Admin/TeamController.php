<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use App\Models\TeamMember;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        return view('admin.team.index', [
            'members' => TeamMember::orderBy('group')->orderBy('display_order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.team.form', ['member' => new TeamMember(), 'photoOptions' => $this->photoOptions()]);
    }

    public function edit(TeamMember $team): View
    {
        return view('admin.team.form', ['member' => $team, 'photoOptions' => $this->photoOptions()]);
    }

    private function photoOptions()
    {
        return MediaAsset::where('category', 'team_photo')->orderByDesc('created_at')->get();
    }

    public function store(Request $request, AuditService $audit): RedirectResponse
    {
        $data = $this->validated($request);
        $member = TeamMember::create($data);
        $audit->record($request->user(), 'team.create', $member);

        return redirect()->route('admin.team.index')->with('status', 'Team member added.');
    }

    public function update(Request $request, TeamMember $team, AuditService $audit): RedirectResponse
    {
        $data = $this->validated($request);
        $team->update($data);
        $audit->record($request->user(), 'team.update', $team);

        return redirect()->route('admin.team.index')->with('status', 'Team member updated.');
    }

    public function destroy(Request $request, TeamMember $team, AuditService $audit): RedirectResponse
    {
        $audit->record($request->user(), 'team.delete', $team, ['name' => $team->name]);
        $team->delete();

        return back()->with('status', 'Team member removed.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'designation' => ['required', 'string', 'max:150'],
            'group' => ['required', 'in:leadership,technical_staff'],
            'display_order' => ['nullable', 'integer'],
            'photo_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
        ]);
    }
}
