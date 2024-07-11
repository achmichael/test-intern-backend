<?php
$title = "Approval Admin";
?>

@extends('layout.app')
@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Pending Purchase Requests for Admin</h2>

    @if (!empty($requests) && count($requests))
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Karyawan ID</th>
                        <th>Equipment Type</th>
                        <th>Quantity</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                        <tr>
                            <td>{{ $request['id'] }}</td>
                            <td>{{ $request['karyawan_id'] }}</td>
                            <td>{{ $request['jenis_alat_berat'] }}</td>
                            <td>{{ $request['jumlah'] }}</td>
                            <td>{{ $request['alasan'] }}</td>
                            <td>{{ $request['status'] }}</td>
                            <td>
                                <form id="approval-form-admin-{{ $request['id'] }}" class="approval-form" action="{{ route('admin.approvals') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="purchase_request_id" value="{{ $request['id'] }}">
                                    <button id="approveAdminBtn-{{ $request['id'] }}" type="submit" class="btn btn-success btn-sm">Approve</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">
            No pending requests found.
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const forms = document.querySelectorAll('.approval-form');

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const token = localStorage.getItem('token');
            const _token = document.querySelector('input[name="_token"]').value;
            const id = form.querySelector('input[name="purchase_request_id"]').value;

            if (!token || !_token || !id) {
                console.log('Token, ID, atau Role tidak ditemukan');
                return;
            }

            const data = {
                purchase_request_id: id,
            };

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': _token,
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(result => {
                console.log(result);
                if (result.status) {
                    Swal.fire({
                        title: 'Success',
                        text: result.data.message,
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })
                    .then(() => {
                        const approveAdminBtn = document.getElementById(`approveAdminBtn-${id}`);
                        if (approveAdminBtn) {
                            approveAdminBtn.textContent = 'Approved';
                            approveAdminBtn.classList.remove('btn-success');
                            approveAdminBtn.classList.add('btn-secondary');
                        }
                        location.reload();
                    })
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: result.message || 'Unknown error occurred',
                        icon: 'error',
                        confirmButtonText: 'Okay'
                    });
                }
            })
            .catch(error => {
                console.log(error.message);
                Swal.fire({
                    title: 'Error',
                    text: 'Terjadi kesalahan jaringan',
                    icon: 'error',
                    confirmButtonText: 'Okay'
                });
            });
        });
    });
});
</script>
@endsection
