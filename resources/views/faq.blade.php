@extends('layouts.main')

@section('FAQs')
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold">Frequently Asked Questions</h1>
            <p class="lead">Find answers to common questions about our platform</p>
        </div>

        <!-- Search Box -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search FAQs..." id="faqSearch">
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-search text-white"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- FAQ Categories -->
        <div class="row">
            <div class="col-lg-10 mx-auto">
                @foreach ($categories as $categoryName => $faqs)
                    @if ($faqs->count() > 0)
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-success text-white">
                                <h3 class="mb-0">
                                    @switch($categoryName)
                                        @case('general')
                                            <i class="fas fa-globe me-2 text-white"></i> General Questions
                                        @break

                                        @case('clients')
                                            <i class="fas fa-user-tie me-2 text-white"></i> For Clients
                                        @break

                                        @case('workers')
                                            <i class="fas fa-hard-hat me-2 text-white"></i> For Workers
                                        @break

                                        @case('platform')
                                            <i class="fas fa-cogs me-2 text-white"></i> Platform Help
                                        @break

                                        @case('skilled')
                                            <i class="fas fa-cogs me-2 text-white"></i> Skilled Workers
                                        @break
                                    @endswitch
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="accordion{{ ucfirst($categoryName) }}">
                                    @foreach ($faqs as $index => $faq)
                                        <div class="accordion-item mb-2 border-0">
                                            <h3 class="accordion-header" id="heading{{ $categoryName }}{{ $index }}">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse{{ $categoryName }}{{ $index }}"
                                                    aria-expanded="false">
                                                    {{ $faq->question }}
                                                </button>
                                            </h3>
                                            <div id="collapse{{ $categoryName }}{{ $index }}"
                                                class="accordion-collapse collapse"
                                                aria-labelledby="heading{{ $categoryName }}{{ $index }}"
                                                data-bs-parent="#accordion{{ ucfirst($categoryName) }}">
                                                <div class="accordion-body">
                                                    {{ $faq->answer }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Search Functionality -->
    <script>
        document.getElementById('faqSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll('.accordion-button').forEach(button => {
                const question = button.textContent.toLowerCase();
                const answer = button.closest('.accordion-item').querySelector('.accordion-body')
                    .textContent.toLowerCase();
                const item = button.closest('.accordion-item');

                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
@endsection
