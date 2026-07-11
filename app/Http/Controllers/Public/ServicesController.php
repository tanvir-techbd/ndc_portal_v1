<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\View\View;

class ServicesController extends Controller
{
    public function index(): View
    {
        return view('public.services', [
            'groups' => Service::groups()->visible()->orderBy('display_order')->get(),
            'detailsByGroup' => Service::details()->visible()->orderBy('display_order')->get()->groupBy('group_slug'),
        ]);
    }
}
