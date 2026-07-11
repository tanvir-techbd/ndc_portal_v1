<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Pre-populates every field currently hardcoded in admin/settings.html
 * and used across the shared public layout. See LARAVEL-DYNAMIZATION-PLAN.md
 * Part 3.1.
 */
class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'site_name' => 'National Data Center (NDC)',
            'site_tagline' => 'Bangladesh Computer Council · Government of Bangladesh',
            'helpdesk_phone' => '+88-02-55006840',
            'helpdesk_email' => 'datacenter@bcc.gov.bd',
            'office_address' => 'ICT Tower, E-14/X, Agargaon, Sher-e-Bangla Nagar, Dhaka-1207, Bangladesh',
            'service_hours' => '24 × 7 × 365',
            'copyright_text' => 'Copyright © 2026 NDC, Bangladesh Computer Council — All Rights Reserved.',
            'planning_implementation_credit' => 'Planning & Implementation: NDC, BCC',
            'homepage_meta_title' => 'National Data Center (NDC) – Bangladesh Computer Council',
            'homepage_meta_description' => "Bangladesh's first Tier-III certified government data center operated by BCC under the ICT Division.",
            'ticker_message' => 'NDC now offers expanded IaaS capacity for government organizations | New Cloud Service Order portal launched — online applications now open',
            'feature_ticker_enabled' => '1',
            'feature_contact_form_enabled' => '1',
            'feature_maintenance_mode' => '0',
            'feature_search_bar_enabled' => '1',
            'cmp_portal_url' => 'https://cmp.bcc.gov.bd',
            'bcc_ca_portal_url' => 'https://www.bcc-ca.gov.bd',
            'support_portal_url' => 'https://support.bcc.gov.bd',
            'logo_ndc_media_id' => '',
            'logo_bcc_media_id' => '',
            'logo_ict_media_id' => '',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        $this->command->info('Seeded ' . count($defaults) . ' settings.');
    }
}
