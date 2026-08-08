@extends('layouts.usermain')
@section('userdashboard')
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
                <div class="card mb-3 {{ $notification->read_at ? '' : 'border-primary' }}"
                    style="{{ $notification->read_at ? '' : 'background-color: #f0f7ff;' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">

                                {{-- Title --}}
                                @if(isset($notification->data['type']) && $notification->data['type'] === 'staff_accepted_offer')
                                    <h6 class="card-title">
                                        <i class="fas fa-user-check text-success"></i>
                                        Staff Accepted Your Request
                                    </h6>
                                @elseif(isset($notification->data['type']) && $notification->data['type'] === 'staff_matched')
                                    <h6 class="card-title">
                                        <i class="fas fa-users text-success"></i>
                                        Staff Matched to Your Request
                                    </h6>
                                @elseif(isset($notification->data['type']) && $notification->data['type'] === 'service_request_status_update')
                                    <h6 class="card-title">
                                        <i class="fas fa-sync-alt text-primary"></i>
                                        Service Request Update
                                    </h6>
                                @elseif(isset($notification->data['type']) && $notification->data['type'] === 'staff_selection_confirmed')
                                    <h6 class="card-title">
                                        <i class="fas fa-check-circle text-success"></i>
                                        Staff Selection Confirmed
                                    </h6>
                                @else
                                    <h6 class="card-title">
                                        <i class="fas fa-bell text-info"></i>
                                        Notification
                                    </h6>
                                @endif

                                {{-- Message --}}
                                <p class="card-text">{{ $notification->data['message'] ?? 'You have a new notification.' }}</p>

                                {{-- Staff details — only show name, hide phone/email unless payment approved --}}
                                @if(isset($notification->data['staff_name']))
                                    @php
                                        $serviceRequestId = $notification->data['service_request_id'] ?? null;
                                        $serviceReq = $serviceRequestId
                                            ? \App\Models\ServiceRequest::find($serviceRequestId)
                                            : null;
                                        $paymentApproved = $serviceReq && $serviceReq->payment_status === 'Paid';
                                        $isClientManaged = $serviceReq && $serviceReq->management_plan === 'client-managed';
                                        $showContact = $paymentApproved || $isClientManaged;
                                    @endphp

                                    {{-- Always show staff name --}}
                                    <small class="text-muted">
                                        <i class="fas fa-user"></i> {{ $notification->data['staff_name'] }}
                                    </small>

                                    {{-- Only show phone/email if payment approved or client managed --}}
                                    @if($showContact)
                                        @if(isset($notification->data['staff_phone']))
                                            <small class="text-muted ms-3">
                                                <i class="fas fa-phone"></i>
                                                <a href="tel:{{ $notification->data['staff_phone'] }}">
                                                    {{ $notification->data['staff_phone'] }}
                                                </a>
                                            </small>
                                        @endif
                                        @if(isset($notification->data['staff_email']))
                                            <small class="text-muted ms-3">
                                                <i class="fas fa-envelope"></i> {{ $notification->data['staff_email'] }}
                                            </small>
                                        @endif
                                    @else
                                        <small class="text-muted ms-3">
                                            <i class="fas fa-lock"></i>
                                            Contact details hidden until payment is approved
                                        </small>
                                    @endif
                                @endif

                                {{-- Status update --}}
                                @if(isset($notification->data['new_status']))
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-tag"></i> Status: {{ ucfirst(str_replace('_', ' ', $notification->data['new_status'])) }}
                                    </small>
                                @endif

                                {{-- Services --}}
                                @if(isset($notification->data['services']))
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-concierge-bell"></i> {{ $notification->data['services'] }}
                                    </small>
                                @endif

                                {{-- Action buttons --}}
                                <div class="mt-2 d-flex flex-wrap gap-2">

                                    {{-- View Shortlist button for staff_accepted_offer and staff_matched --}}
                                    @if(isset($notification->data['service_request_id']) &&
                                        in_array($notification->data['type'] ?? '', ['staff_accepted_offer', 'staff_matched']))
                                        @php
                                            $srId = $notification->data['service_request_id'];
                                            $sr = \App\Models\ServiceRequest::find($srId);
                                        @endphp

                                        @if($sr && in_array($sr->matching_status, ['cancelled']) || ($sr && $sr->status === 'order_cancelled'))
                                            {{-- Booking cancelled --}}
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Booking Cancelled
                                            </span>

                                        @elseif($sr && $sr->selected_staff_id)
                                            {{-- Staff already selected — show staff profile button --}}
                                            @php
                                                $sl = \App\Models\StaffShortlist::where('service_request_id', $srId)
                                                    ->where('staff_id', $sr->selected_staff_id)
                                                    ->first();
                                            @endphp
                                            @if($sl && !in_array($sr->matching_status, ['deployed', 'completed', 'cancelled']))
                                                <a href="{{ route('booking.staff-profile', [$srId, $sl->id]) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-user me-1"></i>View Staff Profile
                                                </a>
                                            @elseif($sl && $sr->matching_status === 'deployed')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Staff Deployed
                                                </span>
                                            @endif

                                        @elseif($sr && \App\Models\StaffShortlist::where('service_request_id', $srId)
                                            ->whereIn('status', ['accepted', 'shortlisted'])->exists())
                                            {{-- Staff available on shortlist — show button --}}
                                            <a href="{{ route('booking.shortlist', $srId) }}"
                                                class="btn btn-sm btn-success">
                                                <i class="fas fa-users me-1"></i>View Shortlist & Choose Staff
                                            </a>

                                        @elseif($sr)
                                            {{-- No accepted staff yet --}}
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i>Still matching staff — check back soon
                                            </span>

                                        @endif
                                    @endif

                                    {{-- View booking button for status updates --}}
                                    @if(isset($notification->data['service_request_id']) &&
                                        ($notification->data['type'] ?? '') === 'service_request_status_update')
                                        <a href="{{ route('bookings') }}"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-clipboard-list me-1"></i>View My Bookings
                                        </a>
                                    @endif

                                    {{-- Button for staff_selection_confirmed --}}
                                    @if(isset($notification->data['service_request_id']) &&
                                        ($notification->data['type'] ?? '') === 'staff_selection_confirmed')
                                        @php
                                            $srId = $notification->data['service_request_id'];
                                            $sr = \App\Models\ServiceRequest::find($srId);
                                            $sl = $sr ? \App\Models\StaffShortlist::where('service_request_id', $srId)
                                                ->where('staff_id', $notification->data['staff_id'])
                                                ->first() : null;
                                        @endphp
                                        @if($sr && $sl)
                                            @if($notification->data['is_client_managed'] ?? false)
                                                <a href="{{ route('booking.staff-profile', [$srId, $sl->id]) }}"
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-user me-1"></i>View Staff Details
                                                </a>
                                            @elseif($sr->payment_status !== 'Paid')
                                                <a href="{{ route('booking.payment', $srId) }}"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fas fa-credit-card me-1"></i>Proceed to Payment
                                                </a>
                                            @else
                                                <a href="{{ route('booking.staff-profile', [$srId, $sl->id]) }}"
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-user me-1"></i>View Staff Details
                                                </a>
                                            @endif
                                        @endif
                                    @endif

                                </div>

                            </div>

                            <div class="text-end ms-3">
                                <small class="text-muted d-block">{{ $notification->created_at->diffForHumans() }}</small>
                                @if(!$notification->read_at)
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="mt-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-check"></i> Read
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