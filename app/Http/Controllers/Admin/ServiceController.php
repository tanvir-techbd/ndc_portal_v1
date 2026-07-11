<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public const ICONS = ['cloud', 'storage', 'grid', 'check-circle', 'server', 'people', 'envelope', 'wifi', 'balance', 'shield', 'hosting', 'colocation', 'managed', 'vps', 'backup', 'email'];

    public function index(): View
    {
        return view('admin.services.index', [
            'groups' => Service::groups()->orderBy('display_order')->get(),
            'details' => Service::details()->orderBy('group_slug')->orderBy('display_order')->get()->groupBy('group_slug'),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.services.form', [
            'service' => new Service(['kind' => 'detail', 'group_slug' => $request->query('group')]),
            'groupOptions' => Service::groups()->orderBy('display_order')->pluck('name', 'slug'),
            'tiersText' => '',
            'featuresText' => '',
        ]);
    }

    public function edit(Service $service): View
    {
        return view('admin.services.form', [
            'service' => $service,
            'groupOptions' => Service::groups()->orderBy('display_order')->pluck('name', 'slug'),
            'tiersText' => implode("\n", $service->tiers ?? []),
            'featuresText' => implode("\n", $service->features ?? []),
        ]);
    }

    public function store(Request $request, AuditService $audit): RedirectResponse
    {
        $data = $this->validated($request);

        $slug = $base = Str::slug($data['name']);
        for ($i = 2; Service::where('slug', $slug)->exists(); $i++) {
            $slug = "{$base}-{$i}";
        }

        $service = Service::create($data + ['slug' => $slug]);
        $audit->record($request->user(), 'service.create', $service);

        return redirect()->route('admin.services.index')->with('status', 'Service added.');
    }

    public function update(Request $request, Service $service, AuditService $audit): RedirectResponse
    {
        $data = $this->validated($request);
        $service->update($data);
        $audit->record($request->user(), 'service.update', $service);

        return redirect()->route('admin.services.index')->with('status', 'Service updated.');
    }

    public function destroy(Request $request, Service $service, AuditService $audit): RedirectResponse
    {
        abort_if($service->kind === 'group' && Service::where('group_slug', $service->slug)->exists(), 422, 'Remove or reassign this group\'s catalog cards before deleting it.');

        $audit->record($request->user(), 'service.delete', $service, ['name' => $service->name]);
        $service->delete();

        return back()->with('status', 'Service removed.');
    }

    public function toggleFeatured(Request $request, Service $service, AuditService $audit): RedirectResponse
    {
        $service->update(['is_featured' => ! $service->is_featured]);
        $audit->record($request->user(), 'service.toggle_featured', $service);

        return back()->with('status', 'Updated.');
    }

    public function toggleVisible(Request $request, Service $service, AuditService $audit): RedirectResponse
    {
        $service->update(['is_visible' => ! $service->is_visible]);
        $audit->record($request->user(), 'service.toggle_visible', $service);

        return back()->with('status', 'Updated.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'kind' => ['required', 'in:group,detail'],
            'group_slug' => ['nullable', 'string', 'exists:services,slug'],
            'name' => ['required', 'string', 'max:150'],
            'tag' => ['nullable', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:2000'],
            'icon' => ['nullable', Rule::in(self::ICONS)],
            'tiers' => ['nullable', 'string', 'max:1000'],
            'features' => ['nullable', 'string', 'max:2000'],
            'is_featured' => ['nullable', 'boolean'],
            'is_visible' => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);

        return [
            'kind' => $data['kind'],
            'group_slug' => $data['kind'] === 'detail' ? $data['group_slug'] : null,
            'name' => $data['name'],
            'tag' => $data['tag'] ?? null,
            'description' => $data['description'],
            'icon' => $data['icon'] ?? null,
            'tiers' => $this->lines($data['tiers'] ?? null),
            'features' => $this->lines($data['features'] ?? null),
            'is_featured' => $request->boolean('is_featured'),
            'is_visible' => $request->boolean('is_visible'),
            'display_order' => $data['display_order'] ?? 999,
        ];
    }

    /**
     * @return array<int, string>
     */
    private function lines(?string $raw): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r?\n/', trim((string) $raw))), fn ($line) => $line !== ''));
    }
}
