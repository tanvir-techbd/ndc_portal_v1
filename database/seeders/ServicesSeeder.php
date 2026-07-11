<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * Two kinds of rows share this table:
 * - kind=group: the 7 section summaries shown on the homepage teaser grid
 *   and as section headers on the full /services catalog page.
 * - kind=detail: the individual catalog cards under each group (e.g.
 *   "Elastic Cloud Server (ECS)" belongs to group_slug=cloud) — extracted
 *   verbatim from the previously-hardcoded public/services.blade.php so no
 *   real content is lost in the move to being admin-editable.
 */
class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            [
                'name' => 'Cloud Services (IaaS / PaaS / SaaS)',
                'slug' => 'cloud',
                'description' => 'Government Cloud infrastructure providing virtual machines, platform environments, and software services with auto-scaling and elastic load balancing capabilities.',
                'icon' => 'cloud',
                'display_order' => 1,
            ],
            [
                'name' => 'Web & Application Hosting',
                'slug' => 'hosting',
                'description' => 'Reliable web hosting on Microsoft and Linux platforms with dedicated resources, scalable options, and full technical support for government portals and applications.',
                'icon' => 'hosting',
                'display_order' => 2,
            ],
            [
                'name' => 'Colocation Services',
                'slug' => 'colocation',
                'description' => 'Physical rack space within the National Data Center facility for hosting customer servers and network equipment, offered as rack unit or full rack allocations.',
                'icon' => 'colocation',
                'display_order' => 3,
            ],
            [
                'name' => 'Managed Services',
                'slug' => 'managed',
                'description' => 'End-to-end consultancy, design, deployment, and implementation of Reference Architecture services — available in Basic and Standard categories with dedicated support.',
                'icon' => 'managed',
                'display_order' => 4,
            ],
            [
                'name' => 'Government Email (Zimbra)',
                'slug' => 'email',
                'description' => 'Official Zimbra email service with domain-level administration, scalable mailbox and domain storage, delegated admin facilities, and complimentary e-Government Cloud Drive.',
                'icon' => 'email',
                'display_order' => 5,
            ],
            [
                'name' => 'VPS & Load Balancer',
                'slug' => 'vps',
                'description' => 'Virtual Private Server services in Basic, Standard, Advance, and Premium tiers, plus elastic load balancing with auto-scaling triggered by CPU and memory thresholds.',
                'icon' => 'vps',
                'display_order' => 6,
            ],
            [
                'name' => 'Backup & Disaster Recovery',
                'slug' => 'backup',
                'description' => 'Data-level, disk-level, and snapshot backup services on the eGovCloud platform, plus geo-redundant Cloud Disaster Recovery (CDRS) for mission-critical applications.',
                'icon' => 'backup',
                'display_order' => 7,
            ],
        ];

        foreach ($groups as $group) {
            Service::updateOrCreate(
                ['slug' => $group['slug']],
                $group + ['kind' => 'group', 'group_slug' => null, 'is_featured' => true, 'is_visible' => true]
            );
        }

        $details = [
            // ── Cloud ──
            [
                'name' => 'Elastic Cloud Server (ECS) — IaaS', 'slug' => 'cloud-ecs', 'group_slug' => 'cloud',
                'tag' => 'Infrastructure as a Service', 'icon' => 'cloud',
                'description' => 'NDC provides Elastic Cloud Server (ECS) services with well-balanced CPU and Memory combinations across three computing types: General Purpose ECS, Memory Intensive ECS, and Accelerated ECS. ECS instances support a wide range of open source and proprietary operating systems and are suitable for application platforms, database systems, AI, image processing, NLP, and machine learning workloads.',
                'tiers' => ['General Purpose ECS', 'Memory Intensive ECS', 'Accelerated ECS'],
                'features' => ['Virtual Private Cloud (VPC) network segmentation', 'Elastic IP (public & private) assignment', 'Auto Scaling triggered by CPU/Memory thresholds', 'Elastic Load Balancer (ELB) integration', 'Virtual Firewall (VFW) & Security Group (SG)', 'IPv6-ready network infrastructure', 'Image Management Service (IMS)', 'Direct Connect (DC) for dedicated links'],
                'display_order' => 1,
            ],
            [
                'name' => 'Cloud Storage Services', 'slug' => 'cloud-storage', 'group_slug' => 'cloud',
                'tag' => 'IaaS — Storage', 'icon' => 'storage',
                'description' => 'The eGovCloud platform offers three types of highly durable and scalable storage tailored for different application needs. Elastic Block Storage (EBS) supports both mixed and high-performance disk configurations for optimized I/O. Object Storage Service (OBS) is S3-compatible and suitable for unstructured data, IoT data, and archival. File System Storage (FSS) provides shared file access for multi-instance workloads.',
                'tiers' => ['Elastic Block Storage (EBS)', 'Object Storage (OBS)', 'File System Storage (FSS)'],
                'features' => [],
                'display_order' => 2,
            ],
            [
                'name' => 'Platform as a Service (PaaS)', 'slug' => 'cloud-paas', 'group_slug' => 'cloud',
                'tag' => 'Application Marketplace & Openshift', 'icon' => 'grid',
                'description' => "NDC's PaaS offerings enable organizations to deploy complex application infrastructure in minutes. The Application Marketplace provides one-click deployment of popular stacks including LAMP, WordPress, Nginx, Apache, Redis, RabbitMQ, MySQL, MariaDB, MongoDB, ElasticSearch, PostgreSQL, Cassandra, Hadoop, Nagios, Icinga, Jenkins, Kafka, and Graylog. NDC also provides Red Hat OpenShift for organizations requiring built-in CI/CD, DevOps capabilities, and micro-services architecture.",
                'tiers' => ['Application Marketplace', 'Red Hat OpenShift', 'Container Service (K8s)'],
                'features' => [],
                'display_order' => 3,
            ],
            [
                'name' => 'Software as a Service (SaaS)', 'slug' => 'cloud-saas', 'group_slug' => 'cloud',
                'tag' => 'Ready-to-Use Government Applications', 'icon' => 'check-circle',
                'description' => 'NDC provides subscription-based SaaS to reduce time, cost, and operational overhead for government organizations. Current SaaS offerings include NID Card OCR Service, Bangla OCR Service, Face Matching Service, Bangla Sentiment Analysis Service, and e-Recruitment Service. NDC continuously expands its SaaS catalog to meet common government application requirements.',
                'tiers' => ['NID OCR', 'Bangla OCR', 'Face Matching', 'Bangla Sentiment Analysis', 'e-Recruitment'],
                'features' => [],
                'display_order' => 4,
            ],
            // ── Hosting ──
            [
                'name' => 'Web Hosting & Application Hosting', 'slug' => 'hosting-web', 'group_slug' => 'hosting',
                'tag' => 'Microsoft & Linux Platform', 'icon' => 'server',
                'description' => 'NDC provides shared hosting services from a shared infrastructure where multiple organizations host websites or applications on the same server space. Both web hosting and application hosting are available on Linux and Windows (Microsoft) environments, suitable for organizational websites, web portals, and custom application systems.',
                'tiers' => ['Linux Platform', 'Windows / Microsoft Platform', 'Web Hosting', 'Application Hosting'],
                'features' => ['Scalable hosting plans with distinct offers', 'Suitable for government portals and e-services', "Managed within NDC's secure Tier-III facility", 'Technical support via NDC helpdesk'],
                'display_order' => 1,
            ],
            // ── Colocation ──
            [
                'name' => 'Rack Space & Rack Unit Allocation', 'slug' => 'colocation-rack', 'group_slug' => 'colocation',
                'tag' => 'Physical Rack Space in Tier-III Facility', 'icon' => 'server',
                'description' => "BCC provides physical space in the National Data Center for hosting customer racks and equipment. Colocation is provided on a limited-scale basis as NDC's primary recommendation is cloud services for cost reduction and maintenance efficiency. Organizations with existing on-premises hardware can benefit from NDC's world-class power, cooling, connectivity, and physical security.",
                'tiers' => ['Rack Unit Allocation', 'Rack Space Allocation'],
                'features' => ['State-of-the-art power and cooling infrastructure', 'Connected to 3 IP transit providers in Bangladesh', '5 local NIX connections for faster local routing', '24×7 physical security and access control', 'Significant rack capacity at primary site', 'Fire suppression and environmental monitoring'],
                'display_order' => 1,
            ],
            // ── Managed ──
            [
                'name' => 'Consultancy, Design, Deployment & Implementation', 'slug' => 'managed-consultancy', 'group_slug' => 'managed',
                'tag' => 'Reference Architecture Services', 'icon' => 'people',
                'description' => "NDC provides two categories of managed services — Basic and Standard — covering the full lifecycle of IT infrastructure: consultancy, architecture design, deployment, and implementation of Reference Architecture services. This end-to-end service model allows government organizations to leverage NDC's expertise rather than building in-house IT capacity.",
                'tiers' => ['Basic Category', 'Standard Category'],
                'features' => ['IT infrastructure consultancy and planning', 'Reference Architecture design', 'Deployment and go-live support', 'Managed Database Service (shared & dedicated)', 'Physical Server Service provision', 'Post-deployment monitoring and support'],
                'display_order' => 1,
            ],
            // ── Email ──
            [
                'name' => 'Zimbra Government Email Service', 'slug' => 'email-zimbra', 'group_slug' => 'email',
                'tag' => 'Official .gov.bd Email Infrastructure', 'icon' => 'envelope',
                'description' => 'NDC provides Zimbra Email Service under a single Standard package with domain-level administration support, scalable mailbox and domain storage options, and delegated admin facilities. Each email account includes a complimentary eGov Cloud Drive Service for official document storage. The service aligns with the Government Email Policy 2018 and is available to all government organizations at their official domain.',
                'tiers' => ['Standard Package', 'eGov Cloud Drive (Complimentary)*'],
                'features' => ['Organization-owned domain email (e.g. @ministry.gov.bd)', 'Domain-level administration panel', 'Scalable mailbox and domain storage', 'Delegated admin facilities for org admins', 'Complimentary eGov Cloud Drive per account', 'Compliant with Government Email Policy 2018'],
                'display_order' => 1,
            ],
            // ── VPS ──
            [
                'name' => 'Virtual Private Server (VPS)', 'slug' => 'vps-server', 'group_slug' => 'vps',
                'tag' => 'On-Demand Compute', 'icon' => 'wifi',
                'description' => 'NDC provides Virtual Private Server (VPS) services in pre-defined packages across four tiers: Basic, Standard, Advance, and Premium. VPS is an on-demand service — instances are provisioned when requests are placed. VPS is ideal for organizations with low and fixed workload requirements that do not need the full scale of cloud ECS services.',
                'tiers' => ['Basic', 'Standard', 'Advance', 'Premium'],
                'features' => [],
                'display_order' => 1,
            ],
            [
                'name' => 'Elastic Load Balancer Service', 'slug' => 'vps-loadbalancer', 'group_slug' => 'vps',
                'tag' => 'Traffic Distribution & Auto-Scaling', 'icon' => 'balance',
                'description' => 'NDC provides Load Balancer services in Basic, Standard, and Advance categories. The service distributes incoming traffic across multiple server instances to ensure high availability and performance. Auto Scaling is triggered automatically when configured CPU or Memory utilization thresholds are reached, expanding or contracting capacity on demand.',
                'tiers' => ['Basic', 'Standard', 'Advance'],
                'features' => ['Automatic threshold-triggered scaling', 'CPU and Memory utilization monitoring', 'Requires cloud ELB to be enabled', 'Suitable for high-traffic government portals'],
                'display_order' => 2,
            ],
            // ── Backup & DR ──
            [
                'name' => 'Cloud Backup & DR Services', 'slug' => 'backup-dr', 'group_slug' => 'backup',
                'tag' => 'BCP · DR · Data Protection', 'icon' => 'shield',
                'description' => 'The eGovCloud platform provides comprehensive backup services for data-level, disk-level, and snapshot backups. Backup policies including full/incremental frequency and retention can be configured via the self-service console. NDC also operates a Cloud Disaster Recovery (CDRS) service from a geographically separate DR facility, providing failover for mission-critical government applications.',
                'tiers' => ['Cloud Server Backup (CSBS)', 'Volume Backup (VBS)', 'File System Backup', 'Geo-Redundant DR Site*'],
                'features' => ['Customizable backup frequency and retention', 'Full and incremental backup modes', 'Geo-redundant Disaster Recovery facility', 'RPO/RTO configurable per application architecture'],
                'display_order' => 1,
            ],
        ];

        foreach ($details as $detail) {
            Service::updateOrCreate(
                ['slug' => $detail['slug']],
                $detail + ['kind' => 'detail', 'is_featured' => false, 'is_visible' => true]
            );
        }

        $this->command->info('Seeded ' . count($groups) . ' service groups and ' . count($details) . ' catalog details.');
    }
}
