@extends('layouts.client')

@section('title', 'U.S. Employer Payroll Tax Guide')
@section('page-title', 'U.S. Employer Payroll Tax Guide')
@section('page-description', 'Federal & state overview for employer payroll tax compliance')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6 border-t-4" style="border-top-color: #1D5C24;">
        <h2 class="text-2xl font-bold mb-2" style="color: #1D5C24;">U.S. Employer Payroll Tax Guide (Federal & State Overview)
        </h2>
    </div>

    {{-- 1. SUI / SUTA --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-3 flex items-center" style="color: #1D5C24;">
            <span class="w-1 h-7 rounded-full mr-3 btn-gradient"></span>
            1. State Unemployment Insurance (SUI / SUTA)
        </h2>
        <ul class="list-disc pl-5 space-y-2 text-gray-700 mb-4 leading-relaxed">
            <li>
                <strong class="text-gray-900">All U.S. states require employers</strong> to pay
                <strong class="text-gray-900">State Unemployment Insurance (SUI) tax (also called SUTA)</strong>.
            </li>
            <li>This tax funds unemployment benefits for eligible workers.</li>
            <li>
                <strong class="text-gray-900">Important:</strong> Even states without income tax withholding still require SUI contributions.
            </li>
        </ul>
        <p class="text-sm font-semibold mb-2" style="color: #348C31;">State agency websites</p>
        <a href="https://www.irs.gov/businesses/small-businesses-self-employed/state-government-websites"
            target="_blank" rel="noopener noreferrer"
            class="inline-flex items-center text-sm font-medium hover:opacity-90 underline break-all"
            style="color: #1D5C24;">
            IRS state government directory
            <i class="fas fa-up-right-from-square ml-2 text-xs"></i>
        </a>
    </div>

    {{-- 2. Federal payments --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-3 flex items-center" style="color: #1D5C24;">
            <span class="w-1 h-7 rounded-full mr-3 btn-gradient"></span>
            2. Federal Payroll Tax Payments
        </h2>
        <ul class="list-disc pl-5 space-y-2 text-gray-700 mb-3 leading-relaxed">
            <li>
                Federal payroll taxes (withholding, Social Security, Medicare) are paid via:
                {{-- <ul class="list-disc pl-5 mt-2 space-y-1">
                    <li>
                        <abbr title="Electronic Federal Tax Payment System" class="border-b border-dotted cursor-help border-gray-600">EFTPS</abbr>
                        (Electronic Federal Tax Payment System)
                    </li>
                </ul> --}}
            </li>
            <li>
                EFTPS (Electronic Federal Tax Payment System)
            </li>
        </ul>
        <a href="https://www.eftps.gov" target="_blank" rel="noopener noreferrer"
            class="inline-flex items-center text-sm font-medium hover:opacity-90 underline"
            style="color: #1D5C24;">
            EFTPS — eftps.gov
            <i class="fas fa-up-right-from-square ml-2 text-xs"></i>
        </a>
    </div>

    {{-- 3. Filing deadlines --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-3 flex items-center" style="color: #1D5C24;">
            <span class="w-1 h-7 rounded-full mr-3 btn-gradient"></span>
            3. Federal payroll tax filing deadlines
        </h2>

        <h3 class="text-lg font-semibold mb-2 text-gray-800">Form 941 (Quarterly Federal Tax Return)</h3>
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                <thead class="btn-gradient text-white">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">Quarter</th>
                        <th class="text-left px-4 py-3 font-semibold">Period end</th>
                        <th class="text-left px-4 py-3 font-semibold">Filing deadline</th>
                    </tr>
                </thead>
                <tbody class="bg-white text-gray-700">
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3">Q1 2025</td>
                        <td class="px-4 py-3">March 31</td>
                        <td class="px-4 py-3">April 30, 2025</td>
                    </tr>
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3">Q2 2025</td>
                        <td class="px-4 py-3">June 30</td>
                        <td class="px-4 py-3">July 31, 2025</td>
                    </tr>
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3">Q3 2025</td>
                        <td class="px-4 py-3">September 30</td>
                        <td class="px-4 py-3">October 31, 2025</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">Q4 2025</td>
                        <td class="px-4 py-3">December 31</td>
                        <td class="px-4 py-3">January 31, 2026</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h3 class="text-lg font-semibold mb-2 text-gray-800">Form 940 (Annual FUTA Tax Return)</h3>
        <p class="text-gray-700 leading-relaxed">Due: <strong class="text-gray-900">January 31</strong> each year (for the prior calendar year).</p>
    </div>

    {{-- 4. E-filing --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-3 flex items-center" style="color: #1D5C24;">
            <span class="w-1 h-7 rounded-full mr-3 btn-gradient"></span>
            4. E-filing option
        </h2>
        <p class="text-gray-700 mb-3 leading-relaxed">
            Employers can e-file Form 941 using providers such as <strong class="text-gray-900">TaxBandits</strong>.
            Example pricing: about <strong class="text-gray-900">$21.42/year</strong> for a 4-quarter filing package (pricing may vary).
        </p>
        <a href="https://www.taxbandits.com" target="_blank" rel="noopener noreferrer"
            class="inline-flex items-center text-sm font-medium hover:opacity-90 underline"
            style="color: #1D5C24;">
            taxbandits.com
            <i class="fas fa-up-right-from-square ml-2 text-xs"></i>
        </a>
    </div>

    {{-- 5 & 6. States --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-3 flex items-center" style="color: #1D5C24;">
                <span class="w-1 h-7 rounded-full mr-3 btn-gradient"></span>
                5. States without income tax withholding
            </h2>
            <p class="text-sm text-gray-700 mb-4 leading-relaxed">
                These states do <strong class="text-gray-900">not</strong> require state income tax withholding, but <strong class="text-gray-900">do</strong> require SUI/SUTA:
            </p>
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach (['Alaska', 'Florida', 'Nevada', 'New Hampshire', 'South Dakota', 'Tennessee', 'Texas', 'Washington', 'Wyoming'] as $state)
                    <span class="px-3 py-1 rounded-full text-sm font-medium text-white btn-gradient">{{ $state }}</span>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-3 flex items-center" style="color: #1D5C24;">
                <span class="w-1 h-7 rounded-full mr-3 btn-gradient"></span>
                6. States with income tax withholding
            </h2>
            <p class="text-sm text-gray-700 mb-3 leading-relaxed">
                Employers in the following states must withhold state income tax from employee wages:
            </p>
            <p class="text-gray-700 text-sm leading-relaxed">
                Alabama, Arizona, Arkansas, California, Colorado, Connecticut, Delaware, Georgia, Hawaii, Idaho, Illinois, Indiana, Iowa, Kansas, Kentucky, Louisiana, Maine, Maryland, Massachusetts, Michigan, Minnesota, Mississippi, Missouri, Montana, Nebraska, New Jersey, New Mexico, New York, North Carolina, North Dakota, Ohio, Oklahoma, Oregon, Pennsylvania, Rhode Island, South Carolina, Utah, Vermont, Virginia, West Virginia, Wisconsin, and Washington, D.C.
            </p>
        </div>
    </div>

    {{-- 7. Takeaways --}}
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4" style="border-left-color: #348C31;">
        <h2 class="text-xl font-bold mb-4 flex items-center" style="color: #1D5C24;">
            <span class="w-1 h-7 rounded-full mr-3 btn-gradient"></span>
            7. Key employer takeaways
        </h2>
        <ul class="space-y-3 text-gray-700">
            <li class="flex items-start">
                <i class="fas fa-check-circle mt-0.5 mr-3 flex-shrink-0" style="color: #348C31;"></i>
                <span>All employers must pay SUI/SUTA, regardless of state income tax rules.</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle mt-0.5 mr-3 flex-shrink-0" style="color: #348C31;"></i>
                <span>Use EFTPS for federal payroll tax payments.</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle mt-0.5 mr-3 flex-shrink-0" style="color: #348C31;"></i>
                <span>File Form 941 quarterly and Form 940 annually.</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle mt-0.5 mr-3 flex-shrink-0" style="color: #348C31;"></i>
                <span>Check state-specific requirements on official state websites.</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle mt-0.5 mr-3 flex-shrink-0" style="color: #348C31;"></i>
                <span>Consider e-filing tools to simplify compliance.</span>
            </li>
        </ul>
    </div>
</div>
@endsection
