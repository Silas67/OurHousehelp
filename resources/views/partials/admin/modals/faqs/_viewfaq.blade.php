<div class="modal fade" id="viewfaq-{{ $faq->id }}" tabindex="-1" aria-labelledby="viewfaqLabel-{{ $faq->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">View FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <p>{{ ucfirst($faq->category) }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Question</label>
                    <p>{{ $faq->question }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Answer</label>
                    <p>{{ $faq->answer }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
