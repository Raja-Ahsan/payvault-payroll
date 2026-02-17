@extends('layouts.app')
@section('title', 'How It Works | DIY Payroll')
@section('content')
    <main>
        <!-- Inner Hero Section -->
        <section class="inner-hero">
            <div class="container relative z-10">
                <h1 class="inner-title">
                    Payroll in <span class="cs-gradient">Minutes</span>, Not Hours
                </h1>
                <p class="inner-text">
                    Discover how PayVault simplifies your payroll process through our intuitive four-step workflow designed for speed, accuracy, and security.
                </p>
            </div>
        </section>

        <!-- Steps Section (reused from home but more detailed) -->
        <section class="section-spacing">
            <div class="container">
                <div class="text-center mb-16">
                    <h2 class="section-heading">
                        Our <span class="cs-gradient">Streamlined</span> Process
                    </h2>
                    <p class="payroll-text mt-4 max-w-2xl mx-auto opacity-75">
                        We've broken down complex payroll calculations into four simple actions that anyone can manage.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-start">
                    <!-- Step 1 -->
                    <div class="flex gap-6 items-start">
                        <div class="shrink-0">
                            <img src="{{ asset('images/step-icon-01.png') }}" alt="Step 1" class="w-20 h-20 object-contain">
                        </div>
                        <div>
                            <h3 class="payroll-heading">1. Onboard Your Team</h3>
                            <p class="payroll-text">
                                Adding employees and contractors is a breeze. Simply enter their basic information, tax details, and payment preferences. Our system handles the rest, ensuring everyone is categorized correctly.
                            </p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex gap-6 items-start">
                        <div class="shrink-0">
                            <img src="{{ asset('images/step-icon-02.png') }}" alt="Step 2" class="w-20 h-20 object-contain">
                        </div>
                        <div>
                            <h3 class="payroll-heading">2. Run Payroll for the Period</h3>
                            <p class="payroll-text">
                                Select your pay frequency — weekly, bi-weekly, or monthly. Input hours worked or salary amounts. Our automated engine calculates gross-to-net payments, including all necessary deductions instantly.
                            </p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex gap-6 items-start">
                        <div class="shrink-0">
                            <img src="{{ asset('images/step-icon-03.png') }}" alt="Step 3" class="w-20 h-20 object-contain">
                        </div>
                        <div>
                            <h3 class="payroll-heading">3. Review and Approve</h3>
                            <p class="payroll-text">
                                Transparency is key. Review detailed reports before anything is finalized. Check for accuracy across departments or individual team members, and approve with a single click once you're satisfied.
                            </p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="flex gap-6 items-start">
                        <div class="shrink-0">
                            <img src="{{ asset('images/step-icon-04.png') }}" alt="Step 4" class="w-20 h-20 object-contain">
                        </div>
                        <div>
                            <h3 class="payroll-heading">4. Distribution and Payslips</h3>
                            <p class="payroll-text">
                                Once approved, funds are securely distributed via ACH. Employees receive automated notifications and can access their detailed payslips through the secure self-service portal immediately.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Detailed Feature Section -->
       <section class="pb-24 overflow-hidden">
            <div class="mx-4 md:mx-[20px]">
                <div class="security-box">
                    <div class="container">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                        <div>
                            <h2 class="text-[32px] md:text-[48px] font-bold leading-tight mb-8">
                                Why Our <span class="text-[#50C300]">Workflow</span> Works
                            </h2>
                            <p class="security-text">
                                We've built PayVault on the principle that business owners shouldn't need a degree in accounting to pay their staff. Our platform handles the complexity in the background so you can focus on growing your business.
                            </p>
                            <ul class="space-y-4">
                                <li class="security-list">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="check" class="w-5 h-5">
                                    <span class="span-text">Automated tax calculations and withholdings</span>
                                </li>
                                <li class="security-list">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="check" class="w-5 h-5">
                                    <span class="span-text">Real-time validation to prevent errors</span>
                                </li>
                                <li class="security-list">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="check" class="w-5 h-5">
                                    <span class="span-text">Seamless ACH integration for direct deposits</span>
                                </li>
                            </ul>
                        </div>
                        <div class="relative">
                            <img src="{{ asset('images/designed-payroll.png') }}" alt="Payroll Dashboard" class="w-full h-auto ">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ready to Simplify -->
        <section class="pb-20">
            <div class="mx-4 md:mx-[20px]">
                <div class="simple-payroll">
                    <div class="simple-payroll-content">
                        <!-- Left Image -->
                        <div class="flex justify-center order-2 lg:order-1">
                            <img src="{{ asset('images/ready-simplify.png') }}" alt="Ready to Simplify" class="w-full max-w-[500px] h-auto">
                        </div>

                        <!-- Right Content -->
                        <div class="order-1 lg:order-2">
                            <h2 class="section-heading mb-6">
                                Experience the <br class="hidden md:block"> PayVault Difference
                            </h2>
                            <p class="simple-payroll-text">
                                Join hundreds of businesses that have transformed their payroll from a headache into a highlight of their month.
                            </p>
                            <div class="flex flex-wrap gap-4">
                                <a href="#" class="primary-btn">Start Your Free Trial</a>
                                <a href="#" class="bg-[#D6E6CB] text-[var(--text-secondary-color)] px-8 py-3 rounded-xl font-semibold hover:bg-black/10 transition-all">Schedule a Demo</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
