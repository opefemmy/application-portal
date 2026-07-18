@extends('layouts.admin')

@section('title', 'Form Builder - Manage Fields')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
<li class="breadcrumb-item active">Form Builder</li>
@endsection

@section('content')
<div class="stat-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bi bi-ui-checks me-2"></i>Form Field Builder</h4>
            <p class="text-muted mb-0">Create and manage form fields for applications</p>
        </div>
        <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addFieldModal">
            <i class="bi bi-plus-circle me-2"></i>Add New Field
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @foreach($sections as $section)
    <div class="mb-4">
        <h5 class="text-capitalize mb-3">
            <i class="bi bi-folder me-2"></i>{{ str_replace('_', ' ', $section) }} Fields
        </h5>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Label</th>
                        <th>Field Name</th>
                        <th>Type</th>
                        <th>Required</th>
                        <th>Visible</th>
                        <th>Sort</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fields->where('section', $section) as $field)
                    <tr>
                        <td><strong>{{ $field->field_label }}</strong></td>
                        <td><code>{{ $field->field_name }}</code></td>
                        <td><span class="badge bg-secondary">{{ $field->field_type }}</span></td>
                        <td>
                            @if($field->is_required)
                            <span class="badge bg-danger">Required</span>
                            @else
                            <span class="badge bg-light text-dark">Optional</span>
                            @endif
                        </td>
                        <td>
                            @if($field->is_visible)
                            <span class="badge bg-success">Visible</span>
                            @else
                            <span class="badge bg-warning">Hidden</span>
                            @endif
                        </td>
                        <td>{{ $field->sort_order }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editFieldModal{{ $field->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.settings.form-builder.destroy', $field->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this field?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Field Modal -->
                    <div class="modal fade" id="editFieldModal{{ $field->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Field: {{ $field->field_label }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.settings.form-builder.update', $field->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Field Label</label>
                                            <input type="text" name="field_label" class="form-control" value="{{ $field->field_label }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" name="is_required" class="form-check-input" id="editRequired{{ $field->id }}" value="1" {{ $field->is_required ? 'checked' : '' }}>
                                                <label class="form-check-label" for="editRequired{{ $field->id }}">Required Field</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" name="is_visible" class="form-check-input" id="editVisible{{ $field->id }}" value="1" {{ $field->is_visible ? 'checked' : '' }}>
                                                <label class="form-check-label" for="editVisible{{ $field->id }}">Visible/Enabled</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Sort Order</label>
                                            <input type="number" name="sort_order" class="form-control" value="{{ $field->sort_order }}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary-custom">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>

<!-- Add Field Modal -->
<div class="modal fade" id="addFieldModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Field</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.settings.form-builder.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Section</label>
                        <select name="section" class="form-select" required>
                            <option value="personal">Personal Information</option>
                            <option value="academic">Academic Information</option>
                            <option value="employment">Employment Information</option>
                            <option value="details">Application Details</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Field Name (unique)</label>
                        <input type="text" name="field_name" class="form-control" placeholder="e.g., employer_name" required>
                        <small class="text-muted">Use underscores, no spaces</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Field Label (display)</label>
                        <input type="text" name="field_label" class="form-control" placeholder="e.g., Employer Name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Field Type</label>
                        <select name="field_type" class="form-select" required>
                            <option value="text">Text Input</option>
                            <option value="email">Email</option>
                            <option value="number">Number</option>
                            <option value="date">Date</option>
                            <option value="textarea">Text Area</option>
                            <option value="select">Dropdown Select</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="radio">Radio Button</option>
                            <option value="file">File Upload</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_required" class="form-check-input" id="addRequired" value="1">
                            <label class="form-check-label" for="addRequired">Required Field</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_visible" class="form-check-input" id="addVisible" value="1" checked>
                            <label class="form-check-label" for="addVisible">Visible/Enabled</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">Create Field</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection