@extends('layouts.admin')

@section('title', 'Configure Fields - ' . $type->name)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.settings.application-types') }}">Application Types</a></li>
<li class="breadcrumb-item active">Configure Fields</li>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.settings.application-types.fields.update', $type->id) }}">
    @csrf
    @method('PUT')

    <div class="stat-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Configure Fields for: {{ $type->name }}</h4>
                <p class="text-muted mb-0">Select which fields to include and mark as required</p>
            </div>
            <button type="submit" class="btn btn-primary-custom">
                <i class="bi bi-save me-2"></i>Save Configuration
            </button>
        </div>

        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Check the "Enabled" box to include a field in this application type. Check "Required" to make it mandatory.
        </div>

        @foreach($sections as $section => $fields)
        <div class="mb-4">
            <h5 class="text-capitalize mb-3">
                {{ str_replace('_', ' ', $section) }} Information
            </h5>
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;">Enabled</th>
                                <th>Field Label</th>
                                <th>Field Name</th>
                                <th>Type</th>
                                <th style="width: 80px;">Required</th>
                                <th style="width: 80px;">Sort Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fields as $field)
                            @php
                            $fieldConfig = $typeFields->get($field->id);
                            $isEnabled = $fieldConfig ? $fieldConfig->pivot->is_enabled : false;
                            $isRequired = $fieldConfig ? $fieldConfig->pivot->is_required : $field->is_required;
                            $sortOrder = $fieldConfig ? $fieldConfig->pivot->sort_order : $field->sort_order;
                            @endphp
                            <tr>
                                <td>
                                    <input type="checkbox"
                                           name="fields[{{ $field->id }}][enabled]"
                                           value="1"
                                           {{ $isEnabled ? 'checked' : '' }}
                                           class="form-check-input field-enabled"
                                           data-field-id="{{ $field->id }}">
                                </td>
                                <td>{{ $field->field_label }}</td>
                                <td><code>{{ $field->field_name }}</code></td>
                                <td><span class="badge bg-secondary">{{ $field->field_type }}</span></td>
                                <td>
                                    <input type="checkbox"
                                           name="fields[{{ $field->id }}][required]"
                                           value="1"
                                           {{ $isRequired ? 'checked' : '' }}
                                           class="form-check-input field-required"
                                           id="required_{{ $field->id }}">
                                </td>
                                <td>
                                    <input type="number"
                                           name="fields[{{ $field->id }}][sort_order]"
                                           value="{{ $sortOrder }}"
                                           class="form-control form-control-sm"
                                           style="width: 70px;">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('admin.settings.application-types') }}" class="btn btn-secondary me-2">Cancel</a>
            <button type="submit" class="btn btn-primary-custom">
                <i class="bi bi-save me-2"></i>Save Configuration
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-uncheck required when disabled
    document.querySelectorAll('.field-enabled').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const fieldId = this.dataset.fieldId;
            const requiredCheckbox = document.getElementById('required_' + fieldId);
            if (!this.checked) {
                requiredCheckbox.checked = false;
            }
        });
    });
});
</script>
@endpush
@endsection