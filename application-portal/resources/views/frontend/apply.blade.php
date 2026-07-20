@extends('layouts.frontend')

@section('title', 'Apply Now')

@section('styles')
<style>
    .form-wizard {
        position: relative;
    }
    .wizard-step {
        display: none;
    }
    .wizard-step.active {
        display: block;
    }
    .progress-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        position: relative;
    }
    .progress-indicator::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 10%;
        right: 10%;
        height: 3px;
        background: #e9ecef;
        z-index: 0;
    }
    .progress-step {
        position: relative;
        z-index: 1;
        text-align: center;
    }
    .progress-step .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin: 0 auto 8px;
        transition: all 0.3s;
    }
    .progress-step.active .step-number {
        background: var(--accent);
        color: white;
    }
    .progress-step.completed .step-number {
        background: var(--success);
        color: white;
    }
    .progress-step .step-label {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 500;
    }
    .progress-step.active .step-label {
        color: var(--accent);
    }
    .file-upload-zone {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    .file-upload-zone:hover {
        border-color: var(--secondary);
        background: rgba(52, 152, 219, 0.05);
    }
    .file-upload-zone.has-file {
        border-color: var(--success);
        background: rgba(39, 174, 96, 0.05);
    }
    .document-preview {
        display: none;
        margin-top: 10px;
    }
    .document-preview.show {
        display: block;
    }
    .required-label::after {
        content: ' *';
        color: var(--danger);
    }
</style>
@endsection

@section('content')
<div class="section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card-custom p-4">
                    <h2 class="text-center mb-4">Application Form</h2>

                    <!-- Error Message -->
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <!-- Validation Errors -->
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>Please correct the errors below:
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <!-- Progress Indicator -->
                    <div class="progress-indicator">
                        <div class="progress-step active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Personal</div>
                        </div>
                        <div class="progress-step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">Academic</div>
                        </div>
                        <div class="progress-step" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Employment</div>
                        </div>
                        <div class="progress-step" data-step="4">
                            <div class="step-number">4</div>
                            <div class="step-label">Details</div>
                        </div>
                        <div class="progress-step" data-step="5">
                            <div class="step-number">5</div>
                            <div class="step-label">Documents</div>
                        </div>
                        <div class="progress-step" data-step="6">
                            <div class="step-number">6</div>
                            <div class="step-label">Review</div>
                        </div>
                    </div>

                    <form id="applicationForm" method="POST" action="{{ route('apply.submit') }}" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="application_type_id" id="application_type_id" value="{{ $applicationTypes->first()->id ?? '' }}">

                        <!-- Application Type Selection - Hidden when only one type -->
                        @if($applicationTypes->count() > 1)
                        <div class="wizard-step" data-step="0">
                            <h4 class="mb-4"><i class="bi bi-files-alt me-2"></i>Select Application Type</h4>
                            <div class="row g-3 mb-4">
                                @foreach($applicationTypes as $type)
                                <div class="col-md-6">
                                    <div class="card application-type-card" data-type-id="{{ $type->id }}" onclick="selectApplicationType({{ $type->id }}, {{ $type->id }})">
                                        <div class="card-body text-center py-4">
                                            <i class="bi bi-files-alt fs-1 text-primary mb-3"></i>
                                            <h5>{{ $type->name }}</h5>
                                            <p class="text-muted mb-0">{{ $type->description }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="alert alert-warning" id="type-selection-warning" style="display: none;">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Please select an application type to continue.
                            </div>
                            <div class="mt-4 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary-custom" id="btn-select-type" onclick="validateTypeSelection()">
                                    Continue <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                        @else
                        <script>
                            // Auto-select the only application type
                            document.addEventListener('DOMContentLoaded', function() {
                                const typeId = '{{ $applicationTypes->first()->id ?? '' }}';
                                if (typeId) {
                                    selectedTypeId = typeId;
                                    document.getElementById('application_type_id').value = typeId;
                                }
                            });
                        </script>
                        @endif

                        <!-- Step 1: Personal Information -->
                        <div class="wizard-step active" data-step="1">
                            <h4 class="mb-4"><i class="bi bi-person me-2"></i>Personal Information</h4>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label required-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" name="middle_name" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required-label">Gender</label>
                                    <select name="gender" class="form-select" required>
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required-label">Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required-label">Marital Status</label>
                                    <select name="marital_status" class="form-select" required>
                                        <option value="">Select Status</option>
                                        <option value="single">Single</option>
                                        <option value="married">Married</option>
                                        <option value="divorced">Divorced</option>
                                        <option value="widowed">Widowed</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required-label">Nationality</label>
                                    <input type="text" name="nationality" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required-label">State of Origin</label>
                                    <select name="state_of_origin" id="state_of_origin" class="form-select" required>
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                                <div class="col-md-4" id="lga-container">
                                    <label class="form-label required-label">Local Government</label>
                                    <select name="local_government" id="local_government" class="form-control" required disabled>
                                        <option value="">Select Local Government</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required-label">Residential Address</label>
                                    <textarea name="residential_address" class="form-control" rows="2" required></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Postal Address</label>
                                    <textarea name="postal_address" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required-label">Phone Number</label>
                                    <input type="tel" name="phone_number" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Alternative Phone</label>
                                    <input type="tel" name="alternative_phone" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="mt-4 text-end">
                                <button type="button" class="btn btn-primary-custom next-step">Next <i class="bi bi-arrow-right ms-2"></i></button>
                            </div>
                        </div>

                        <!-- Step 2: Academic Information -->
                        <div class="wizard-step" data-step="2">
                            <h4 class="mb-4"><i class="bi bi-book me-2"></i>Academic Information</h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label required-label">Highest Qualification</label>
                                    <select name="highest_qualification" class="form-select" required>
                                        <option value="">Select Qualification</option>
                                        <option value="SSCE">SSCE</option>
                                        <option value="NCE">NCE</option>
                                        <option value="ND">ND</option>
                                        <option value="HND">HND</option>
                                        <option value="Bachelor's Degree">Bachelor's Degree</option>
                                        <option value="Master's Degree">Master's Degree</option>
                                        <option value="PhD">PhD</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required-label">Institution Attended</label>
                                    <input type="text" name="institution_attended" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required-label">Course Studied</label>
                                    <input type="text" name="course_studied" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-label">Grade/Class</label>
                                    <select name="grade_class" class="form-select" required>
                                        <option value="">Select Grade</option>
                                        <option value="First Class">First Class</option>
                                        <option value="Second Class Upper">Second Class Upper</option>
                                        <option value="Second Class Lower">Second Class Lower</option>
                                        <option value="Third Class">Third Class</option>
                                        <option value="Pass">Pass</option>
                                        <option value="Credit">Credit</option>
                                        <option value="Merit">Merit</option>
                                        <option value="Distinction">Distinction</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-label">Graduation Year</label>
                                    <input type="number" name="graduation_year" class="form-control" min="1950" max="{{ date('Y') }}" required>
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary prev-step"><i class="bi bi-arrow-left me-2"></i>Previous</button>
                                <button type="button" class="btn btn-primary-custom next-step">Next <i class="bi bi-arrow-right ms-2"></i></button>
                            </div>
                        </div>

                        <!-- Step 3: Employment Information -->
                        <div class="wizard-step" data-step="3">
                            <h4 class="mb-4"><i class="bi bi-briefcase me-2"></i>Employment Information (Optional)</h4>

                            <div id="employment-container">
                                <div class="employment-entry row g-3 mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Employer</label>
                                        <input type="text" name="employment[0][employer]" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Position</label>
                                        <input type="text" name="employment[0][position]" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Years of Experience</label>
                                        <input type="number" name="employment[0][years_experience]" class="form-control" min="0">
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-remove-employment" style="display: none;" title="Remove">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-success mb-4" id="btn-add-employment">
                                <i class="bi bi-plus-circle me-2"></i>Add More Employment
                            </button>

                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary prev-step"><i class="bi bi-arrow-left me-2"></i>Previous</button>
                                <button type="button" class="btn btn-primary-custom next-step">Next <i class="bi bi-arrow-right ms-2"></i></button>
                            </div>
                        </div>

                        <!-- Step 4: Application Details -->
                        <div class="wizard-step" data-step="4">
                            <h4 class="mb-4"><i class="bi bi-file-earmark-text me-2"></i>Application Details</h4>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label required-label">Position Applying For</label>
                                    <select name="position_applying_for" class="form-select" required>
                                        <option value="">Select a position</option>
                                        @if(count($programmes ?? []) > 0)
                                            @foreach($programmes as $prog)
                                            <option value="{{ $prog['name'] }}">{{ $prog['code'] }} - {{ $prog['name'] }}</option>
                                            @endforeach
                                        @else
                                            <option value="Legal Officer">Legal Officer</option>
                                            <option value="NCE">NCE - Nigeria Certificate in Education</option>
                                            <option value="ND">ND - National Diploma</option>
                                            <option value="HND">HND - Higher National Diploma</option>
                                            <option value="PGDE">PGDE - Postgraduate Diploma in Education</option>
                                            <option value="Bachelor">Bachelor Degree (B.Ed, B.Sc, B.A)</option>
                                            <option value="Masters">Master's Degree (M.Ed, M.Sc, M.A)</option>
                                            <option value="PhD">Doctor of Philosophy (PhD)</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary prev-step"><i class="bi bi-arrow-left me-2"></i>Previous</button>
                                <button type="button" class="btn btn-primary-custom next-step">Next <i class="bi bi-arrow-right ms-2"></i></button>
                            </div>
                        </div>

                        <!-- Step 5: Document Uploads -->
                        <div class="wizard-step" data-step="5">
                            <h4 class="mb-4"><i class="bi bi-paperclip me-2"></i>Document Uploads</h4>
                            <p class="text-muted mb-4">Allowed formats: JPG, JPEG, PNG, PDF. Maximum file size: 10MB</p>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label required-label">Passport Photograph</label>
                                    <div class="file-upload-zone" onclick="document.getElementById('passport_photograph').click()">
                                        <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                                        <p class="mb-0">Click to upload passport photograph</p>
                                        <small class="text-muted">JPG, PNG - Max 2MB</small>
                                    </div>
                                    <input type="file" id="passport_photograph" name="passport_photograph" class="d-none" accept="image/jpeg,image/jpg,image/png" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required-label">Birth Certificate</label>
                                    <div class="file-upload-zone" onclick="document.getElementById('birth_certificate').click()">
                                        <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                                        <p class="mb-0">Click to upload birth certificate</p>
                                        <small class="text-muted">PDF, JPG, PNG - Max 5MB</small>
                                    </div>
                                    <input type="file" id="birth_certificate" name="birth_certificate" class="d-none" accept="application/pdf,image/jpeg,image/jpg,image/png" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required-label">Local Government Certificate</label>
                                    <div class="file-upload-zone" onclick="document.getElementById('lg_certificate').click()">
                                        <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                                        <p class="mb-0">Click to upload LG certificate</p>
                                        <small class="text-muted">PDF, JPG, PNG - Max 5MB</small>
                                    </div>
                                    <input type="file" id="lg_certificate" name="lg_certificate" class="d-none" accept="application/pdf,image/jpeg,image/jpg,image/png" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Degree Certificate</label>
                                    <div class="file-upload-zone" onclick="document.getElementById('degree_certificate').click()">
                                        <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                                        <p class="mb-0">Click to upload degree certificate</p>
                                        <small class="text-muted">PDF, JPG, PNG - Max 10MB</small>
                                    </div>
                                    <input type="file" id="degree_certificate" name="degree_certificate" class="d-none" accept="application/pdf,image/jpeg,image/jpg,image/png">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">O'Level Result</label>
                                    <div class="file-upload-zone" onclick="document.getElementById('olevel_result').click()">
                                        <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                                        <p class="mb-0">Click to upload O'Level result</p>
                                        <small class="text-muted">PDF, JPG, PNG - Max 10MB</small>
                                    </div>
                                    <input type="file" id="olevel_result" name="olevel_result" class="d-none" accept="application/pdf,image/jpeg,image/jpg,image/png">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">NYSC Certificate</label>
                                    <div class="file-upload-zone" onclick="document.getElementById('nysc_certificate').click()">
                                        <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                                        <p class="mb-0">Click to upload NYSC certificate</p>
                                        <small class="text-muted">PDF, JPG, PNG - Max 5MB</small>
                                    </div>
                                    <input type="file" id="nysc_certificate" name="nysc_certificate" class="d-none" accept="application/pdf,image/jpeg,image/jpg,image/png">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">CV/Resume</label>
                                    <div class="file-upload-zone" onclick="document.getElementById('cv').click()">
                                        <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                                        <p class="mb-0">Click to upload CV</p>
                                        <small class="text-muted">PDF - Max 5MB</small>
                                    </div>
                                    <input type="file" id="cv" name="cv" class="d-none" accept="application/pdf">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Recommendation Letter</label>
                                    <div class="file-upload-zone" onclick="document.getElementById('recommendation_letter').click()">
                                        <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                                        <p class="mb-0">Click to upload recommendation letter</p>
                                        <small class="text-muted">PDF - Max 5MB</small>
                                    </div>
                                    <input type="file" id="recommendation_letter" name="recommendation_letter" class="d-none" accept="application/pdf">
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary prev-step"><i class="bi bi-arrow-left me-2"></i>Previous</button>
                                <button type="button" class="btn btn-primary-custom next-step">Next <i class="bi bi-arrow-right ms-2"></i></button>
                            </div>
                        </div>

                        <!-- Step 6: Declaration -->
                        <div class="wizard-step" data-step="6">
                            <h4 class="mb-4"><i class="bi bi-check2-square me-2"></i>Declaration & Review</h4>

                            <div class="alert alert-info mb-4">
                                <h5 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Review Your Application</h5>
                                <p class="mb-0">Please review all information carefully before submitting. You can go back to make changes.</p>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="declaration" name="declaration" value="1" required>
                                <label class="form-check-label" for="declaration">
                                    I declare that the information provided in this application form is true, complete, and accurate to the best of my knowledge. I understand that any false or misleading information may result in rejection of my application or dismissal if admitted.
                                </label>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Date</label>
                                <input type="text" class="form-control" value="{{ date('F j, Y') }}" readonly>
                            </div>

                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary prev-step"><i class="bi bi-arrow-left me-2"></i>Previous</button>
                                <button type="submit" class="btn btn-success btn-lg" onclick="this.disabled=true;this.innerHTML='<span class=\'spinner-border spinner-border-sm me-2\'></span>Submitting...';this.form.submit();"><i class="bi bi-send me-2"></i>Submit Application</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Field configurations from database - for conditional rendering
    const fieldConfigs = {
        personal: @json($formFields['personal']->mapWithKeys(fn($f) => [$f->field_name => ['visible' => true, 'required' => $f->is_required]])),
        academic: @json($formFields['academic']->mapWithKeys(fn($f) => [$f->field_name => ['visible' => true, 'required' => $f->is_required]])),
        employment: @json($formFields['employment']->mapWithKeys(fn($f) => [$f->field_name => ['visible' => true, 'required' => $f->is_required]])),
        details: @json($formFields['details']->mapWithKeys(fn($f) => [$f->field_name => ['visible' => true, 'required' => $f->is_required]])),
    };

    // Function to update field visibility based on config
    function applyFieldConfigs() {
        // Personal fields
        Object.keys(fieldConfigs.personal).forEach(fieldName => {
            const config = fieldConfigs.personal[fieldName];
            const element = document.querySelector(`[name="${fieldName}"]`);
            if (element) {
                const container = element.closest('.col-md-4, .col-md-6, .col-md-12') || element.closest('.row, .mb-3');
                if (container) {
                    container.style.display = config.visible ? '' : 'none';
                }
            }
        });

        // Academic fields
        Object.keys(fieldConfigs.academic).forEach(fieldName => {
            const config = fieldConfigs.academic[fieldName];
            const element = document.querySelector(`[name="${fieldName}"]`);
            if (element) {
                const container = element.closest('.col-md-4, .col-md-6, .col-md-3');
                if (container) {
                    container.style.display = config.visible ? '' : 'none';
                }
            }
        });

        // Employment fields - handle the dynamic employment entries
        const employmentEntries = document.querySelectorAll('.employment-entry');
        employmentEntries.forEach(entry => {
            Object.keys(fieldConfigs.employment).forEach(fieldName => {
                const element = entry.querySelector(`[name*="${fieldName}"]`);
                if (element) {
                    const container = element.closest('.col-md-4, .col-md-3, .col-md-1');
                    if (container) {
                        container.style.display = fieldConfigs.employment[fieldName]?.visible ? '' : 'none';
                    }
                }
            });
        });

        // Details fields
        Object.keys(fieldConfigs.details).forEach(fieldName => {
            const config = fieldConfigs.details[fieldName];
            const element = document.querySelector(`[name="${fieldName}"]`);
            if (element) {
                const container = element.closest('.col-md-6, .col-md-4');
                if (container) {
                    container.style.display = config.visible ? '' : 'none';
                }
            }
        });
    }

    // Apply configs on page load
    document.addEventListener('DOMContentLoaded', applyFieldConfigs);

    // Form Wizard
    let currentStep = 1;
    const totalSteps = 6;

    function updateProgress() {
        $('.progress-step').each(function() {
            const step = $(this).data('step');
            $(this).removeClass('active completed');
            if (step < currentStep) {
                $(this).addClass('completed');
                $(this).find('.step-number').html('<i class="bi bi-check"></i>');
            } else if (step === currentStep) {
                $(this).addClass('active');
                $(this).find('.step-number').html(step);
            }
        });

        $('.wizard-step').removeClass('active');
        $('.wizard-step[data-step="' + currentStep + '"]').addClass('active');
    }

    $('.next-step').click(function() {
        // Validate current step
        const currentStepEl = $('.wizard-step[data-step="' + currentStep + '"]');
        const requiredFields = currentStepEl.find('[required]');
        let valid = true;

        requiredFields.each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                valid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (valid && currentStep < totalSteps) {
            currentStep++;
            updateProgress();
        }
    });

    $('.prev-step').click(function() {
        if (currentStep > 1) {
            currentStep--;
            updateProgress();
        }
    });

    // File upload preview with image thumbnail
    $('input[type="file"]').change(function() {
        const zone = $(this).closest('.col-md-6').find('.file-upload-zone');
        const file = this.files[0];

        if (file) {
            zone.addClass('has-file');

            // Create preview element
            let previewHtml = '';

            if (file.type.startsWith('image/')) {
                // Show image preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    zone.html(`
                        <div class="text-center">
                            <img src="${e.target.result}" alt="Preview" class="img-fluid mb-2" style="max-height: 150px; border-radius: 8px;">
                            <p class="mb-1 fw-bold text-success">${file.name}</p>
                            <small class="text-muted">${(file.size / 1024).toFixed(1)} KB</small>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile('${$(this).attr('id')}')">Remove</button>
                            </div>
                        </div>
                    `);
                };
                reader.readAsDataURL(file);
            } else {
                // Show file icon for non-image files
                zone.html(`
                    <div class="text-center">
                        <i class="bi bi-file-earmark-check fs-1 text-success mb-2"></i>
                        <p class="mb-1 fw-bold text-success">${file.name}</p>
                        <small class="text-muted">${(file.size / 1024).toFixed(1)} KB</small>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile('${$(this).attr('id')}')">Remove</button>
                        </div>
                    </div>
                `);
            }
        }
    });

    // Auto-save to localStorage
    $('#applicationForm').on('change', function() {
        localStorage.setItem('application_draft', JSON.stringify($(this).serializeArray()));
    });

    // Load draft
    const draft = localStorage.getItem('application_draft');
    if (draft) {
        const data = JSON.parse(draft);
        data.forEach(function(field) {
            $('[name="' + field.name + '"]').val(field.value);
        });
    }

    // Employment Add/Remove functionality
    let employmentIndex = 0;

    $('#btn-add-employment').click(function() {
        employmentIndex++;
        const html = `
            <div class="employment-entry row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Employer</label>
                    <input type="text" name="employment[${employmentIndex}][employer]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Position</label>
                    <input type="text" name="employment[${employmentIndex}][position]" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Years of Experience</label>
                    <input type="number" name="employment[${employmentIndex}][years_experience]" class="form-control" min="0">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remove-employment" title="Remove">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        $('#employment-container').append(html);
        updateRemoveButtons();
    });

    $(document).on('click', '.btn-remove-employment', function() {
        $(this).closest('.employment-entry').remove();
        updateRemoveButtons();
    });

    function updateRemoveButtons() {
        const entries = $('.employment-entry');
        if (entries.length > 1) {
            $('.btn-remove-employment').show();
        } else {
            $('.btn-remove-employment').hide();
        }
    }

    // Initialize remove buttons on page load
    updateRemoveButtons();

    // Application Type Selection
    let selectedTypeId = null;
    const applicationTypes = @json($applicationTypes->keyBy('id'));

    function selectApplicationType(typeId, element) {
        selectedTypeId = typeId;
        $('#application_type_id').val(typeId);

        // Remove selected class from all cards
        $('.application-type-card').removeClass('border-primary bg-light');
        $('.application-type-card').css('border', '');

        // Add selected class
        $(element.target).closest('.application-type-card').addClass('border-primary bg-light');
        $(element.target).closest('.application-type-card').css('border', '2px solid var(--primary)');

        $('#type-selection-warning').hide();
    }

    function validateTypeSelection() {
        if (!selectedTypeId) {
            $('#type-selection-warning').show();
            return false;
        }

        // Hide type selection and show step 1
        $('.wizard-step[data-step="0"]').removeClass('active');
        $('.wizard-step[data-step="1"]').addClass('active');

        // Update progress
        currentStep = 1;
        updateProgress();

        return true;
    }

    // Initialize - hide step 0 if only one type
    @if($applicationTypes->count() <= 1)
    $(document).ready(function() {
        currentStep = 1;
        updateProgress();
    });
    @else
    // Initialize first type as selected if only one type
    @if($applicationTypes->count() === 1)
    $(document).ready(function() {
        const type = $applicationTypes.values()[0];
        selectApplicationType(type.id, { target: $('.application-type-card').first() });
    });
    @endif
    @endif

    // Remove file function
    function removeFile(inputId) {
        const input = document.getElementById(inputId);
        input.value = ''; // Clear the input

        // Reset the upload zone
        const col = input.closest('.col-md-6');
        col.querySelector('.file-upload-zone').classList.remove('has-file');
    }
</script>
<script src="{{ asset('js/nigerian-lgas.js') }}"></script>
@endsection