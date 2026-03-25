@extends('layouts.client')

@section('title', 'Federal Payroll / EFTPS')
@section('page-title', 'Federal Payroll / EFTPS')
@section('page-description', 'Manage federal payroll tax payment workflow')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-gray-700 font-medium mb-3">Sign up and pay your federal payroll taxes online:</p>
        <a href="https://www.eftps.gov/eftps/" target="_blank" rel="noopener noreferrer"
            class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium underline">
            https://www.eftps.gov/eftps/
            <i class="fas fa-up-right-from-square ml-2 text-xs"></i>
        </a>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-3">All states:</h3>
        <p class="text-sm text-gray-700 mb-4">
        Require Employers to pay state unemployment insurance (SUI) tax, also known as SUTA. 
        </p>

        <div class="space-y-3">
            <div>
                <p class="text-sm font-semibold text-gray-700 mb-1">Federal payment link</p>
                <a href="https://www.eftps.gov/eftps/" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium underline">
                    Welcome To EFTPS - Login
                    <i class="fas fa-up-right-from-square ml-2 text-xs"></i>
                </a>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-700 mb-1">States website link</p>
                <a href="https://www.irs.gov/businesses/small-businesses-self-employed/state-government-websites"
                    target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium underline break-all">
                    https://www.irs.gov/businesses/small-businesses-self-employed/state-government-websites
                    <i class="fas fa-up-right-from-square ml-2 text-xs"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Federal Payroll Tax Deadlines</h3>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-3 border-b border-gray-200 text-gray-800 font-semibold">Quarters</th>
                        <th class="text-left px-4 py-3 border-b border-gray-200 text-gray-800 font-semibold">Quarter Ends</th>
                        <th class="text-left px-4 py-3 border-b border-gray-200 text-gray-800 font-semibold">941 Quarterly Due Dates</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 border-b border-gray-100 text-gray-700">Form 941 Q1 2025</td>
                        <td class="px-4 py-3 border-b border-gray-100 text-gray-700">Mar 31</td>
                        <td class="px-4 py-3 border-b border-gray-100 text-gray-700">April 30</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 border-b border-gray-100 text-gray-700">Form 941 Q2 2025</td>
                        <td class="px-4 py-3 border-b border-gray-100 text-gray-700">Jun 30</td>
                        <td class="px-4 py-3 border-b border-gray-100 text-gray-700">July 31</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 border-b border-gray-100 text-gray-700">Form 941 Q3 2025</td>
                        <td class="px-4 py-3 border-b border-gray-100 text-gray-700">Sep 30</td>
                        <td class="px-4 py-3 border-b border-gray-100 text-gray-700">October 31</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-700">Form 941 Q4 2025</td>
                        <td class="px-4 py-3 text-gray-700">Dec 31</td>
                        <td class="px-4 py-3 text-gray-700">January 31</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-5 space-y-2">
            <p class="text-gray-700"><span class="font-semibold">Form 940 Annual:</span> Due January 31</p>
            <p class="text-gray-800 font-semibold">
                TaxBandits e-file 941-only pack cost $21.42 for four quarters, saving 10%. [taxbandits +1] 
            </p>
            <a href="https://www.taxbandits.com" target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium underline">
                https://www.taxbandits.com
                <i class="fas fa-up-right-from-square ml-2 text-xs"></i>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">States Without Withholding</h3>
            <!-- <p class="text-sm text-gray-600 mb-4">These states generally do not withhold state income tax from payroll.</p> -->

            <div class="flex flex-wrap gap-2">
                @foreach (['Alaska', 'Florida', 'Nevada', 'New Hampshire', 'South Dakota', 'Tennessee', 'Texas', 'Washington', 'Wyoming'] as $state)
                    <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-sm font-medium">{{ $state }}</span>
                @endforeach
            </div>

            <div class="mt-4 p-4 rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-900 text-sm">
                Alaska, Florida, Nevada, New Hampshire, South Dakota, Tennessee, Texas, Washington, and Wyoming still require state unemployment insurance (SUI), also known as SUTA.
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">States With Withholding Tax</h3>
            <!-- <p class="text-sm text-gray-600 mb-4">Most states require payroll withholding for state income taxes.</p> -->

            <p class="text-gray-700 leading-7">
                Alabama, Arizona, Arkansas, California, Colorado, Connecticut, Delaware, Georgia, Hawaii, Idaho, Illinois, Indiana, Iowa, Kansas, Kentucky, Louisiana, Maine, Maryland, Massachusetts, Michigan, Minnesota, Mississippi, Missouri, Montana, Nebraska, New Jersey, New Mexico, New York, North Carolina, North Dakota, Ohio, Oklahoma, Oregon, Pennsylvania, Rhode Island, South Carolina, Utah, Vermont, Virginia, West Virginia, Wisconsin, and Washington, D.C.
            </p>
        </div>
    </div>

    
</div>
@endsection
