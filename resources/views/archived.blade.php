@extends('layouts.adminmain')

@section('guarantorsachieve')
    <div class="container pb-3 py-5">
        <h1 class="text-center">{{ $user->name }}'s Guarantors Archive</h1>
    </div>

    <div class="container py-3">
        <div class="card">
            <div class="card-body">
                @if ($archivedGuarantors->isEmpty())
                    <p>No archived records found for this applicant.</p>
                @else
                    <!-- Set max height for the table container and add a scrollbar -->
                    <div style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-bordered table-responsive">
                            <thead style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1;">
                                <tr>
                                    <th>Guarantor Number</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Relationship</th>
                                    <th>Occupation</th>
                                    <th>Address</th>
                                    <th>Passport</th>
                                    <th>Signature</th>
                                    <th>Archived At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($archivedGuarantors as $guarantor)
                                    <tr>
                                        <td>{{ $guarantor->guarantor_number }}</td>
                                        <td>{{ $guarantor->first_name }}</td>
                                        <td>{{ $guarantor->last_name }}</td>
                                        <td>{{ $guarantor->email ?? 'N/A' }}</td>
                                        <td>{{ $guarantor->phone }}</td>
                                        <td>{{ $guarantor->relationship }}</td>
                                        <td>{{ $guarantor->occupation }}</td>
                                        <td>{{ $guarantor->address }}</td>
                                        <td>
                                            @if ($guarantor->passport_path)
                                                <img src="{{ asset('build/assets/image/passports/' . $guarantor->passport_path) }}"
                                                    alt="Passport" width="50">
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if ($guarantor->signature_path)
                                                <img src="{{ asset('build/assets/image/signatures/' . $guarantor->signature_path) }}"
                                                    alt="Signature" width="50">
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $guarantor->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>

                                @endforeach
                            </tbody>
                        </table>
                        <!-- Pagination links -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $archivedGuarantors->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
