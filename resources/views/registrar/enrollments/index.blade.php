@extends('layouts.registrar')
@section('title', 'Enrollment Queue')
@section('content')

    <h4 class="fw-bold mb-4">Enrollment Queue</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @php
        $currentStatus = request('status');
        // Batch approve only on the Pending tab
        $showBatch = $currentStatus === 'pending';
    @endphp

    {{-- Status filter tabs --}}
    <ul class="nav nav-tabs mb-3">
        @php $tabs = ['' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected']; @endphp
        @foreach ($tabs as $value => $label)
            <li class="nav-item">
                <a href="{{ route('registrar.showEnrollments', array_filter(['status' => $value, 'strand' => request('strand'), 'grade' => request('grade'), 'section' => request('section')])) }}"
                   class="nav-link {{ ($currentStatus === $value || (!$currentStatus && $value === '')) ? 'active' : '' }}">
                    {{ $label }}
                </a>
            </li>
        @endforeach
    </ul>

    {{-- Strand / grade filters --}}
    <form method="GET" action="{{ route('registrar.showEnrollments') }}" class="row g-2 mb-3">
        <input type="hidden" name="status" value="{{ $currentStatus }}">
        <div class="col-auto">
            <select name="strand" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All strands</option>
                @foreach ($strands as $strand)
                    <option value="{{ $strand->id }}" {{ request('strand') == $strand->id ? 'selected' : '' }}>{{ $strand->strand_code }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <select name="grade" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All grades</option>
                <option value="11" {{ request('grade') == '11' ? 'selected' : '' }}>Grade 11</option>
                <option value="12" {{ request('grade') == '12' ? 'selected' : '' }}>Grade 12</option>
            </select>
        </div>
        <div class="col-auto">
            <select name="section" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All sections</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}" {{ request('section') == $section->id ? 'selected' : '' }}>
                        {{ $section->strand->strand_code ?? '' }} - {{ $section->section_name }} (G{{ $section->grade_level }})
                    </option>
                @endforeach
            </select>
        </div>
        @if (request()->hasAny(['strand', 'grade', 'section']))
            <div class="col-auto">
                <a href="{{ route('registrar.showEnrollments', array_filter(['status' => $currentStatus])) }}" class="btn btn-sm btn-outline-secondary">Clear</a>
            </div>
        @endif
    </form>

    <form method="POST" action="{{ route('registrar.batchApproveEnrollments') }}">
        @csrf
        <div class="card shadow-sm">
            @if ($enrollments->isEmpty())
                <div class="card-body text-center text-muted">No enrollments found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                @if ($showBatch)
                                    <th style="width: 1%;"><input type="checkbox" onclick="document.querySelectorAll('.row-check').forEach(c => c.checked = this.checked)"></th>
                                @endif
                                <th>Student</th>
                                <th>Section</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enrollments as $enrollment)
                                <tr>
                                    @if ($showBatch)
                                        <td>
                                            @if ($enrollment->status === 'pending')
                                                <input type="checkbox" class="row-check" name="enrollment_ids[]" value="{{ $enrollment->id }}">
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        <a href="{{ route('registrar.showEnrollment', $enrollment->id) }}">
                                            {{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}
                                        </a>
                                    </td>
                                    <td class="text-muted">
                                        {{ $enrollment->section->strand->strand_code ?? '' }} - {{ $enrollment->section->section_name }}
                                        <span class="badge text-bg-light">G{{ $enrollment->section->grade_level }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($enrollment->status) {
                                                'approved' => 'text-bg-success',
                                                'rejected' => 'text-bg-danger',
                                                default    => 'text-bg-warning',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($enrollment->status) }}</span>
                                    </td>
                                    <td class="text-muted">{{ $enrollment->submitted_at?->format('M d, Y') }}</td>
                                    <td class="text-end">
                                        @if ($enrollment->status === 'pending')
                                            <button type="submit"
                                                    formaction="{{ route('registrar.approveEnrollment', $enrollment->id) }}"
                                                    class="btn btn-sm btn-success">Approve</button>
                                            <a href="{{ route('registrar.showEnrollment', $enrollment->id) }}" class="btn btn-sm btn-outline-danger">Reject</a>
                                        @else
                                            <a href="{{ route('registrar.showEnrollment', $enrollment->id) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if ($enrollments->isNotEmpty())
            <div class="d-flex justify-content-between align-items-center mt-3">
                @if ($showBatch)
                    <button type="submit" class="btn btn-success btn-sm"
                            onclick="return confirm('Approve all selected enrollments? This cannot be undone.')">
                        <i class="bi bi-check2-all me-1"></i> Batch Approve Selected
                    </button>
                @else
                    <span></span>
                @endif
                <div>{{ $enrollments->links() }}</div>
            </div>
        @endif
    </form>

@endsection
