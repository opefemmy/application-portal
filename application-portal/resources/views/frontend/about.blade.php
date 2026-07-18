@extends('layouts.frontend')

@section('title', 'About Us')

@section('content')
<section class="section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="page-title">About {{ $settings['institution_name'] ?? 'Our Institution' }}</h2>
                <p class="lead">Welcome to {{ $settings['portal_name'] ?? 'Online Application Portal' }}, your gateway to new opportunities.</p>
                <p>We are committed to providing a seamless, transparent, and efficient application process for all candidates. Our online portal allows you to apply from anywhere in the world, at any time.</p>
                <p>Our mission is to make the application process simple, secure, and accessible to everyone. We continuously improve our system to ensure a positive experience for all applicants.</p>
            </div>
        </div>
    </div>
</section>
@endsection