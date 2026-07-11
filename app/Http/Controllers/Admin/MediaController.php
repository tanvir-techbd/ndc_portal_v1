<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function __construct(private MediaService $media)
    {
    }

    public function index(): View
    {
        return view('admin.media.index', [
            'assets' => MediaAsset::orderByDesc('created_at')->paginate(24),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file'],
            'category' => ['required', 'in:notice,team_photo,logo,general'],
        ]);

        $this->media->store($request->file('file'), $request->input('category'), $request->user());

        return back()->with('status', 'File uploaded.');
    }

    public function destroy(Request $request, MediaAsset $asset): RedirectResponse
    {
        $this->media->delete($asset, $request->user());

        return back()->with('status', 'File deleted.');
    }
}
