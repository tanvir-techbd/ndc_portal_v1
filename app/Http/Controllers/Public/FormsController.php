<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\ContentService;
use Illuminate\View\View;

class FormsController extends Controller
{
    public function index(ContentService $content): View
    {
        return view('public.forms', [
            'blocks' => $content->getBlocks('forms'),
        ]);
    }
}
