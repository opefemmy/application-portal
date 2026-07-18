@extends('layouts.admin')

@section('title', 'Page Builder - Edit Pages')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
<li class="breadcrumb-item active">Page Builder</li>
@endsection

@section('content')
<div class="stat-card">
    <h4 class="mb-4"><i class="bi bi-layout-text-window-reverse me-2"></i>Page Builder</h4>
    <p class="text-muted mb-4">Edit the content of your website pages. Click on a page to edit its content.</p>

    <div class="row g-4">
        @foreach($pages as $key => $name)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-text fs-1 text-primary mb-3"></i>
                    <h5>{{ $name }}</h5>
                    <p class="text-muted small mb-3">Edit {{ Str::lower($name) }} content</p>
                    <a href="{{ route('admin.settings.pages.edit', $key) }}" class="btn btn-primary-custom btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit Page
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <hr class="my-5">

    <h5 class="mb-3">Quick Actions</h5>
    <div class="row g-3">
        <div class="col-md-3">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-secondary w-100">
                <i class="bi bi-eye me-2"></i>View Live Website
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.settings.branding') }}" class="btn btn-outline-primary w-100">
                <i class="bi bi-palette me-2"></i>Branding Settings
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.applications.index') }}" class="btn btn-outline-success w-100">
                <i class="bi bi-people me-2"></i>View Applications
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-info w-100">
                <i class="bi bi-gear me-2"></i>General Settings
            </a>
        </div>
    </div>
</div>
@endsection