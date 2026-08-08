<div class="btn-group btn-group-sm" role="group">
    <!-- Approve/Unapprove Button -->
    @if ($user->approval_status !== 'approved')
        <form action="{{ route('admin.approve.user', $user->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-success" title="Approve">
                <i class="fas fa-check"></i>
            </button>
        </form>
    @else
        <form action="{{ route('admin.unapprove.user', $user->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-warning" title="Unapprove">
                <i class="fas fa-times"></i>
            </button>
        </form>
    @endif

    <!-- Archive Button -->
    <button type="button" class="btn btn-secondary" title="Archive" data-bs-toggle="modal"
        data-bs-target="#archiveModal{{ $user->id }}">
        <i class="fas fa-archive"></i>
    </button>

    <!-- Archive Confirmation Modal -->
    <div class="modal fade" id="archiveModal{{ $user->id }}" tabindex="-1" aria-labelledby="archiveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="archiveModalLabel">Confirm Archive</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to archive <b>{{ $user->name }} {{ $user->last_name }}</b>? <br> <b
                        class="text-danger">You cannot undo this action!</b>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('admin.archieve.user', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger">Archive User</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Button -->
    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#users{{ $user->id }}" title="View">
        <i class="fas fa-eye"></i>
    </button>

    <!-- Delete Button (Hidden by default) -->
    <form action="{{ route('users.delete', $user->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" title="Delete"
            onclick="return confirm('Are you sure you want to delete this user?')" style="display: none;">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>
