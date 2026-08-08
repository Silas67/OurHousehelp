@extends('layouts.applicantmain')

@section('specialDuties')
<div class="container mt-5">
    <h2 class="mb-4">Special Duties</h2>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Position</th>
                <th>Assigned By</th>
                <th>Assigned At</th>
                <th>Status</th>
                <th>Client</th> <!-- New Column for Client -->
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($assignments as $assignment)
            @include('partials.admin.modals._ordermodalClient')

                <tr>
                    <td>
                        {{ $assignment->house_help_id == auth()->id() ? 'House Help' : 'Assistant' }}
                    </td>
                    <td>{{ $assignment->admin->name }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($assignment->assigned_at)->format('Y-m-d') }}
                    </td>
                    <td>{{ ucfirst($assignment->status) }}</td>
                    <td>
                        {{-- {{ $assignment->serviceRequest->client->name ?? 'No client assigned' }} --}}
                        {{ $assignment->serviceRequest->name }} {{ $assignment->serviceRequest->last_name }}
                    </td>
                    <td>
                        <button type="button" id="PopoverCustomT-{{ $assignment->id }}"
                            data-bs-toggle="modal" data-bs-target="#detail{{ $assignment->id }}"
                            class="btn btn-primary btn-sm">Details</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No special duties assigned yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
