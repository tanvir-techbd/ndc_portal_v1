<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingTier;
use App\Services\PricingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PricingController extends Controller
{
    public const PRICE_UNITS = ['/mo', '/yr', '/GB', '/GB/mo', '/core/mo', '/vCPU/mo', '/domain/mo', '/account/mo', ' one-time', ''];

    public function index(string $type, PricingService $pricing): View
    {
        abort_unless(in_array($type, ['cloud', 'request'], true), 404);

        return view('admin.pricing.index', [
            'title' => $type === 'cloud' ? 'Cloud Based Pricing' : 'Request Based Pricing',
            'type' => $type,
            'tiersByType' => $pricing->getForPage([$type === 'cloud' ? 'cloud' : 'rbs'], publicOnly: false),
        ]);
    }

    public function create(string $type, Request $request): View
    {
        abort_unless(in_array($type, ['cloud', 'request'], true), 404);

        $tier = new PricingTier([
            'service_type' => $request->query('service_type', $type === 'cloud' ? 'cloud_' : 'rbs_'),
            'price_unit' => '/mo',
        ]);

        return view('admin.pricing.form', [
            'title' => $type === 'cloud' ? 'Cloud Based Pricing' : 'Request Based Pricing',
            'type' => $type,
            'tier' => $tier,
            'specsText' => '',
            'priceText' => '',
            'priceUnits' => self::PRICE_UNITS,
        ]);
    }

    public function edit(string $type, string $tierKey): View
    {
        abort_unless(in_array($type, ['cloud', 'request'], true), 404);

        $tier = PricingTier::where('tier_key', $tierKey)->firstOrFail();
        $specsText = collect($tier->specs ?? [])->map(fn ($val, $key) => "{$key}: {$val}")->implode("\n");

        return view('admin.pricing.form', [
            'title' => $type === 'cloud' ? 'Cloud Based Pricing' : 'Request Based Pricing',
            'type' => $type,
            'tier' => $tier,
            'specsText' => $specsText,
            // Only pre-fill the custom-text override when the tier isn't in
            // auto-format mode (no price_unit set) — otherwise it'd show a
            // redundant copy of the auto-generated text.
            'priceText' => $tier->price_unit === null ? $tier->price_display : '',
            'priceUnits' => self::PRICE_UNITS,
        ]);
    }

    public function store(Request $request, string $type, PricingService $pricing): RedirectResponse
    {
        abort_unless(in_array($type, ['cloud', 'request'], true), 404);

        $data = $this->validateTier($request);

        $tierKey = $base = Str::slug($data['service_type'] . '-' . $data['name']);
        for ($i = 2; PricingTier::where('tier_key', $tierKey)->exists(); $i++) {
            $tierKey = "{$base}-{$i}";
        }

        $pricing->addTier([
            'tier_key' => $tierKey,
            'service_type' => $data['service_type'],
            'name' => $data['name'],
            'price_value' => $data['price_value'],
            'price_unit' => $data['price_value'] !== null ? $data['price_unit'] : null,
            'price_display' => $this->resolveDisplay($data),
            'specs' => $this->parseSpecs($data['specs']),
            'is_visible' => true,
            'display_order' => $data['display_order'] ?? 999,
        ], $request->user());

        return redirect()->route('admin.pricing.index', $type)->with('status', 'Pricing tier added.');
    }

    public function update(Request $request, string $tierKey, PricingService $pricing): RedirectResponse
    {
        $data = $this->validateTier($request);

        $pricing->updateTier($tierKey, [
            'service_type' => $data['service_type'],
            'name' => $data['name'],
            'price_value' => $data['price_value'],
            'price_unit' => $data['price_value'] !== null ? $data['price_unit'] : null,
            'price_display' => $this->resolveDisplay($data),
            'specs' => $this->parseSpecs($data['specs']),
            'display_order' => $data['display_order'] ?? 999,
        ], $request->user());

        return redirect()->route('admin.pricing.index', $request->input('type'))->with('status', 'Pricing tier updated.');
    }

    /**
     * @return array{service_type: string, name: string, price_value: ?string, price_unit: ?string, price_text: ?string, specs: ?string, display_order: ?int}
     */
    private function validateTier(Request $request): array
    {
        return $request->validate([
            'type' => ['required', 'in:cloud,request'],
            'service_type' => ['required', 'string', 'max:60', 'regex:/^(cloud|rbs)_[a-z0-9_]+$/'],
            'name' => ['required', 'string', 'max:150'],
            'price_value' => ['nullable', 'numeric', 'min:0'],
            'price_unit' => ['nullable', 'string', Rule::in(self::PRICE_UNITS)],
            // Required when price_value is left blank (e.g. "Contact for
            // Quote"). Optional otherwise — filling it in overrides the
            // auto-formatted "৳X,XXX.XX/unit" text, for the rare compound
            // price that isn't a single number + unit (e.g. a one-time fee
            // plus a separate yearly renewal).
            'price_text' => ['required_without:price_value', 'nullable', 'string', 'max:80'],
            'specs' => ['nullable', 'string', 'max:2000'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    /**
     * @param  array{price_value: ?string, price_unit: ?string, price_text: ?string}  $data
     */
    private function resolveDisplay(array $data): string
    {
        if (! empty($data['price_text'])) {
            return $data['price_text'];
        }

        return PricingTier::formatDisplay($data['price_value'], $data['price_unit'] ?? '');
    }

    /**
     * Parses the admin form's "label: value" per-line textarea into the
     * specs array — the `tier` key (if present) drives the public pricing
     * card's badge; every other key renders as a resource-detail row.
     *
     * @return array<string, string>
     */
    private function parseSpecs(?string $raw): array
    {
        $specs = [];

        foreach (preg_split('/\r?\n/', trim((string) $raw)) as $line) {
            if (! str_contains($line, ':')) {
                continue;
            }

            [$key, $val] = explode(':', $line, 2);
            $key = Str::slug(trim($key), '_');
            $val = trim($val);

            if ($key === '' || $val === '') {
                continue;
            }

            $specs[$key] = $val;
        }

        return $specs;
    }

    public function toggleVisibility(Request $request, string $tierKey, PricingService $pricing): RedirectResponse
    {
        $request->validate(['type' => ['required', 'in:cloud,request']]);
        $tier = PricingTier::where('tier_key', $tierKey)->firstOrFail();
        $pricing->toggleVisibility($tierKey, ! $tier->is_visible, $request->user());

        return back()->with('status', 'Visibility toggled.');
    }

    public function destroy(Request $request, string $tierKey, PricingService $pricing): RedirectResponse
    {
        $request->validate(['type' => ['required', 'in:cloud,request']]);
        $pricing->deleteTier($tierKey, $request->user());

        return back()->with('status', 'Tier deleted.');
    }
}
