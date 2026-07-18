@extends('layouts.frontend')

@section('title', 'About Us')

<?php
use App\Models\Setting;
$title = Setting::get('page_about_title', 'About Us');
$subtitle = Setting::get('page_about_subtitle', 'Learn more about our institution');
$mission = Setting::get('page_about_mission', 'To provide quality education and training that equips students with relevant skills for the workforce.');
$vision = Setting::get('page_about_vision', 'To be a center of excellence in technical and vocational education.');
$values = Setting::get('page_about_values', 'Excellence, Integrity, Innovation, Service');
$history = Setting::get('page_about_history', 'Our institution was established to provide quality education and training to students.');
?>

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">{{ $title }}</h1>
                <p class="lead">{{ $subtitle }}</p>
            </div>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="stat-card">
                    <h4 class="mb-4"><i class="bi bi-bullseye me-2 text-primary"></i>Our Mission</h4>
                    <p class="mb-0">{{ $mission }}</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="stat-card">
                    <h4 class="mb-4"><i class="bi bi-eye me-2 text-primary"></i>Our Vision</h4>
                    <p class="mb-0">{{ $vision }}</p>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="stat-card">
                    <h4 class="mb-4"><i class="bi bi-heart me-2 text-primary"></i>Core Values</h4>
                    <p class="mb-0">{{ $values }}</p>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="stat-card">
                    <h4 class="mb-4"><i class="bi bi-clock-history me-2 text-primary"></i>Our History</h4>
                    <p class="mb-0">{{ $history }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container text-center">
        <h2 class="mb-3">Ready to Join Us?</h2>
        <p class="mb-4">Take the first step towards your future career today</p>
        <a href="{{ route('apply') }}" class="btn btn-primary-custom btn-lg">
            <i class="bi bi-send me-2"></i>Apply Now
        </a>
    </div>
</section>
@endsection