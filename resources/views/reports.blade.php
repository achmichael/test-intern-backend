@extends('layout.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Generate Periodic Report</h2>
    <form action="{{ route('reports.generate') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="period_type">Period Type:</label>
            <select id="period_type" name="period_type" class="form-control" required>
                <option value="weekly" {{ old('period_type') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="monthly" {{ old('period_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
            </select>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ old('start_date', now()->toDateString()) }}" required>
        </div>
        <div class="alert-success">
            <strong>Note:</strong> Ketika anda sudah melakukan generate report, maka ketika anda ingin melakukan download excel, anda harus melakukan set tanggal dan tipe periode lagi, karena ketika anda menjalankan satu aksi, maka otomatis data tanggal dan tipe periode akan disetel ulang ke nilai default.
        </div>
        <button type="submit" class="btn btn-primary mt-3">Generate Report</button>
    </form>
    <form id="excel_form" action="{{ route('reports.export.excel') }}" method="POST" class="mt-3">
        @csrf
        <input type="hidden" id="period_type_excel" name="period_type" value="{{ old('period_type') }}">
        <input type="hidden" id="start_date_excel" name="start_date" value="{{ old('start_date', now()->toDateString()) }}">
        <button type="submit" class="btn btn-success">Download Excel</button>
    </form>
    @isset($approvals)
    <div class="mt-5">
        <h3>Report Results</h3>
        <p>Period Type: {{ ucfirst($periodType) }}</p>
        <p>Start Date: {{ $startDate->toDateString() }}</p>
        <p>End Date: {{ $endDate->toDateString() }}</p>

        @if($approvals->isEmpty())
            <p>No report found</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Approval ID</th>
                        <th>Approved By</th>
                        <th>Role</th>
                        <th>Approval Status</th>
                        <th>Approved At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($approvals as $approval)
                    <tr>
                        <td>{{ $approval['id'] }}</td>
                        <td>{{ $approval['approval_id'] }}</td>
                        <td>{{ $approval['approved_by'] }}</td>
                        <td>{{ $approval['role'] }}</td>
                        <td>{{ $approval['approval_status'] }}</td>
                        <td>{{ $approval['approved_at'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    @endisset
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const formGenerate = document.querySelector('form[action="{{ route('reports.generate') }}"]');
        const formDownloadExcel = document.getElementById('excel_form');

        formGenerate.addEventListener('submit', function () {
            document.getElementById('period_type_excel').value = document.getElementById('period_type').value;
            document.getElementById('start_date_excel').value = document.getElementById('start_date').value;
        });

        formDownloadExcel.addEventListener('submit', function () {
            document.getElementById('period_type_excel').value = document.getElementById('period_type').value;
            document.getElementById('start_date_excel').value = document.getElementById('start_date').value;
        });
    });
</script>
@endsection
