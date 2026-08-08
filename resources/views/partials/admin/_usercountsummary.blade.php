<div class="container py-4">
    <div class="row text-center">
        @unless (Route::is('adminstaffs') ||
                Route::is('adminclients') ||
                Route::is('adminskilledworkers') ||
                Route::is('adminapplicants'))
            <!-- Show all cards by default -->
            <div class="col-md-4">
                <a href="{{ route('adminclients') }}">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">Total Clients</h5>
                            <p class="card-text fs-4">{{ $totalClients }}</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('adminapplicants') }}">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Total Applicants</h5>
                            <p class="card-text fs-4">{{ $totalApplicants }}</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('adminskilledworkers') }}">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title">Total Skilled Workers</h5>
                            <p class="card-text fs-4">{{ $totalskilledworkers }}</p>
                        </div>
                    </div>
                </a>
            </div>
            @if (Auth::user()->user_role == 2)
                <div class="col-md-4">
                    <a href="{{ route('adminstaffs') }}">
                        <div class="card text-white bg-danger">
                            <div class="card-body">
                                <h5 class="card-title">Total Staffs</h5>
                                <p class="card-text fs-4">{{ $totalAdmin }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        @else
            <!-- Show only the relevant card based on current route -->
            @if (Route::is('adminclients'))
                <div class="col-md-12">
                    <a href="{{ route('adminclients') }}">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <h5 class="card-title">Total Clients</h5>
                                <p class="card-text fs-4">{{ $totalClients }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @elseif(Route::is('adminapplicants'))
                <div class="col-md-3">
                    <a href="{{ route('adminapplicants') }}">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h5 class="card-title">Total Applicants</h5>
                                <p class="card-text fs-4">{{ $totalApplicants }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('adminapplicants') }}">
                        <div class="card text-white bg-danger">
                            <div class="card-body">
                                <h5 class="card-title">Live Out</h5>
                                <p class="card-text fs-4">{{ $totalLiveOut }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('adminapplicants') }}">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <h5 class="card-title">Live In</h5>
                                <p class="card-text fs-4">{{ $totalLiveIn }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('adminapplicants') }}">
                        <div class="card text-white bg-dark">
                            <div class="card-body">
                                <h5 class="card-title">Live In & Out</h5>
                                <p class="card-text fs-4">{{ $totalBoth }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @elseif(Route::is('adminskilledworkers'))
                <div class="col-md-12">
                    <a href="{{ route('adminskilledworkers') }}">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <h5 class="card-title">Total Skilled Workers</h5>
                                <p class="card-text fs-4">{{ $totalskilledworkers }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @elseif(Route::is('adminstaffs') && Auth::user()->user_role == 2)
                <div class="col-md-12">
                    <a href="{{ route('adminstaffs') }}">
                        <div class="card text-white bg-danger">
                            <div class="card-body">
                                <h5 class="card-title">Total Staffs</h5>
                                <p class="card-text fs-4">{{ $totalAdmin }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
            <button class="btn btn-primary btn-sm col-md-12" data-bs-toggle="modal" data-bs-target="#registerNew">
    <h5>Register New
        @if (Route::is('adminstaffs'))
            Admin
        @elseif (Route::is('adminclients'))
            Client
        @elseif (Route::is('adminskilledworkers'))
            Skilled Worker
        @elseif (Route::is('adminapplicants'))
            House Help
        @endif
    </h5>
</button>
        @endunless
    </div>
</div>

@include('partials.admin.modals._registerNewUsers')
