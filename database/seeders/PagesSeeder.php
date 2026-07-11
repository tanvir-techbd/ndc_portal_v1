<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * See LARAVEL-DYNAMIZATION-PLAN.md Phase 6.2. 'home' seeded here (Phase 3/7
 * pull-forward); about/contact/policies/forms seeded in Phase 6.
 */
class PagesSeeder extends Seeder
{
    public function run(): void
    {
        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'content_blocks' => [
                    'hero_eyebrow' => '◆ Tier-III Certified · Established 2010',
                    'hero_title_main' => "Bangladesh's",
                    'hero_title_accent' => 'National',
                    'hero_title_end' => 'Data Center',
                    'hero_description' => 'The apex government IT infrastructure facility operated by Bangladesh Computer Council under the ICT Division — delivering cloud, hosting, colocation, and managed services to government and non-government organizations across the country.',
                    'hero_badges' => ['ISO 27001', 'ISO 20000', 'ITILv2', 'IaaS', 'PaaS', 'SaaS'],
                    'stat_bar' => [
                        ['num' => '99.9%', 'label' => 'Uptime SLA'],
                        ['num' => 'TIER-III', 'label' => 'Certified Standard'],
                        ['num' => '24×7', 'label' => 'Service Availability'],
                        ['num' => '500+', 'label' => 'Gov. Organizations Served'],
                    ],
                    'about_preview_title' => "Bangladesh's Premier Government Data Center",
                    'about_preview_paragraphs' => [
                        'The National Data Center (NDC) is operated by Bangladesh Computer Council (BCC) — the apex statutory body of the Government of Bangladesh under the Information & Communication Technology Division, Ministry of Posts, Telecommunications and Information Technology.',
                        'Established in 2010 as a Tier-III certified facility, NDC provides 24×7 IT infrastructure services to government and non-government organizations. The center has progressively expanded its capacity to meet growing national demand for digital services.',
                        'NDC holds three internationally recognized certifications that assure secure, reliable, and efficient IT service delivery at the highest industry standards.',
                    ],
                ],
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'About NDC',
                'content_blocks' => [
                    'history_lead' => "From the founding of the National Computer Council in 1983 to becoming Bangladesh's first Tier-III certified government data center — NDC's journey reflects the nation's ambition for digital transformation.",
                    'timeline' => [
                        ['year' => '1983', 'title' => 'National Computer Council Established', 'body' => 'The Government of Bangladesh formed the National Computer Council (NCC) to lead and coordinate ICT development across the country — marking the formal beginning of Bangladesh\'s journey in information technology.'],
                        ['year' => '1990', 'title' => 'Bangladesh Computer Council (BCC) Ordinance', 'body' => 'The National Computer Council was renamed and restructured as the Bangladesh Computer Council (BCC) through a formal government ordinance.'],
                        ['year' => '2008', 'title' => 'Digital Bangladesh Vision Announced', 'body' => 'The Government of Bangladesh announced its transformative "Digital Bangladesh" vision, paving the way for massive investment in digital infrastructure including a national data center.'],
                        ['year' => '2010', 'title' => 'National Data Center Established', 'body' => 'BCC established the National Data Center (NDC) at ICT Tower, Agargaon, Dhaka — Bangladesh\'s first government-owned data center.'],
                        ['year' => '2013', 'title' => 'ISO/IEC 27001 Certification', 'body' => 'NDC achieved ISO/IEC 27001 certification for information security management.'],
                        ['year' => '2015', 'title' => 'Uptime Institute Tier-III Certification', 'body' => 'NDC achieved Tier-III certification, meeting the Uptime Institute\'s globally recognized standards for concurrent maintainability and fault tolerance.'],
                        ['year' => '2019', 'title' => 'Government Cloud Service Launched', 'body' => 'NDC launched eGovCloud, an on-premises government cloud platform offering IaaS, PaaS, and SaaS to public organizations.'],
                        ['year' => '2022', 'title' => 'Disaster Recovery Facility Commissioned', 'body' => 'NDC commissioned a dedicated Disaster Recovery (DR) facility, ensuring business continuity and data resilience for government services.'],
                        ['year' => 'NOW', 'title' => 'Leading Government ICT Hub — Present Day', 'body' => "Today NDC serves government organizations across Bangladesh's public sector, continuously expanding its cloud, hosting, and colocation infrastructure."],
                    ],
                    'mission' => 'As the National Data Center, we are committed to delivering state-of-the-art IT services and infrastructure that create better opportunities for Bangladesh through technological advancement and digitalization.',
                    'vision' => 'NDC aspires to be recognized as the leading data center in Asia — setting global standards for IT service delivery, information security, and digital governance while empowering connected communities across Bangladesh.',
                    'core_values' => [
                        ['icon' => '🔒', 'title' => 'Security First', 'body' => 'Multi-layered defense mechanisms and ISO 27001 certified information security management protect every piece of government data entrusted to us.'],
                        ['icon' => '⚡', 'title' => 'Reliability', 'body' => 'Tier-III certified infrastructure, designed to the 99.982% uptime standard, with concurrent maintainability and continuous service delivery.'],
                        ['icon' => '🌱', 'title' => 'Innovation', 'body' => "Continuously adopting emerging technologies to keep Bangladesh's government infrastructure at the cutting edge."],
                    ],
                    'certifications' => [
                        ['name' => 'ISO/IEC 27001', 'desc' => 'Information Security Management System certification.'],
                        ['name' => 'ISO/IEC 20000', 'desc' => 'IT Service Management System certification.'],
                        ['name' => 'Tier-III (Uptime Institute)', 'desc' => 'Concurrently maintainable data center design certification.'],
                    ],
                ],
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'contact'],
            [
                'title' => 'Contact NDC',
                'content_blocks' => [
                    'departments' => [
                        ['name' => 'Cloud Services', 'email' => 'cloud@bcc.gov.bd', 'desc' => 'IaaS, PaaS, SaaS inquiries'],
                        ['name' => 'Network Operations', 'email' => 'network@bcc.gov.bd', 'desc' => 'Connectivity & NIX issues'],
                        ['name' => 'Security Operations', 'email' => 'security@bcc.gov.bd', 'desc' => 'SOC, VAPT, incident reporting'],
                        ['name' => 'Service Orders', 'email' => 'orders@bcc.gov.bd', 'desc' => 'New service applications'],
                        ['name' => 'Colocation', 'email' => 'colo@bcc.gov.bd', 'desc' => 'Rack space allocation'],
                        ['name' => 'Email Services', 'email' => 'email@bcc.gov.bd', 'desc' => 'Zimbra support & onboarding'],
                    ],
                    'faqs' => [
                        ['q' => 'How do I request a new cloud or hosting service?', 'a' => 'Follow the relevant procedure under Service Order Procedure — Cloud, Request Based, or SSL/VPN. Cloud and Request-Based orders are placed on the NDC Cloud Management Portal (CMP); SSL/VPN certificates are issued through the BCC Certifying Authority.'],
                        ['q' => 'Which organizations are eligible for NDC services?', 'a' => 'NDC primarily serves government ministries, divisions, directorates, and other public sector organizations. Non-government organizations may be eligible for specific services subject to approval.'],
                        ['q' => 'How is NDC pricing structured?', 'a' => 'Cloud-based services are billed monthly per resource. Request Based Services follow fixed monthly or one-time packages. All published prices exclude VAT/Tax.'],
                        ['q' => 'What uptime and reliability standard does NDC operate to?', 'a' => "NDC is a Tier-III certified facility, designed to the Uptime Institute's 99.982% availability standard."],
                        ['q' => 'How do I get technical support after my service is provisioned?', 'a' => 'Raise a ticket through the Customer Support Portal, or call the main helpdesk for urgent issues.'],
                    ],
                ],
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'policies'],
            [
                'title' => 'Policies & Guidelines',
                'content_blocks' => [
                    'hero_lead' => 'Governing policies, guidelines and standard operating procedures for NDC services — covering acceptable use, email, cloud computing, and data center operations.',
                    'sections' => [
                        [
                            'id' => 'user-policy', 'tab' => 'User Policy', 'eyebrow' => 'Governance', 'title' => 'User Policy of NDC',
                            'body' => [
                                'Defines the acceptable use of NDC infrastructure and services by government and non-government organizations, including account responsibilities, data handling obligations, and prohibited activities.',
                                'Applies to all organizations with an active service order, from cloud resources to colocation racks and hosted email.',
                            ],
                            'requestable' => true,
                        ],
                        [
                            'id' => 'email-policy-2018', 'tab' => 'Email Policy 2018', 'eyebrow' => 'Governance', 'title' => 'Public Email Policy 2018',
                            'body' => ['Establishes the framework for official government email issued on .gov.bd domains — covering account provisioning, retention, acceptable use, and security requirements for public offices.'],
                            'requestable' => true,
                        ],
                        [
                            'id' => 'cloud-computing-policy', 'tab' => 'Cloud Computing Policy', 'eyebrow' => 'Governance', 'title' => 'NDC Cloud Computing Policy 2023 (Draft)',
                            'body' => [
                                'Sets out the governance model for government cloud adoption on the eGovCloud platform, including data classification, residency requirements, and shared-responsibility expectations between NDC and client organizations.',
                                'This policy is currently in draft — contact NDC for the latest version and consultation status.',
                            ],
                            'requestable' => true,
                        ],
                        [
                            'id' => 'sop', 'tab' => 'SOP', 'eyebrow' => 'Operations', 'title' => 'Standard Operating Procedure (SOP)',
                            'body' => ['Documents the operational procedures followed by NDC staff for service provisioning, incident response, change management, and maintenance windows across the data center.'],
                            'requestable' => true,
                        ],
                        [
                            'id' => 'dc-guideline', 'tab' => 'DC Guideline', 'eyebrow' => 'Operations', 'title' => 'Data Center Guideline',
                            'body' => ['Technical and physical guidelines for organizations using colocation, rack space, and on-premises equipment at NDC — covering power, cooling, cabling, and access control requirements.'],
                            'requestable' => true,
                        ],
                        [
                            'id' => 'email-guideline-2019', 'tab' => 'Email Guideline 2019', 'eyebrow' => 'Operations', 'title' => 'Public Email Guideline 2019',
                            'body' => ['Implementation guidance supporting the Public Email Policy 2018 — mailbox provisioning steps, quota management, and migration guidance for government organizations onboarding to NDC email.'],
                            'requestable' => true,
                        ],
                        [
                            'id' => 'privacy', 'tab' => 'Privacy Policy', 'eyebrow' => 'Legal', 'title' => 'Privacy Policy',
                            'body' => [
                                'NDC collects only the information required to provision and support requested services (organization details, contact information, and technical configuration data). Data submitted through service order and contact forms is used solely for processing your request and is not shared with third parties outside the scope of government service delivery.',
                                "For questions about how your organization's data is handled, contact the NDC helpdesk.",
                            ],
                        ],
                        [
                            'id' => 'terms', 'tab' => 'Terms of Use', 'eyebrow' => 'Legal', 'title' => 'Terms of Use',
                            'body' => [
                                "Access to ndc.bcc.gov.bd and NDC's online service ordering portal is provided for legitimate government and authorized non-government use. Users are responsible for the accuracy of information submitted through registration, service order, and contact forms.",
                                'Continued use of NDC services is subject to compliance with the User Policy of NDC and applicable government IT policies.',
                            ],
                        ],
                        [
                            'id' => 'rti', 'tab' => 'RTI', 'eyebrow' => 'Legal', 'title' => 'Right to Information (RTI)',
                            'body' => [
                                "As a unit of Bangladesh Computer Council under the ICT Division, NDC supports citizens' right to information under the Right to Information Act 2009. Information requests regarding NDC's public functions can be submitted through the designated officer at BCC.",
                                'Submit RTI requests via the Contact page or in writing to the BCC head office.',
                            ],
                        ],
                        [
                            'id' => 'accessibility', 'tab' => 'Accessibility', 'eyebrow' => 'Accessibility', 'title' => 'Accessibility Statement',
                            'body' => [
                                'NDC is committed to making ndc.bcc.gov.bd usable by the widest possible audience, including people using assistive technologies. The site uses semantic HTML, keyboard-navigable menus, and scalable text sizing.',
                                'If you encounter an accessibility barrier on this site, please report it via the Contact page so we can address it.',
                            ],
                        ],
                    ],
                ],
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'forms'],
            [
                'title' => 'Forms & Agreements',
                'content_blocks' => [
                    'intro' => 'Downloadable forms, frame agreements, and reference documents for NDC services.',
                ],
            ]
        );

        $this->command->info('Seeded home, about, contact, policies, forms pages.');
    }
}
