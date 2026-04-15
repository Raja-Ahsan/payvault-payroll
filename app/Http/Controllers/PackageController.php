<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::query()->orderBy('sort_order')->orderBy('id')->get();

        return view('screens.admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('screens.admin.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'billing_label' => 'nullable|string|max:255',
            'billing_cycle' => ['required', 'string', Rule::in(['yearly', 'monthly', 'one_time'])],
            'cta_label' => 'nullable|string|max:255',
            'features_text' => 'nullable|string',
            'quickbooks_item_id' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $package = Package::create([
            'title' => $validated['title'],
            'price' => $validated['price'],
            'currency' => strtoupper($validated['currency']),
            'billing_label' => $validated['billing_label'] ?? null,
            'billing_cycle' => $validated['billing_cycle'],
            'cta_label' => $validated['cta_label'] ?? 'Start Today',
            'features' => $this->parseFeatures($request->input('features_text')),
            'quickbooks_item_id' => $validated['quickbooks_item_id'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Package created successfully',
            'redirect' => route('packages.index'),
            'data' => $package,
        ]);
    }

    public function edit(Package $package)
    {
        $featuresText = collect($package->features ?? [])
            ->filter(fn ($line) => filled($line))
            ->implode("\n");

        return view('screens.admin.packages.edit', compact('package', 'featuresText'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'billing_label' => 'nullable|string|max:255',
            'billing_cycle' => ['required', 'string', Rule::in(['yearly', 'monthly', 'one_time'])],
            'cta_label' => 'nullable|string|max:255',
            'features_text' => 'nullable|string',
            'quickbooks_item_id' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $package->update([
            'title' => $validated['title'],
            'price' => $validated['price'],
            'currency' => strtoupper($validated['currency']),
            'billing_label' => $validated['billing_label'] ?? null,
            'billing_cycle' => $validated['billing_cycle'],
            'cta_label' => $validated['cta_label'] ?? 'Start Today',
            'features' => $this->parseFeatures($request->input('features_text')),
            'quickbooks_item_id' => $validated['quickbooks_item_id'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Package updated successfully',
            'redirect' => route('packages.index'),
            'data' => $package->fresh(),
        ]);
    }

    public function delete(Package $package)
    {
        $package->delete();

        return response()->json([
            'success' => true,
            'message' => 'Package deleted successfully',
        ]);
    }

    /**
     * @return list<string>
     */
    protected function parseFeatures(?string $raw): array
    {
        if ($raw === null || trim($raw) === '') {
            return [];
        }

        return collect(preg_split("/\r\n|\n|\r/", $raw))
            ->map(fn ($line) => trim((string) $line))
            ->filter()
            ->values()
            ->all();
    }
}
