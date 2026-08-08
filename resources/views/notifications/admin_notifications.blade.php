@extends('layouts.adminmain')
@section('admindashboard')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fas fa-bell"></i> Admin Notifications</h4>
            @if($notifications->where('read_at', null)->count() > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-check-double"></i> Mark All as Read
                    </button>
                </form>
            @endif
        </div>

        @if($notifications->count() > 0)
            @foreach($notifications as $notification)
                <div class="card mb-3 {{ $notification->read_at ? '' : 'border-primary' }}" style="{{ $notification->read_at ? '' : 'background-color: #f0f7ff;' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                @if(isset($notification->data['type']) && $notification->data['type'] === 'new_service_request')
                                    <h6 class="card-title">
                                        <i class="fas fa-plus-circle text-success"></i>
                                        New Service Request
                                    </h6>
                                @elseif(isset($notification->data['type']) && $notification->data['type'] === 'bank_receipt_uploaded')
                                    <h6 class="card-title">
                                        <i class="fas fa-receipt text-warning"></i>
                                        Bank Receipt Uploaded
                                    </h6>
                                @elseif(isset($notification->data['type']) && $notification->data['type'] === 'service_request_status_update')
                                    <h6 class="card-title">
                                        <i class="fas fa-sync-alt text-info"></i>
                                        Service Request Update
                                    </h6>
                                @elseif(isset($notification->data['type']) && $notification->data['type'] === 'new_staff_registered')
                                    <h6 class="card-title">
                                        <i class="fas fa-user-plus text-success"></i>
                                        New Staff Registered
                                    </h6>
                                @elseif(isset($notification->data['type']) && $notification->data['type'] === 'new_client_registered')
                                    <h6 class="card-title">
                                        <i class="fas fa-user-plus text-primary"></i>
                                        New Client Registered
                                    </h6>
                                @else
                                    <h6 class="card-title">
                                        <i class="fas fa-bell text-info"></i>
                                        Notification
                                    </h6>
                                @endif

                                <p class="card-text">{{ $notification->data['message'] ?? 'You have a new notification.' }}</p>

                                @if(isset($notification->data['client_name']))
                                    <small class="text-muted">
                                        <i class="fas fa-user"></i> {{ $notification->data['client_name'] }}
                                    </small>
                                @endif
                                @if(isset($notification->data['client_email']))
                                    <small class="text-muted ms-3">
                                        <i class="fas fa-envelope"></i> {{ $notification->data['client_email'] }}
                                    </small>
                                @endif
                                @if(isset($notification->data['amount']))
                                    <small class="text-muted ms-3">
                                        <i class="fas fa-money-bill"></i> ₦{{ number_format((float) $notification->data['amount'], 2) }}
                                    </small>
                                @endif
                                @if(isset($notification->data['location']))
                                    <small class="text-muted ms-3">
                                        <i class="fas fa-map-marker-alt"></i> {{ $notification->data['location'] }}
                                    </small>
                                @endif
                                @if(isset($notification->data['package']))
                                    <small class="text-muted ms-3">
                                        <i class="fas fa-box"></i> {{ $notification->data['package'] }}
                                    </small>
                                @endif
                                @if(isset($notification->data['transaction_reference']))
                                    <small class="text-muted ms-3">
                                        <i class="fas fa-hashtag"></i> {{ $notification->data['transaction_reference'] }}
                                    </small>
                                @endif
                                @if(isset($notification->data['type']) && in_array($notification->data['type'], ['new_staff_registered', 'new_client_registered']))
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-envelope me-1"></i>{{ $notification->data['email'] ?? '' }}
                                            &nbsp;|&nbsp;
                                            <i class="fas fa-phone me-1"></i>{{ $notification->data['phone'] ?? '' }}
                                            @if(isset($notification->data['state']))
                                                &nbsp;|&nbsp;
                                                <i class="fas fa-map-marker-alt me-1"></i>{{ $notification->data['state'] }}
                                            @endif
                                        </small>
                                        <div class="mt-1">
                                            <a href="{{ route('admin.HouseHelp.edit', $notification->data['user_id']) }}"
                                                class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-eye me-1"></i>View Profile
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block">{{ $notification->created_at->diffForHumans() }}</small>
                                @if(!$notification->read_at)
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="mt-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-check"></i> Mark Read
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-secondary mt-1">Read</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-center">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                <p class="text-muted">You have no notifications yet.</p>
            </div>
        @endif
    </div>
@endsection
