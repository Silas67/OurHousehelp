@extends('layouts.adminmain')

@section('applicants')
    <div class="container-fluid py-4">
        <!-- Success/Error Messages -->
        @include('partials.admin._alerts')

        <!-- User Count Summary -->
        @include('partials.admin._usercountsummary')

        
        <!-- Live-Out Applicants Section -->
        <div class="card mb-4" style="border-left: 4px solid #285ba7;">
            <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h5 class="mb-2 mb-md-0">Live-Out Applicants</h5>
                <div class="w-75 w-md-50 mt-2 mt-md-0">
                    <form method="GET" action="">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" name="liveout_search"
                                placeholder="Search by name, email, phone..." value="{{ request('liveout_search') }}">
                            @if (request('liveout_search'))
                                <a href="{{ url()->current() }}" class="input-group-text text-danger" title="Clear search">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @include('partials.admin.table._liveout_applicants', ['applicants' => $approvedusers])
            </div>
        </div>

        <!-- Live-In Applicants Section -->
        <div class="card" style="border-left: 4px solid #28a745;">
            <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h5 class="mb-2 mb-md-0">Live-In Applicants</h5>
                <div class="w-75 w-md-50 mt-2 mt-md-0">
                    <form method="GET" action="">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" name="livein_search"
                                placeholder="Search by name, email, phone..." value="{{ request('livein_search') }}">
                            @if (request('livein_search'))
                                <a href="{{ url()->current() }}" class="input-group-text text-danger" title="Clear search">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @include('partials.admin.table._livein_applicants', ['applicants' => $users])
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Debounce function to limit API calls
            function debounce(func, wait) {
                let timeout;
                return function() {
                    const context = this,
                        args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        func.apply(context, args);
                    }, wait);
                };
            }

            // Function to handle search
            function handleSearch(searchType, searchTerm) {
                if (searchTerm.length === 0) {
                    // Reload the page to show all results if search is empty
                    window.location.reload();
                    return;
                }

                fetch(`/admin/applicants?search=${encodeURIComponent(searchTerm)}`)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        if (searchType === 'liveout') {
                            const newTable = doc.querySelector('#liveout-applicants-table');
                            if (newTable) {
                                document.querySelector('#liveout-applicants-table').innerHTML = newTable
                                    .innerHTML;
                            }
                        } else {
                            const newTable = doc.querySelector('#livein-applicants-table');
                            if (newTable) {
                                document.querySelector('#livein-applicants-table').innerHTML = newTable
                                    .innerHTML;
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }

            // Live-Out search
            const liveoutSearch = document.getElementById('liveout-search');
            if (liveoutSearch) {
                liveoutSearch.addEventListener('keyup', debounce(function() {
                    handleSearch('liveout', this.value);
                }, 300));
            }

            // Live-In search
            const liveinSearch = document.getElementById('livein-search');
            if (liveinSearch) {
                liveinSearch.addEventListener('keyup', debounce(function() {
                    handleSearch('livein', this.value);
                }, 300));
            }
        });
    </script>
@endsection
