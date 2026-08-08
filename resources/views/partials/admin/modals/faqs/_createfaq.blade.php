<div class="modal fade" id="createFaqModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Add New FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.faqs.store') }}" method="POST" id="createFaqForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-select" required>
                            <option value="" disabled selected>Select a category</option>
                            <option value="general">General Questions</option>
                            <option value="clients">For Clients</option>
                            <option value="workers">For Workers</option>
                            <option value="platform">Platform Help</option>
                            <option value="skilled">Skilled Workers</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="question" class="form-label">Question</label>
                        <input type="text" name="question" id="question" class="form-control" required minlength="10">
                    </div>
                    <div class="mb-3">
                        <label for="answer" class="form-label">Answer</label>
                        <textarea name="answer" id="answer" class="form-control" rows="5" required minlength="20"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>
