<section class="container py-5 bg-white">
    <h1>Edit or Delete Costs</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
<table class="table">
        <thead>
            <tr>
                <th>Package Name</th>
                <th>Service Name</th>
                <th>Cost</th>
                <th>Days per week</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($packages as $package)
                <tr>
                    <td>{{ ucfirst($package->name) }}</td>
                    @if ($package->serviceName == 1)
                        <td>Cleaning & Housekeeping</td>
                    @elseif ($package->serviceName == 2)
                        <td>Laundry</td>
                    @elseif ($package->serviceName == 3)
                        <td>Errand & Housekeeping</td>
                    @elseif ($package->serviceName == 4)
                        <td>Nanny & Housekeeping</td>
                    @elseif ($package->serviceName == 5)
                        <td>Cook & Errands</td>
                    @endif
                    <td>
                        <form action="{{ route('packages.update', $package->id) }}" method="POST">
                            @csrf
                            <div class="row g-2 align-items-center">
                                <div class="col-9">
                                    <input class="form-control" type="number" name="cost" value="{{ $package->cost }}"
                                        step="0.01" min="0" required>
                                </div>
                                <div class="col-3">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">Update</button>
                                </div>
                            </div>
                        </form>

                    </td>
                    <td>{{ ucfirst($package->days_per_week) }}</td>
                    <td>{{ ucfirst($package->description) }}</td>
                    <td>

                        <!-- Delete button -->
                        <form action="{{ route('packages.destroy', $package->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this package?');">
                            @csrf
                            @method('DELETE') <!-- Spoof the DELETE method -->
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</section>

<script>
    function confirmDeletion(id) {
        if (confirm("Are you sure you want to delete this package?")) {
            // Perform deletion logic here (e.g., make an AJAX call)
        }
    }
</script>
