@extends('layouts.adminmain')

@section('allskills')
    <div class="container">
        <div class="row justify-content-between align-items-center mb-4">
            <div class="col-md-6">
                <h2>All Skills</h2>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.skill.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Skill
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Skill Name</th>
                                <th>Description</th>
                                <th>Icon</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($skills as $skill)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $skill->skill_name }}</td>
                                    <td>{{ Str::limit($skill->about_skill, 50) }}</td>
                                    <td>
                                        @if ($skill->icon_class)
                                            <i class="{{ $skill->icon_class }}"></i>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Edit Button with Modal Trigger -->
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#editSkillModal-{{ $skill->id }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>

                                        <!-- Delete Button with Modal Trigger -->
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteSkillModal-{{ $skill->id }}">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>

                                <!-- Edit Skill Modal -->
                                <div class="modal fade" id="editSkillModal-{{ $skill->id }}" tabindex="-1"
                                    aria-labelledby="editSkillModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editSkillModalLabel">Edit Skill</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.skill.update', $skill->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="skill_name" class="form-label fw-bold">Skill
                                                            Name</label>
                                                        <input type="text" class="form-control" id="skill_name"
                                                            name="skill_name" value="{{ $skill->skill_name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="about_skill"
                                                            class="form-label fw-bold">Description</label>
                                                        <textarea class="form-control" id="about_skill" name="about_skill" rows="3" required>{{ $skill->about_skill }}</textarea>
                                                    </div>

                                                    <!-- Icon Class -->
                                                    <div class="form-group mb-3">
                                                        <label for="icon_class" class="fw-bold">Icon <small>(Select an icon
                                                                to represent the skill)</small></label>
                                                        <select name="icon_class" id="icon_class" class="form-control">
                                                            <option value=""
                                                                {{ $skill->icon_class == '' ? 'selected' : '' }}>No Icon
                                                            </option>
                                                            <option value="fas fa-hammer"
                                                                {{ $skill->icon_class == 'fas fa-hammer' ? 'selected' : '' }}>
                                                                Hammer (Construction)</option>
                                                            <option value="fas fa-paint-brush"
                                                                {{ $skill->icon_class == 'fas fa-paint-brush' ? 'selected' : '' }}>
                                                                Paint Brush (Painting)</option>
                                                            <option value="fas fa-broom"
                                                                {{ $skill->icon_class == 'fas fa-broom' ? 'selected' : '' }}>
                                                                Broom (Cleaning)</option>
                                                            <option value="fas fa-plug"
                                                                {{ $skill->icon_class == 'fas fa-plug' ? 'selected' : '' }}>
                                                                Plug (Electrical)</option>
                                                            <option value="fas fa-faucet"
                                                                {{ $skill->icon_class == 'fas fa-faucet' ? 'selected' : '' }}>
                                                                Faucet (Plumbing)</option>
                                                            <option value="fas fa-car"
                                                                {{ $skill->icon_class == 'fas fa-car' ? 'selected' : '' }}>
                                                                Car (Driving)</option>
                                                            <option value="fas fa-seedling"
                                                                {{ $skill->icon_class == 'fas fa-seedling' ? 'selected' : '' }}>
                                                                Gardening</option>
                                                            <option value="fas fa-laptop"
                                                                {{ $skill->icon_class == 'fas fa-laptop' ? 'selected' : '' }}>
                                                                Computer Repair</option>
                                                            <option value="fas fa-cogs"
                                                                {{ $skill->icon_class == 'fas fa-cogs' ? 'selected' : '' }}>
                                                                Mechanical</option>
                                                            <option value="fas fa-tshirt"
                                                                {{ $skill->icon_class == 'fas fa-tshirt' ? 'selected' : '' }}>
                                                                Tailoring</option>
                                                        </select>
                                                        @error('icon_class')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Skill Modal -->
                                <div class="modal fade" id="deleteSkillModal-{{ $skill->id }}" tabindex="-1"
                                    aria-labelledby="deleteSkillModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteSkillModalLabel">Confirm Deletion</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete the skill "{{ $skill->skill_name }}"?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.skill.delete', $skill->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $skills->links() }}
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS (if not already included in your layout) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
