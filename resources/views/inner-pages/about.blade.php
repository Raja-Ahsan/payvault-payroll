@extends('layouts.app')
@section('title', 'About Us')
@section('content')
    <main>
        <!-- Inner Hero Section -->
        <section class="inner-hero">
            <div class="container relative z-10 text-center">
                <h1 class="inner-title">
                    Our Mission to <span class="cs-gradient">Simplify</span> Payroll
                </h1>
                <p class="inner-text">
                    We believe that paying your team should be the easiest part of your month. At PayVault, we're dedicated to making payroll accessible, secure, and stress-free for every business.
                </p>
            </div>
        </section>

        <!-- Our Story Section -->
        <section class="section-spacing">
            <div class="container">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <h2 class="section-heading mb-8">Empowering Businesses <br> Since <span class="cs-gradient">2023</span></h2>
                        <p class="payroll-text mb-6">
                            PayVault was founded by a team of software engineers and financial experts who saw first-hand how much time business owners wasted on manual payroll processes.
                        </p>
                        <p class="payroll-text mb-8">
                            We set out to build a platform that combines the power of enterprise-grade security with a user interface so intuitive that anyone can use it. Our goal is to give business owners their time back so they can focus on what they do best: building great companies.
                        </p>
                        <div class="flex gap-4">
                            <a href="#" class="primary-btn">Join Our Journey</a>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="absolute -inset-4 bg-green-500/10 blur-3xl rounded-full"></div>
                        <img src="{{ asset('images/about-image.png') }}" alt="Our Team" class="relative w-full h-auto rounded-[32px] shadow-2xl">
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="pb-24">
            <div class="container">
                <div class="stats-container">
                    <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-white/10">
                        <div class="stat-item">
                            <span class="stat-number">500+</span>
                            <span class="stat-label">Trusted Businesses</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">$10M+</span>
                            <span class="stat-label">Processed Monthly</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">99.9%</span>
                            <span class="stat-label">Accuracy Rate</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Values -->
        <section class="pb-24">
            <div class="container">
                <div class="text-center mb-16">
                    <h2 class="section-heading">The Core <span class="cs-gradient">Values</span> <br> That Drive Us</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="value-card">
                        <div class="feature-icon-wrapper">
                            <img src="{{ asset('images/step-icon-01.png') }}" alt="Security" class="w-12 h-12">
                        </div>
                        <h3 class="feature-card-heading">Security First</h3>
                        <p class="feature-card-text">We never compromise on the safety of your data. Bank-grade encryption is built into our DNA.</p>
                    </div>
                    <div class="value-card">
                        <div class="feature-icon-wrapper">
                            <img src="{{ asset('images/step-icon-02.png') }}" alt="Simplicity" class="w-12 h-12">
                        </div>
                        <h3 class="feature-card-heading">Radical Simplicity</h3>
                        <p class="feature-card-text">Payroll doesn't have to be complicated. We stripp out the jargon and keep things crystal clear.</p>
                    </div>
                    <div class="value-card">
                        <div class="feature-icon-wrapper">
                            <img src="{{ asset('images/step-icon-03.png') }}" alt="Innovation" class="w-12 h-12">
                        </div>
                        <h3 class="feature-card-heading">Continuous Innovation</h3>
                        <p class="feature-card-text">We're always building. Our team releases updates weekly to ensure you have the best tools available.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="pb-24">
            <div class="mx-4 md:mx-[20px]">
                <div class="simple-payroll">
                    <div class="simple-payroll-content">
                        <div class="flex justify-center order-2 lg:order-1">
                             <img src="{{ asset('images/ready-simplify.png') }}" alt="Ready to Simplify" class="w-full max-w-[500px] h-auto">
                        </div>
                        <div class="order-1 lg:order-2">
                            <h2 class="section-heading mb-8">Be Part of the <br> <span class="cs-gradient">PayVault</span> Future</h2>
                            <p class="simple-payroll-text text-lg mb-12">
                                We're just getting started. Join hundreds of businesses that have found a better way to manage their payroll.
                            </p>
                            <div class="flex flex-wrap gap-6">
                                <a href="#" class="primary-btn px-10">Get Started Today</a>
                                <a href="#" class="bg-black/5 text-[var(--text-secondary-color)] px-10 py-3 rounded-xl font-bold hover:bg-black/10 transition-all border border-black/5">Request a Demo</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
