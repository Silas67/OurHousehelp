@extends('layouts.adminmain')

@section('newskillservice')
    <div class="row justify-content-between align-items-center mb-4">
        <div class="col-md-6">
            <h2>Create New Skill</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.allskill') }}" class="btn btn-primary">
                <i class="fas fa-list"></i> All Skill
            </a>
        </div>
    </div>

    <section class="container py-5 bg-white">

        <form action="{{ route('admin.skills.store') }}" method="POST" class="form-style" id="skillForm">
            @csrf
        
            <h2 class="form-title">Create Skill</h2>
        
            <fieldset class="form_fieldset">
                <legend>Skill Details</legend>
        
                <!-- Skill Name -->
                <div class="form-group">
                    <label class="form_label fw-bold" for="skill_name">Skill Name</label>
                    <input type="text" class="form-control" id="skill_name" name="skill_name"
                        placeholder="The name of the skill" required>
                    @error('skill_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
        
                <!-- Description of Skill -->
                <div class="form-group">
                    <label class="form_label fw-bold mt-3" for="about_skill">Description</label>
                    <textarea class="form-control" id="about_skill" name="about_skill" rows="3"
                        placeholder="Description of the skill" required maxlength="150"
                        oninput="updateSkillCountdown()"></textarea>
                    <small id="skillCharacterCountdown" class="form-text text-muted">150 characters remaining</small>
                    @error('about_skill')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
        
                <script>
                    function updateSkillCountdown() {
                        const textarea = document.getElementById('about_skill');
                        const countdown = document.getElementById('skillCharacterCountdown');
                        const remaining = 150 - textarea.value.length;
                        countdown.textContent = `${remaining} characters remaining`;
                    }
                </script>
            </fieldset>
        
            <!-- Icon Class -->
            <div class="form-group">
                <label for="icon_class" class="fw-bold">Icon <small>(Select an icon to represent the skill)</small></label>
                <select name="icon_class" id="icon_class" class="form-control">
                    <option value="" selected>No Icon</option>
                    <option value="fas fa-hammer">Hammer (Construction)</option>
                    <option value="fas fa-paint-brush">Paint Brush (Painting)</option>
                    <option value="fas fa-broom">Broom (Cleaning)</option>
                    <option value="fas fa-plug">Plug (Electrical)</option>
                    <option value="fas fa-faucet">Faucet (Plumbing)</option>
                    <option value="fas fa-car">Car (Driving)</option>
                    <option value="fas fa-seedling">Gardening</option>
                    <option value="fas fa-laptop">Computer Repair</option>
                    <option value="fas fa-cogs">Mechanical</option>
                    <option value="fas fa-tshirt">Tailoring</option>
                </select>
                @error('icon_class')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary form_button">Create Skill</button>
        </form>
    </section>

@endsection
