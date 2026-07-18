@extends('layouts.admin')

@section('title', 'Edit ' . $pageName)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.settings.pages') }}">Page Builder</a></li>
<li class="breadcrumb-item active">Edit {{ $pageName }}</li>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.settings.pages.update', $page) }}">
    @csrf
    @method('PUT')

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- Hero/Header Section -->
            @if(in_array($page, ['home', 'about', 'contact', 'how-to-apply']))
            <div class="stat-card mb-4">
                <h5 class="mb-4"><i class="bi bi-card-heading me-2"></i>Header Section</h5>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Page Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $content['title'] ?? '' }}">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Subtitle</label>
                        <input type="text" name="subtitle" class="form-control" value="{{ $content['subtitle'] ?? '' }}">
                    </div>
                </div>
            </div>
            @endif

            <!-- About Page Content -->
            @if($page === 'about')
            <div class="stat-card mb-4">
                <h5 class="mb-4"><i class="bi bi-info-circle me-2"></i>About Content</h5>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Mission Statement</label>
                        <textarea name="mission" class="form-control" rows="3">{{ $content['mission'] ?? '' }}</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Vision Statement</label>
                        <textarea name="vision" class="form-control" rows="3">{{ $content['vision'] ?? '' }}</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Core Values</label>
                        <textarea name="values" class="form-control" rows="3">{{ $content['values'] ?? '' }}</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">History</label>
                        <textarea name="history" class="form-control" rows="4">{{ $content['history'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            @endif

            <!-- Requirements Page -->
            @if($page === 'requirements')
            <div class="stat-card mb-4">
                <h5 class="mb-4"><i class="bi bi-list-check me-2"></i>Requirements Content</h5>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">General Requirements</label>
                        <textarea name="general_requirements" class="form-control" rows="4">{{ $content['general_requirements'] ?? '' }}</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Entry Requirements</label>
                        <textarea name="entry_requirements" class="form-control" rows="4">{{ $content['entry_requirements'] ?? '' }}</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Required Documents</label>
                        <textarea name="documents_required" class="form-control" rows="4">{{ $content['documents_required'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            @endif

            <!-- How to Apply -->
            @if($page === 'how-to-apply')
            <div class="stat-card mb-4">
                <h5 class="mb-4"><i class="bi bi-question-circle me-2"></i>How to Apply Content</h5>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Application Instructions</label>
                        <textarea name="instructions" class="form-control" rows="5">{{ $content['instructions'] ?? '' }}</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Important Tips</label>
                        <textarea name="tips" class="form-control" rows="4">{{ $content['tips'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            @endif

            <!-- FAQ Page -->
            @if($page === 'faq')
            <div class="stat-card mb-4">
                <h5 class="mb-4"><i class="bi bi-chat-square-text me-2"></i>FAQ Content</h5>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">FAQs (Format: Question? Answer)</label>
                        <textarea name="faqs" class="form-control" rows="8" placeholder="Q1: What is the application fee?&#10;A1: The application fee is N2,000.&#10;&#10;Q2: When is the deadline?&#10;A2: The deadline is December 31st.">{{ $content['faqs'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            @endif

            <!-- Contact Page -->
            @if($page === 'contact')
            <div class="stat-card mb-4">
                <h5 class="mb-4"><i class="bi bi-envelope me-2"></i>Contact Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email" class="form-control" value="{{ $content['contact_email'] ?? '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="contact_phone" class="form-control" value="{{ $content['contact_phone'] ?? '' }}">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3">{{ $content['address'] ?? '' }}</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Additional Info</label>
                        <textarea name="additional_info" class="form-control" rows="3">{{ $content['additional_info'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            @endif

            <button type="submit" class="btn btn-primary-custom mb-4">
                <i class="bi bi-save me-2"></i>Save Changes
            </button>
        </div>

        <div class="col-md-4">
            <div class="stat-card mb-4 sticky-top" style="top: 20px;">
                <h5 class="mb-3">Preview</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route($page === 'home' ? 'home' : $page) }}" target="_blank" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>View Live Page
                    </a>
                </div>
                <hr>
                <h6 class="mb-3">Quick Tips</h6>
                <ul class="list-unstyled small text-muted">
                    <li class="mb-2"><i class="bi bi-check text-success me-1"></i>Changes save automatically</li>
                    <li class="mb-2"><i class="bi bi-check text-success me-1"></i>Use clear, short text</li>
                    <li class="mb-2"><i class="bi bi-info text-info me-1"></i>HTML allowed in text areas</li>
                </ul>
            </div>
        </div>
    </div>
</form>
@endsection