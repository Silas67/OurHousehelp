<div class="modal fade" id="editfaq-{{ $faq->id }}" tabindex="-1" aria-labelledby="editfaqLabel-{{ $faq->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.faqs.update', $faq->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editCategory" class="form-label">Category</label>
                        <select name="category" id="editCategory" class="form-select" required>
                            <option value="general" {{ $faq->category == 'general' ? 'selected' : '' }}>General Questions</option>
                            <option value="clients" {{ $faq->category == 'clients' ? 'selected' : '' }}>For Clients</option>
                            <option value="workers" {{ $faq->category == 'workers' ? 'selected' : '' }}>For Workers</option>
                            <option value="platform" {{ $faq->category == 'platform' ? 'selected' : '' }}>Platform Help</option>
                            <option value="skilled" {{ $faq->category == 'skilled' ? 'selected' : '' }}>Skilled Workers</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editQuestion" class="form-label">Question</label>
                        <input type="text" name="question" id="editQuestion" class="form-control" value="{{ $faq->question }}" required minlength="10">
                    </div>
                    <div class="mb-3">
                        <label for="editAnswer" class="form-label">Answer</label>
                        <textarea name="answer" id="editAnswer" class="form-control" rows="5" required minlength="20">{{ $faq->answer }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>
