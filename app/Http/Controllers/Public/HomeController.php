<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\ContentService;
use App\Services\NoticeService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(ContentService $content, NoticeService $notices): View
    {
        return view('public.home', [
            'blocks' => $content->getBlocks('home'),
            'featuredServices' => $content->getFeaturedServices(),
            'latestNotices' => $notices->latestNews(4),
            'officialNotices' => $notices->latestOfficialNotices(7),
        ]);
    }
}
