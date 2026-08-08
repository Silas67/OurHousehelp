@extends('layouts.adminmain')

@section('extraServices')
    <div class="container pb-3 py-5">
        <h1 class="text-center">Manage Extra Service Cost</h1>

        @if (session('success'))
            <div class="alert alert-success text-center mt-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger text-center mt-3">
                {{ $errors->first() }}
            </div>
        @endif
    </div>

    <div class="container py-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    @if($extraService->id)
                        Edit Extra Service Cost
                    @else
                        Create Extra Service Cost
                    @endif
                </h5>

                <form method="POST" action="{{ route('admin.extra-service-cost.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="cost" class="form-label">Extra Service Cost (₦)</label>
                        <input type="number" step="0.01" name="cost" id="cost"
                            value="{{ old('cost', $extraService->cost ?? 0) }}"
                            class="form-control @error('cost') is-invalid @enderror" required>
                        @error('cost')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description (optional)</label>
                        <textarea name="description" id="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description', $extraService->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success px-4">
                        {{ $extraService->id ? 'Update' : 'Create' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection