<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\StateReportingMethodOption;
use App\Models\StateReportingTaxType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StateReportingCatalogController extends Controller
{
    /**
     * @return array<string, string>
     */
    protected function flowKindOptions(): array
    {
        return [
            'icesa' => 'ICESA / electronic file',
            'generic' => 'Generic report (manual filing)',
            'printed_form' => 'Printed state form',
        ];
    }

    public function index(): View
    {
        $taxTypes = StateReportingTaxType::query()
            ->withCount('methodOptions')
            ->orderBy('state_code')
            ->orderBy('sort_order')
            ->get();

        $grouped = $taxTypes->groupBy('state_code');

        return view('screens.admin.state-reporting-catalog.index', [
            'grouped' => $grouped,
            'taxTypes' => $taxTypes,
        ]);
    }

    public function createTaxType(): View
    {
        $states = State::query()->orderBy('name')->get();

        return view('screens.admin.state-reporting-catalog.tax-type-form', [
            'taxType' => null,
            'states' => $states,
        ]);
    }

    public function storeTaxType(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'state_code' => 'required|string|size:2',
            'slug' => [
                'required',
                'string',
                'max:64',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('state_reporting_tax_types', 'slug')->where(function ($q) use ($request) {
                    return $q->where('state_code', strtoupper((string) $request->input('state_code')));
                }),
            ],
            'label' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0|max:65535',
            'is_active' => 'nullable|boolean',
            'meta' => 'nullable|string',
        ]);

        $meta = $this->parseMetaInput($request->input('meta'));

        StateReportingTaxType::query()->create([
            'state_code' => strtoupper($validated['state_code']),
            'slug' => $validated['slug'],
            'label' => $validated['label'],
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
            'meta' => $meta,
        ]);

        return redirect()
            ->route('admin.forms.state-reporting.catalog.index')
            ->with('success', 'Reporting tax type created.');
    }

    public function editTaxType(StateReportingTaxType $taxType): View
    {
        $states = State::query()->orderBy('name')->get();

        return view('screens.admin.state-reporting-catalog.tax-type-form', [
            'taxType' => $taxType,
            'states' => $states,
        ]);
    }

    public function updateTaxType(Request $request, StateReportingTaxType $taxType): RedirectResponse
    {
        $validated = $request->validate([
            'state_code' => 'required|string|size:2',
            'slug' => [
                'required',
                'string',
                'max:64',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('state_reporting_tax_types', 'slug')
                    ->ignore($taxType->id)
                    ->where(function ($q) use ($request) {
                        return $q->where('state_code', strtoupper((string) $request->input('state_code')));
                    }),
            ],
            'label' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0|max:65535',
            'is_active' => 'nullable|boolean',
            'meta' => 'nullable|string',
        ]);

        $meta = $this->parseMetaInput($request->input('meta'));

        $taxType->update([
            'state_code' => strtoupper($validated['state_code']),
            'slug' => $validated['slug'],
            'label' => $validated['label'],
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
            'meta' => $meta,
        ]);

        return redirect()
            ->route('admin.forms.state-reporting.catalog.index')
            ->with('success', 'Reporting tax type updated.');
    }

    public function destroyTaxType(StateReportingTaxType $taxType): RedirectResponse
    {
        $taxType->delete();

        return redirect()
            ->route('admin.forms.state-reporting.catalog.index')
            ->with('success', 'Reporting tax type deleted.');
    }

    public function methodsIndex(StateReportingTaxType $taxType): View
    {
        $methods = $taxType->methodOptions()->orderBy('sort_order')->get();
        $flowKinds = $this->flowKindOptions();

        return view('screens.admin.state-reporting-catalog.methods-index', [
            'taxType' => $taxType,
            'methods' => $methods,
            'flowKinds' => $flowKinds,
        ]);
    }

    public function createMethod(StateReportingTaxType $taxType): View
    {
        return view('screens.admin.state-reporting-catalog.method-form', [
            'taxType' => $taxType,
            'method' => null,
            'flowKinds' => $this->flowKindOptions(),
        ]);
    }

    public function storeMethod(Request $request, StateReportingTaxType $taxType): RedirectResponse
    {
        $validated = $request->validate([
            'slug' => ['required', 'string', 'max:64', 'regex:/^[a-z0-9_]+$/', Rule::unique('state_reporting_method_options', 'slug')->where('state_reporting_tax_type_id', $taxType->id)],
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link_text' => 'nullable|string|max:255',
            'flow_kind' => ['required', 'string', Rule::in(array_keys($this->flowKindOptions()))],
            'sort_order' => 'nullable|integer|min:0|max:65535',
            'is_active' => 'nullable|boolean',
            'meta' => 'nullable|string',
        ]);

        $meta = $this->parseMetaInput($request->input('meta'));

        StateReportingMethodOption::query()->create([
            'state_reporting_tax_type_id' => $taxType->id,
            'slug' => $validated['slug'],
            'label' => $validated['label'],
            'description' => $validated['description'] ?? null,
            'link_text' => $validated['link_text'] ?? null,
            'flow_kind' => $validated['flow_kind'],
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
            'meta' => $meta,
        ]);

        return redirect()
            ->route('admin.forms.state-reporting.catalog.methods.index', $taxType)
            ->with('success', 'Method option created.');
    }

    public function editMethod(StateReportingTaxType $taxType, StateReportingMethodOption $method): View
    {
        abort_unless((int) $method->state_reporting_tax_type_id === (int) $taxType->id, 404);

        return view('screens.admin.state-reporting-catalog.method-form', [
            'taxType' => $taxType,
            'method' => $method,
            'flowKinds' => $this->flowKindOptions(),
        ]);
    }

    public function updateMethod(Request $request, StateReportingTaxType $taxType, StateReportingMethodOption $method): RedirectResponse
    {
        abort_unless((int) $method->state_reporting_tax_type_id === (int) $taxType->id, 404);

        $validated = $request->validate([
            'slug' => ['required', 'string', 'max:64', 'regex:/^[a-z0-9_]+$/', Rule::unique('state_reporting_method_options', 'slug')->ignore($method->id)->where('state_reporting_tax_type_id', $taxType->id)],
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link_text' => 'nullable|string|max:255',
            'flow_kind' => ['required', 'string', Rule::in(array_keys($this->flowKindOptions()))],
            'sort_order' => 'nullable|integer|min:0|max:65535',
            'is_active' => 'nullable|boolean',
            'meta' => 'nullable|string',
        ]);

        $meta = $this->parseMetaInput($request->input('meta'));

        $method->update([
            'slug' => $validated['slug'],
            'label' => $validated['label'],
            'description' => $validated['description'] ?? null,
            'link_text' => $validated['link_text'] ?? null,
            'flow_kind' => $validated['flow_kind'],
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
            'meta' => $meta,
        ]);

        return redirect()
            ->route('admin.forms.state-reporting.catalog.methods.index', $taxType)
            ->with('success', 'Method option updated.');
    }

    public function destroyMethod(StateReportingTaxType $taxType, StateReportingMethodOption $method): RedirectResponse
    {
        abort_unless((int) $method->state_reporting_tax_type_id === (int) $taxType->id, 404);
        $method->delete();

        return redirect()
            ->route('admin.forms.state-reporting.catalog.methods.index', $taxType)
            ->with('success', 'Method option deleted.');
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function parseMetaInput(?string $raw): ?array
    {
        if ($raw === null || trim($raw) === '') {
            return null;
        }
        $decoded = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
            throw ValidationException::withMessages([
                'meta' => ['Meta must be valid JSON (object) or left empty.'],
            ]);
        }

        return $decoded;
    }
}
