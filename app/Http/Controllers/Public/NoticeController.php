<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Services\NoticeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoticeController extends Controller
{
    public function index(Request $request, NoticeService $notices): View
    {
        $category = $request->query('category');

        return view('public.notices', [
            'notices' => $notices->getPublic(['category' => $category]),
            'category' => $category,
            'categories' => ['maintenance', 'services', 'tender', 'policy', 'security', 'general'],
            'totalCount' => Notice::public()->count(),
        ]);
    }

    public function download(Notice $notice): RedirectResponse
    {
        abort_unless($notice->attachment && $notice->status === 'published' && $notice->visibility === 'public', 404);

        return redirect($notice->attachment->resolvedUrl());
    }
}
