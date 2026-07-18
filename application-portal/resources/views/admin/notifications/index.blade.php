@extends('layouts.admin')

@section('title', 'Notifications')

@section('breadcrumbs')
<li class="breadcrumb-item active">Notifications</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Notifications</h4>
    <form method="POST" action="{{ route('admin.notifications.mark-all-read') }}">
        @csrf
        <button type="submit" class="btn btn-outline-primary">Mark All as Read</button>
    </form>
</div>

<div class="stat-card">
    @forelse($notifications as $notification)
    <div class="notification-item p-3 border-bottom {{ $notification->is_read ? '' : 'bg-light' }}">
        <div class="d-flex justify-content-between align-items-start">
            <div class="d-flex align-items-start">
                <div class="notification-icon me-3">
                    @switch($notification->type)
                        @case('new_application')
                            <i class="bi bi-file-earmark-plus text-primary fs-4"></i>
                            @break
                        @case('status_update')
                            <i class="bi bi-arrow-up-circle text-info fs-4"></i>
                            @break
                        @default
                            <i class="bi bi-bell text-muted fs-4"></i>
                    @endswitch
                </div>
                <div>
                    <h6 class="mb-1">{{ $notification->title }}</h6>
                    <p class="mb-1 text-muted">{{ $notification->message }}</p>
                    <small class="text-muted">{{ $notification->created_at->format('F j, Y g:i A') }}</small>
                </div>
            </div>
            @if(!$notification->is_read)
            <form method="POST" action="{{ route('admin.notifications.read', $notification->id) }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary">Mark Read</button>
            </form>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="bi bi-bell-slash fs-1 text-muted d-block mb-2"></i>
        <p class="text-muted mb-0">No notifications</p>
    </div>
    @endforelse
</div>

<div class="mt-3">
    {{ $notifications->links() }}
</div>
@endsection

<style>
.notification-item:last-child {
    border-bottom: none !important;
}
</style>