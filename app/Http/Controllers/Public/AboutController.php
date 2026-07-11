<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Services\ContentService;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(ContentService $content): View
    {
        return view('public.about', [
            'blocks' => $content->getBlocks('about'),
            'leadership' => TeamMember::where('group', 'leadership')->orderBy('display_order')->get(),
            'technicalStaff' => TeamMember::where('group', 'technical_staff')->orderBy('display_order')->get(),
        ]);
    }
}
