@extends('layouts.frontend')

@section('title', 'Contact Us')

<?php
use App\Models\Setting;
$contact_email = Setting::get('page_contact_contact_email', $settings['contact_email'] ?? 'admin@ekscotech.edu.ng');
$contact_phone = Setting::get('page_contact_contact_phone', $settings['phone_number'] ?? '+2341234567890');
$address = Setting::get('page_contact_address', 'Ekiti State College of Technology, Iyin Road, Ado-Ekiti, Nigeria');
$additional_info = Setting::get('page_contact_additional_info', 'We are open Monday to Friday, 8am to 4pm.');
?>

@section('content')
<section class="section bg-white">
    <div class="container">
        <h2 class="page-title">Contact Us</h2>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card-custom p-4 h-100">
                    <h5 class="mb-4">Get in Touch</h5>

                    <!-- Logo instead of icon -->
                    <div class="mb-4 text-center">
                        @if($settings['logo'] ?? false)
                        <img src="{{ asset($settings['logo']) }}" alt="Institution Logo" style="max-height: 80px;">
                        @else
                        <i class="bi bi-building fs-1 text-primary"></i>
                        @endif
                    </div>

                    <div class="mb-3">
                        <i class="bi bi-envelope text-primary me-2"></i>
                        <span>{{ $contact_email }}</span>
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-telephone text-primary me-2"></i>
                        <span>{{ $contact_phone }}</span>
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-geo-alt text-primary me-2"></i>
                        <span>{{ $address }}</span>
                    </div>
                    @if($additional_info)
                    <div class="mt-4 pt-3 border-top">
                        <small class="text-muted">{{ $additional_info }}</small>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-custom p-4">
                    <h5 class="mb-4">Send us a Message</h5>
                    <form method="POST" action="{{ route('contact') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Your Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="bi bi-send me-2"></i>Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection