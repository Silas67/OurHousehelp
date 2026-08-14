{{--
    Flash messages.

    The layouts previously rendered only session('success'), so 72
    ->with('error'), 24 ->with('info') and 3 ->with('warning') calls across the
    controllers produced nothing at all - the user saw a silent redirect and no
    explanation of what went wrong.
--}}

@foreach ([
    'success' => ['alert-success', 'fa-circle-check'],
    'error'   => ['alert-danger',  'fa-circle-exclamation'],
    'warning' => ['alert-warning', 'fa-triangle-exclamation'],
    'info'    => ['alert-info',    'fa-circle-info'],
] as $key => [$class, $icon])
    @if (session($key))
        <div class="alert {{ $class }} alert-dismissible fade show d-flex align-items-start gap-2" role="alert">
            <i class="fas {{ $icon }} mt-1" aria-hidden="true"></i>
            <div class="flex-grow-1">{!! e(session($key)) !!}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endforeach

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
