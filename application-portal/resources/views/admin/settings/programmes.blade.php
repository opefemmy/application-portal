@extends('layouts.admin')

@section('title', 'Programmes & Positions')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
<li class="breadcrumb-item active">Programmes</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="stat-card">
            <h5 class="mb-4">Add New Programme</h5>
            <form method="POST" action="{{ route('admin.settings.programmes.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Programme Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g., Computer Science" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Programme Code</label>
                    <input type="text" name="code" class="form-control" placeholder="e.g., CSC" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <input type="text" name="department" class="form-control" placeholder="e.g., Science" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Brief description..."></textarea>
                </div>
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" checked>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">
                    <i class="bi bi-plus-circle me-2"></i>Add Programme
                </button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Programmes & Positions List</h4>
                <span class="badge bg-primary">{{ count($programmes) }} Total</span>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(count($programmes) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($programmes as $index => $programme)
                        <tr>
                            <td><strong>{{ $programme['code'] }}</strong></td>
                            <td>{{ $programme['name'] }}</td>
                            <td>{{ $programme['department'] }}</td>
                            <td>
                                @if($programme['is_active'] ?? true)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $index }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.settings.programmes.destroy', $index) }}" class="d-inline" onsubmit="return confirm('Delete this programme?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $index }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit: {{ $programme['name'] }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.settings.programmes.update', $index) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Programme Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ $programme['name'] }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Programme Code</label>
                                                <input type="text" name="code" class="form-control" value="{{ $programme['code'] }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Department</label>
                                                <input type="text" name="department" class="form-control" value="{{ $programme['department'] }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" rows="2">{{ $programme['description'] ?? '' }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" name="is_active" class="form-check-input" id="editActive{{ $index }}" value="1" {{ ($programme['is_active'] ?? true) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="editActive{{ $index }}">Active</label>
                                                </div>
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
            @else
            <div class="text-center py-5">
                <i class="bi bi-folder-plus fs-1 text-muted mb-3"></i>
                <p class="text-muted">No programmes added yet.</p>
                <p class="text-muted">Add programmes above that applicants can select when applying.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection