<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\PricingService;
use Illuminate\View\View;

class PricingController extends Controller
{
    public function cloud(PricingService $pricing): View
    {
        return view('public.pricing', [
            'pageTitle' => 'Cloud Based Pricing',
            'ctaLabel' => 'Order via CMP Portal',
            'ctaUrl' => setting('cmp_portal_url', 'https://cmp.bcc.gov.bd'),
            'referenceDoc' => ['label' => 'NDC Cloud Service Fee 2023 (PDF)', 'file' => 'NDC-Cloud-Service-Fee-2023.pdf'],
            'tiersByType' => $pricing->getForPage(['cloud']),
        ]);
    }

    public function request(PricingService $pricing): View
    {
        return view('public.pricing', [
            'pageTitle' => 'Request Based Pricing',
            'ctaLabel' => 'Start Request on CMP',
            'ctaUrl' => setting('cmp_portal_url', 'https://cmp.bcc.gov.bd'),
            'referenceDoc' => ['label' => 'NDC Datacenter Service Prices 2023 (PDF)', 'file' => 'NDC-Datacenter-Service-Prices-2023.pdf'],
            'tiersByType' => $pricing->getForPage(['rbs']),
        ]);
    }
}
