@extends('layouts.web.master')
@section('title', 'Security')
@section('content')
    <main>
        <!-- Inner Hero Section -->
        <section class="inner-hero">
            <div class="container relative z-10 text-center">
                <h1 class="inner-title">
                    Your Data, <span class="cs-gradient">Protected</span> Always
                </h1>
                <p class="inner-text">
                    Security isn't just a feature at PayVault — it's the foundation of everything we build. We use bank-grade encryption to keep your payroll safe.
                </p>
            </div>
        </section>

        <!-- Data Protection Section -->
        <section class="section-spacing">
            <div class="container">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="order-2 lg:order-1">
                        <div class="relative">
                            <div class="absolute -inset-4 bg-green-500/10 blur-3xl rounded-full"></div>
                            <img src="{{ asset('images/secuity-img-001.jpeg') }}" alt="Data Protection" class="relative w-full h-auto rounded-[32px] shadow-2xl">
                        </div>
                    </div>
                    <div class="order-1 lg:order-2">
                        <h2 class="section-heading mb-8">Bank-Grade <span class="cs-gradient">Encryption</span></h2>
                        <p class="payroll-text mb-8">
                            We use the same encryption standards as leading financial institutions to ensure your payroll data remains confidential and secure at all times. Your sensitive information is never stored in plain text.
                        </p>
                        <ul class="space-y-4">
                            <li class="security-list">
                                <img src="{{ asset('images/check-icon.png') }}" alt="check" class="w-6 h-6">
                                <span class="payroll-text">256-bit SSL encryption for all data transfers</span>
                            </li>
                            <li class="security-list">
                                <img src="{{ asset('images/check-icon.png') }}" alt="check" class="w-6 h-6">
                                <span class="payroll-text">Multi-layered firewall protection</span>
                            </li>
                            <li class="security-list">
                                <img src="{{ asset('images/check-icon.png') }}" alt="check" class="w-6 h-6">
                                <span class="payroll-text">Regular security audits and penetration testing</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Compliance Section -->
        <section class="pb-24">
            <div class="mx-4 md:mx-[20px]">
                <div class="security-box">
                    <div class="container">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                        <div>
                            <h2 class="text-[32px] md:text-[48px] font-bold leading-tight mb-8 text-white">
                                Strict <span class="text-[#50C300]">Compliance</span> Standards
                            </h2>
                            <p class="security-text text-white/80">
                                We adhere to the highest industry standards to ensure that your business stays compliant with local and federal regulations effortlessly.
                            </p>
                            <ul class="space-y-4 mb-10">
                                <li class="security-list">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="check" class="w-5 h-5 brightness-0 invert">
                                    <span class="span-text">SOC 2 Type II compliant infrastructure</span>
                                </li>
                                <li class="security-list">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="check" class="w-5 h-5 brightness-0 invert">
                                    <span class="span-text">Automatic tax regulation updates</span>
                                </li>
                                <li class="security-list">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="check" class="w-5 h-5 brightness-0 invert">
                                    <span class="span-text">Secure data redundancy and backups</span>
                                </li>
                            </ul>
                            <div class="flex flex-wrap gap-4">
                                <a href="#" class="primary-btn">View Trust Center</a>
                            </div>
                        </div>
                        <div class="relative">
                            <img src="{{ asset('images/secuity-img-002.jpeg') }}" alt="Compliance" class="relative w-full h-auto rounded-3xl shadow-2xl">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- New Security Features Section -->
        <section class="pb-24">
            <div class="container text-center">
                <h2 class="section-heading mb-16">Advanced <span class="cs-gradient">Security</span> Features</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <img src="{{ asset('images/step-icon-01.png') }}" alt="Access Control" class="w-16 h-16">
                        </div>
                        <h3 class="feature-card-heading">Role-Based Access</h3>
                        <p class="feature-card-text">
                            Control exactly who has access to sensitive payroll data with customizable user roles and permissions.
                        </p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <img src="{{ asset('images/step-icon-02.png') }}" alt="Two-Factor" class="w-16 h-16">
                        </div>
                        <h3 class="feature-card-heading">Multi-Factor Auth</h3>
                        <p class="feature-card-text">
                            Add an extra layer of security to every account with multi-factor authentication (MFA) via SMS or app.
                        </p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <img src="{{ asset('images/step-icon-03.png') }}" alt="Audit Log" class="w-16 h-16">
                        </div>
                        <h3 class="feature-card-heading">Detailed Audit Logs</h3>
                        <p class="feature-card-text">
                            Track every action within the system with comprehensive audit logs, providing full transparency and accountability.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ready to Simplify CTA -->
        <section class="pb-24">
            <div class="mx-4 md:mx-[20px]">
                <div class="simple-payroll">
                    <div class="simple-payroll-content">
                        <div class="flex justify-center order-2 lg:order-1">
                            <img src="{{ asset('images/ready-simplify.png') }}" alt="Ready to Simplify" class="w-full max-w-[500px] h-auto">
                        </div>
                        <div class="order-1 lg:order-2">
                             <h2 class="section-heading mb-8">
                                Secure Your Payroll <br class="hidden md:block"> Today
                            </h2>
                            <p class="simple-payroll-text text-lg mb-12">
                                Join the thousands of businesses that trust PayVault with their most sensitive information.
                            </p>
                            <div class="flex flex-wrap gap-6">
                                <a href="#" class="primary-btn px-10">Get Started Now</a>
                                <a href="#" class="bg-black/5 text-[var(--text-secondary-color)] px-10 py-3 rounded-xl font-bold hover:bg-black/10 transition-all border border-black/5">Contact Sales</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
