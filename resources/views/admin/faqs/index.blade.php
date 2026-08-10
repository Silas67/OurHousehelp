{{-- @extends('layouts.adminmain')

@section('faq')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-question-circle"></i> FAQ Management
            </h1>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createFaqModal">
                <i class="fas fa-plus"></i> Add FAQ
            </button>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="faqsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Category</th>
                                <th>Question</th>
                                <th>Answer</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($faqs as $faq)
                                <tr>
                                    <td>{{ ucfirst($faq->category) }}</td>
                                    <td>{{ Str::limit($faq->question, 50) }}</td>
                                    <td>{{ Str::limit($faq->answer, 70) }}</td>
                                    <td>                                        
                                        <button class="btn btn-sm btn-info view-faq"  data-bs-toggle="modal"
                                                data-bs-target="#viewfaq-{{ $faq->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-primary edit-faq"  data-bs-toggle="modal"
                                                data-bs-target="#editfaq-{{ $faq->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-faq"  data-bs-toggle="modal"
                                                data-bs-target="#deletefaq-{{ $faq->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No FAQs found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    @include('partials.admin.modals.faqs._createfaq')
    @include('partials.admin.modals.faqs._viewfaq')
    @include('partials.admin.modals.faqs._editfaq')
    @include('partials.admin.modals.faqs._deletefaq')

@endsection --}}


@extends('layouts.adminmain')

@section('faq')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-question-circle"></i> FAQ Management
            </h1>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createFaqModal">
                <i class="fas fa-plus"></i> Add FAQ
            </button>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="faqsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Category</th>
                                <th>Question</th>
                                <th>Answer</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($faqs as $faq)
                                <tr>
                                    <td>{{ ucfirst($faq->category) }}</td>
                                    <td>{{ Str::limit($faq->question, 50) }}</td>
                                    <td>{{ Str::limit($faq->answer, 70) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                data-bs-target="#viewfaq-{{ $faq->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#editfaq-{{ $faq->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deletefaq-{{ $faq->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No FAQs found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('partials.admin.modals.faqs._createfaq')
    @foreach($faqs as $faq)
        @include('partials.admin.modals.faqs._viewfaq', ['faq' => $faq])
        @include('partials.admin.modals.faqs._editfaq', ['faq' => $faq])
        @include('partials.admin.modals.faqs._deletefaq', ['faq' => $faq])
    @endforeach
@endsection
