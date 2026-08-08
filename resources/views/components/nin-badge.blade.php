@props(['user'])

@if($user->nin_verified)
    <span class="badge bg-success d-inline-flex align-items-center">
        <i class="bi bi-patch-check-fill me-1"></i>
        NIN Verified
    </span>
@else
    <span class="badge bg-warning text-dark d-inline-flex align-items-center">
        <i class="bi bi-exclamation-circle-fill me-1"></i>
        NIN Not Verified
    </span>
@endif
