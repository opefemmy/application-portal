@extends('layouts.admin')

@section('title', 'Manage Roles')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
<li class="breadcrumb-item active">Roles</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="stat-card">
            <h5 class="mb-4">Create New Role</h5>
            <form method="POST" action="{{ route('admin.settings.roles.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Role Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g., Registrar" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role Slug (unique)</label>
                    <input type="text" name="slug" class="form-control" placeholder="e.g., registrar" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Brief description..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Permissions</label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="permissions[]" class="form-check-input" id="perm_view" value="view-applications">
                                <label class="form-check-label" for="perm_view">View Applications</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="permissions[]" class="form-check-input" id="perm_download" value="download-applications">
                                <label class="form-check-label" for="perm_download">Download Documents</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="permissions[]" class="form-check-input" id="perm_manage" value="manage-applications">
                                <label class="form-check-label" for="perm_manage">Manage Applications</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="permissions[]" class="form-check-input" id="perm_reports" value="view-reports">
                                <label class="form-check-label" for="perm_reports">View Reports</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="permissions[]" class="form-check-input" id="perm_settings" value="manage-settings">
                                <label class="form-check-label" for="perm_settings">Manage Settings</label>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">
                    <i class="bi bi-shield-plus me-2"></i>Create Role
                </button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">All Roles</h4>
                <span class="badge bg-primary">{{ $roles->count() }} Total</span>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Permissions</th>
                            <th>Users</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                        <tr>
                            <td><strong>{{ $role->name }}</strong></td>
                            <td><code>{{ $role->slug }}</code></td>
                            <td>{{ $role->description ?? '-' }}</td>
                            <td>
                                @php $perms = is_array($role->permissions) ? $role->permissions : json_decode($role->permissions, true) @endphp
                                @if($perms && count($perms) > 0)
                                <span class="badge bg-info">{{ count($perms) }}</span>
                                @else
                                <span class="badge bg-secondary">None</span>
                                @endif
                            </td>
                            <td>{{ $role->administrators->count() }}</td>
                            <td>
                                @if($role->administrators->count() == 0)
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $role->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.settings.roles.destroy', $role->id) }}" class="d-inline" onsubmit="return confirm('Delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @else
                                <small class="text-muted">In use</small>
                                @endif
                            </td>
                        </tr>

                        <!-- Edit Role Modal -->
                        <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Role: {{ $role->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.settings.roles.update', $role->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Role Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" rows="2">{{ $role->description }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Permissions</label>
                                                @php $currentPerms = is_array($role->permissions) ? $role->permissions : json_decode($role->permissions, true) @endphp
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <input type="checkbox" name="permissions[]" class="form-check-input" id="edit_view_{{ $role->id }}" value="view-applications" {{ in_array('view-applications', $currentPerms ?? []) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="edit_view_{{ $role->id }}">View Applications</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="permissions[]" class="form-check-input" id="edit_download_{{ $role->id }}" value="download-applications" {{ in_array('download-applications', $currentPerms ?? []) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="edit_download_{{ $role->id }}">Download Documents</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="permissions[]" class="form-check-input" id="edit_manage_{{ $role->id }}" value="manage-applications" {{ in_array('manage-applications', $currentPerms ?? []) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="edit_manage_{{ $role->id }}">Manage Applications</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <input type="checkbox" name="permissions[]" class="form-check-input" id="edit_reports_{{ $role->id }}" value="view-reports" {{ in_array('view-reports', $currentPerms ?? []) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="edit_reports_{{ $role->id }}">View Reports</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="permissions[]" class="form-check-input" id="edit_settings_{{ $role->id }}" value="manage-settings" {{ in_array('manage-settings', $currentPerms ?? []) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="edit_settings_{{ $role->id }}">Manage Settings</label>
                                                        </div>
                                                    </div>
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
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No roles found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection