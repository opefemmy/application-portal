@extends('layouts.frontend')

@section('title', 'Requirements')

@section('content')
<section class="section bg-white">
    <div class="container">
        <h2 class="page-title">Application Requirements</h2>
        <div class="row">
            <div class="col-lg-6">
                <div class="card-custom p-4 mb-4">
                    <h5><i class="bi bi-person-badge me-2 text-primary"></i>Personal Information</h5>
                    <ul class="list-unstyled mb-0">
                        <li><i class="bi bi-check-circle text-success me-2"></i>Valid government-issued ID</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Passport photograph</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Date of birth certificate</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Local Government Certificate</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-custom p-4 mb-4">
                    <h5><i class="bi bi-book me-2 text-primary"></i>Academic Qualifications</h5>
                    <ul class="list-unstyled mb-0">
                        <li><i class="bi bi-check-circle text-success me-2"></i>SSCE/WAEC Certificate</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Degree/Certificate</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Transcript (where applicable)</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>NYSC Certificate (where applicable)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection