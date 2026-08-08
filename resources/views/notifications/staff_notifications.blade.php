@extends('layouts.applicantmain')
@section('applicantdashboard')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fas fa-bell"></i> Notifications</h4>
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
                                @if(isset($notification->data['type']) && $notification->data['type'] === 'service_request_approved')
                                    <h6 class="card-title">
                                        <i class="fas fa-briefcase text-success"></i>
                                        New Service Request Available
                                    </h6>
                                @else
                                    <h6 class="card-title">
                                        <i class="fas fa-bell text-info"></i>
                                        Notification
                                    </h6>
                                @endif

                                <p class="card-text">{{ $notification->data['message'] ?? 'You have a new notification.' }}</p>

                                @if(isset($notification->data['location']))
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i> {{ $notification->data['location'] }}
                                    </small>
                                @endif
                                @if(isset($notification->data['package']))
                                    <small class="text-muted ms-3">
                                        <i class="fas fa-box"></i> {{ $notification->data['package'] }}
                                    </small>
                                @endif
                                @if(isset($notification->data['working_hour']))
                                    <small class="text-muted ms-3">
                                        <i class="fas fa-clock"></i> {{ $notification->data['working_hour'] }}
                                    </small>
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
