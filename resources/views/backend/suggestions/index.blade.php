@extends('layouts.admin')

@section('content')
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.3);
        }

        .split-pane-outer {
            background: #f1f5f9;
            border-radius: 20px;
            padding: 1.5rem;
        }

        .split-pane-container {
            display: flex;
            min-height: calc(100vh - 220px);
            gap: 1.5rem;
        }

        .pane-left {
            width: 32%;
            min-width: 380px;
            display: flex;
            flex-direction: column;
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            padding: 1.25rem;
            position: sticky;
            top: 90px;
            height: calc(100vh - 140px);
            align-self: flex-start;
        }

        .pane-right {
            width: 68%;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .status-tabs {
            display: flex;
            background: #e2e8f0;
            padding: 4px;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        .status-tab {
            flex: 1;
            text-align: center;
            padding: 6px 4px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            background: transparent;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-tab.active {
            background: white;
            color: var(--bs-primary);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .suggestion-list-scroll {
            flex-grow: 1;
            overflow-y: auto;
            padding-right: 8px;
            margin-top: 5px;
        }

        .suggestion-list-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .suggestion-list-scroll::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
        }

        .suggestion-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #94a3b8 !important;
            overflow: hidden;
        }

        .suggestion-card:hover {
            transform: translateX(4px);
            border-color: var(--bs-primary) !important;
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.05);
        }

        .suggestion-card.active {
            background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
            border-color: var(--bs-primary) !important;
            box-shadow: 0 8px 15px -3px rgba(92, 107, 192, 0.1);
        }

        .pane-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .search-box {
            position: relative;
            margin-bottom: 1rem;
        }

        .search-box input {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 10px 15px 10px 40px;
            border-radius: 12px;
            font-size: 0.85rem;
            width: 100%;
            transition: all 0.2s;
        }

        .search-box input:focus {
            background: white;
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 3px rgba(92, 107, 192, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
    </style>

    <div class="row mb-4 align-items-end">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-1">
                <div class="bg-primary text-white rounded-3 p-2 me-3 shadow-primary">
                    <i class="ti ti-inbox fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0">Suggestion Hub</h3>
                    <p class="text-muted smaller mb-0 text-uppercase fw-bold opacity-75" style="letter-spacing: 1px;">
                        Moderation Command Center
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="badge bg-soft-primary text-primary p-2 px-3 border rounded-pill">
                <i class="ti ti-activity me-1"></i>
                <span class="fw-bold">Active Stream</span>
            </div>
        </div>
    </div>

    <div class="split-pane-outer shadow-sm">
        <div class="split-pane-container">
            <div class="pane-left">
                <div class="pane-header">
                    <h6 class="fw-bold mb-0 text-dark">Live Queue</h6>
                    <div class="text-muted smaller fw-bold" id="queueCount">Loading...</div>
                </div>

                <div class="status-tabs">
                    <button class="status-tab active" data-status="Pending">Pending</button>
                    <button class="status-tab" data-status="Approved">Approved</button>
                    <button class="status-tab" data-status="Rejected">Rejected</button>
                    <button class="status-tab" data-status="Flagged">Flagged</button>
                </div>

                <div class="search-box">
                    <i class="ti ti-search"></i>
                    <input type="text" id="suggestionSearch" placeholder="Search by type or user...">
                </div>

                <div class="suggestion-list-scroll" id="suggestionListContainer">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                    </div>
                </div>
            </div>

            <div class="pane-right" id="suggestionDetailContainer">
                <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 18px;">
                    <div
                        class="empty-state d-flex flex-column align-items-center justify-content-center h-100 p-5 text-center">
                        <div class="icon-shape bg-soft-primary text-primary rounded-circle mb-4 shadow-sm"
                            style="width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle, rgba(92, 107, 192, 0.1) 0%, rgba(92, 107, 192, 0.05) 100%);">
                            <i class="ti ti-layout-dashboard fs-1 opacity-50"></i>
                        </div>
                        <h4 class="fw-bold text-dark">Moderation Command Center</h4>
                        <p class="text-muted max-width-300 mx-auto">
                            Select a suggestion from the live queue on the left to begin reviewing community contributions.
                        </p>
                        <div class="mt-2">
                            <span class="badge bg-light text-muted border">
                                <i class="ti ti-mouse me-1"></i> CLICK TO PREVIEW
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="reviewModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold" id="reviewModalTitle">Review Action</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="reviewSuggestionId">
                        <input type="hidden" id="reviewActionType">

                        <p class="text-muted small mb-3">
                            You are about to <span id="reviewActionLabel" class="fw-bold">...</span>
                            this suggestion. You may provide an optional reason below.
                        </p>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">Review Note (Optional)</label>
                            <textarea class="form-control shadow-none" id="reviewNote" rows="3" placeholder="e.g. This route does not exist."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary px-4" id="btnConfirmReview">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            let currentSuggestionId = null;

            $(document).ready(function() {
                loadList();

                $('.status-tab').on('click', function() {
                    $('.status-tab').removeClass('active');
                    $(this).addClass('active');

                    loadList();
                    resetDetailPane();
                });

                let searchTimer;
                $('#suggestionSearch').on('input', function() {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        loadList();
                    }, 500);
                });

                $(document).on('click', '.suggestion-pagination a', function(e) {
                    e.preventDefault();
                    const pageUrl = $(this).attr('href');
                    loadList(pageUrl);
                });

                $('#btnConfirmReview').on('click', function() {
                    const id = $('#reviewSuggestionId').val();
                    const action = $('#reviewActionType').val();
                    const note = $('#reviewNote').val();

                    submitReview(id, action, note);
                });
            });

            function loadList(url = null) {
                const activeStatus = $('.status-tab.active').data('status');
                const searchQuery = $('#suggestionSearch').val();

                const fetchUrl = url || `{{ route('backend.suggestions.list') }}?status=${activeStatus}&search=${searchQuery}`;

                $('#suggestionListContainer').html(
                    `<div class="text-center py-5"> <div class="spinner-border text-primary spinner-border-sm" role="status"></div></div>`
                );

                fetch(fetchUrl)
                    .then(res => res.text())
                    .then(html => {
                        $('#suggestionListContainer').html(html);

                        const count = $('.suggestion-card').length;
                        $('#queueCount').text(`${count} items found`);

                        if (currentSuggestionId) {
                            $(`.suggestion-card[data-id="${currentSuggestionId}"]`).addClass('active');
                        }
                    })
                    .catch(err => toastr.error("Failed to load suggestions."));
            }

            function loadSuggestionDetails(id) {
                currentSuggestionId = id;

                $('.suggestion-card').removeClass('active');
                $(`.suggestion-card[data-id="${id}"]`).addClass('active');

                $('#suggestionDetailContainer').html(
                    `<div class="card border-0 shadow-sm h-100 d-flex align-items-center justify-content-center bg-white"><div class="spinner-border text-primary" role="status"></div></div>`
                );

                fetch(`{{ url('backend/suggestions') }}/${id}`)
                    .then(res => {
                        if (!res.ok) throw new Error("Not found");
                        return res.text();
                    })
                    .then(html => {
                        $('#suggestionDetailContainer').html(html);
                    })
                    .catch(err => toastr.error("Failed to load details."));
            }

            function resetDetailPane() {
                currentSuggestionId = null;
                $('#suggestionDetailContainer').html(`
                    <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 18px;">
                        <div class="empty-state d-flex flex-column align-items-center justify-content-center h-100 p-5 text-center">
                            <div class="icon-shape bg-soft-primary text-primary rounded-circle mb-4 shadow-sm" style="width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle, rgba(92, 107, 192, 0.1) 0%, rgba(92, 107, 192, 0.05) 100%);">
                                <i class="ti ti-layout-dashboard fs-1 opacity-50"></i>
                            </div>
                            <h4 class="fw-bold text-dark">Moderation Command Center</h4>
                            <p class="text-muted max-width-300 mx-auto">
                                Select a suggestion from the live queue on the left to begin reviewing community contributions.
                            </p>
                            <div class="mt-2">
                                <span class="badge bg-light text-muted border">
                                    <i class="ti ti-mouse me-1"></i> CLICK TO PREVIEW
                                </span>
                            </div>
                        </div>
                    </div>
                `);
            }

            function openReviewModal(id, action) {
                $('#reviewSuggestionId').val(id);
                $('#reviewActionType').val(action);
                $('#reviewNote').val('');

                let colorClass = 'text-warning';
                if (action === 'Rejected') colorClass = 'text-danger';
                if (action === 'Approved') colorClass = 'text-success';

                $('#reviewActionLabel').html(`<span class="${colorClass}">${action}</span>`);

                const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
                modal.show();
            }

            function submitReview(id, action, note = null) {
                showLoader();

                fetch(`{{ url('backend/suggestions') }}/${id}/review`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            status: action,
                            review_note: note
                        })
                    })
                    .then(res => res.json().then(data => ({
                        status: res.status,
                        body: data
                    })))
                    .then(res => {
                        if (res.status === 200) {
                            toastr.success(res.body.message);
                            $('#reviewModal').modal('hide');
                            resetDetailPane();
                            loadList();
                        } else {
                            toastr.error(res.body.message || "An error occurred.");
                        }
                    })
                    .catch(err => toastr.error("Network error."))
                    .finally(() => hideLoader());
            }
        </script>
    @endpush
