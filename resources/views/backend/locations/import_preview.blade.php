@extends('layouts.backend')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 text-primary fw-bold">
                        <i class="ti ti-file-import me-2"></i>Import {{ $type }} Preview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info shadow-sm border-0">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-info-circle fs-4 me-2"></i>
                            <div>
                                <strong>Previewing Data:</strong> Found <strong>{{ count($valid) }}</strong> valid records
                                and <strong>{{ count($invalid) }}</strong> invalid records.
                            </div>
                        </div>
                    </div>

                    @if (count($invalid) > 0)
                        <div class="mb-4">
                            <h6 class="text-danger fw-bold mb-3"><i class="ti ti-alert-triangle me-1"></i>Invalid Records
                                (Action Required)</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="bg-light">
                                        <tr>
                                            @foreach ($headers as $header)
                                                <th>{{ ucfirst($header) }}</th>
                                            @endforeach
                                            <th class="text-danger">Errors</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($invalid as $item)
                                            <tr>
                                                @foreach ($headers as $header)
                                                    <td>{{ $item[$header] ?? '' }}</td>
                                                @endforeach
                                                <td class="text-danger small">
                                                    <ul class="mb-0 ps-3">
                                                        @foreach ($item['errors'] as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h6 class="text-success fw-bold mb-3"><i class="ti ti-check me-1"></i>Valid Records (Ready to
                            Import)</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-muted">
                                <thead class="bg-light">
                                    <tr>
                                        @foreach ($headers as $header)
                                            <th>{{ ucfirst($header) }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($valid as $item)
                                        <tr>
                                            @foreach ($headers as $header)
                                                <td>{{ $item[$header] ?? '' }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    @if (count($valid) == 0)
                                        <tr>
                                            <td colspan="{{ count($headers) }}" class="text-center">No valid records found
                                                in the uploaded file.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ url()->previous() }}" class="btn btn-light"><i
                                class="ti ti-arrow-left me-1"></i>Back</a>

                        @if (count($valid) > 0)
                            <form action="{{ route('backend.' . strtolower(Str::plural($type)) . '.import.commit') }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="data" value="{{ json_encode($valid) }}">
                                <button type="submit" class="btn btn-primary px-4"><i
                                        class="ti ti-cloud-upload me-1"></i>Import {{ count($valid) }} Records</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
