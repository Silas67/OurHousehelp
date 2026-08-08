@extends('layouts.adminmain')
@section('paymentplan')

    <div class="container pb-3 py-5">
        <h1>Create New Payment Plan</h1>
    </div>

    <section class="container py-5 bg-white">
        <form action="{{ route('admin.payment_plans.paymentplanstore') }}" method="POST" class="form-style" id="paymentPlanForm">
            @csrf

            <h2 class="form-title">Create Payment Plan</h2>

            <fieldset class="form_fieldset">
                <legend><span class="number">1</span> Payment Plan Details</legend>

                <div class="form-group">
                    <label class="form_label" for="plan_type">Plan Type</label>
                    <select class="form-control" id="plan_type" name="plan_type" required>
                        <option value="">Select Plan Type</option>
                        <option value="househelp">Househelp Plan</option>
                        <option value="skilled">Skilled Workers Plan</option>                        
                    </select>
                    @error('plan_type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form_label" for="plan_name">Plan Name</label>
                    <input type="text" class="form-control" id="plan_name" name="plan_name" required>
                    @error('plan_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form_label" for="cost">Cost (₦)</label>
                    <input type="number" step="0.01" class="form-control" id="cost" name="cost" required>
                    @error('cost')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form_label" for="duration">Duration</label>
                    <select class="form-control" id="duration" name="duration" required>
                        <option value="">Select Duration</option>
                        <option value="1">Daily Plan</option>
                        <option value="7">One Week Plan</option>
                        <option value="30">One Month Plan</option>
                        <option value="90">Three Months Plan</option>
                        <option value="180">Six Months Plan</option>
                        <option value="365">One Year Plan</option>
                    </select>
                    @error('duration')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>    

                <div class="form-group">
                    <label class="form_label" for="num_searches">Number of Searches</label>
                    <input type="number" class="form-control" id="num_searches" name="num_searches" required>
                    @error('num_searches')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

            </fieldset>
            <br>

            <fieldset class="form_fieldset">
                <legend class="form_legend"><span class="number">2</span> Additional Information</legend>

                <div class="form-group">
                    <label class="form_label" for="about">Description</label>
                    <textarea class="form-control" id="about" name="about" rows="3"></textarea>
                    @error('about')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </fieldset>
            <br>

            <button type="submit" class="btn btn-primary form_button">Create Payment Plan</button>
        </form>
    </section>

@endsection
