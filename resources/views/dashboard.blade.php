@extends('layout.app')
<style>
    body {
        font-family: Arial, sans-serif;
    }

    .sidebar {
        height: 100vh;
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #343a40;
        padding-top: 20px;
        z-index: 1000;
    }

    .sidebar a {
        padding: 15px 20px;
        text-decoration: none;
        font-size: 18px;
        color: #ddd;
        display: block;
    }

    .sidebar a:hover {
        background-color: #fff;
        color: black;
    }

    .sidebar .nav-item i {
        margin-right: 10px;
    }

    .content {
        padding: 20px;
        margin-left: 250px;
    }

    .card {
        border-radius: 10px;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
    }

    .card-text {
        font-size: 1rem;
        color: #555;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }

    @media (max-width: 991px) {
        html {
            font-size: 75%;
        }

        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
            padding-top: 70px;
        }

        .content {
            margin-left: 0;
        }

        .sidebar a {
            display: inline-block;
            padding: 10px 15px;
        }

        .navbar-brand {
            padding: 0;
        }
    }

    @media (max-width: 768px) {
        html, th, td, span {
            font-size: 55%;
        }
    }

    @media (max-width: 400px) {
        html, th, td, span {
            font-size: 45%;
        }
    }
</style>
@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-primary p-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <span class="fs-5 ms-2">ApprovalGear</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/submission"><i class="bi bi-file-earmark-plus"></i> Pengajuan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('approvals') }}"><i class="bi bi-check-square"></i> Persetujuan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/approval/direktur"><i class="bi bi-check-square"></i> Persetujuan Direktur</a>
                </li>
                <li class="nav-item">
                    <a id="report" class="nav-link" href="#"><i class="bi bi-person-circle"></i> Laporan</a>
                </li>
                <li class="nav-item">
                    <a id="logout" href="{{ route('logout') }}" class="nav-link">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4 px-4">
    <div class="row">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Pengajuan Baru</h5>
                    <p class="card-text">Buat pengajuan pembelian alat berat baru.</p>
                    <a href="/submission" class="btn btn-primary mt-auto">Ajukan</a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Laporan</h5>
                    <p class="card-text">Lihat laporan pengajuan yang telah diajukan.</p>
                    <a href="{{ route('reports') }}" class="btn btn-primary mt-auto">Lihat Laporan</a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Persetujuan Admin</h5>
                    <p class="card-text">Setujui atau tolak pengajuan yang masuk.</p>
                    <a href="{{ route('approvals') }}" class="btn btn-primary mt-auto">Lihat Persetujuan</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="mb-4">Ringkasan Aktivitas</h4>
            <div class="table-responsive">
                <table class="table table-bordered" id="activityTable">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>ID</th>
                            <th>Pengajuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                        <tr>
                            <td>{{ $request['id'] }}</td>
                            <td>{{ $request['jenis_alat_berat'] }}</td>
                            <td>
                                @if ($request['status'] === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif ($request['status'] === 'approved_by_admin')
                                    <span class="badge bg-primary text-white">Approved By Admin</span>
                                @elseif ($request['status'] === 'approved_by_direktur')
                                    <span class="badge bg-success text-white">Success</span>
                                @endif
                            </td>
                            <td><button class="btn btn-sm btn-primary detail-button" data-request='@json($request)'>Detail</button></td>
                        </tr>
                        @endforeach
                    </tbody>    
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <strong>ID Pengajuan:</strong>
                    <span id="modalPurchaseId"></span>
                </div>
                <div class="mb-2">
                    <strong>ID Karyawan:</strong>
                    <span id="karyawanId"></span>
                </div>
                <div class="mb-2">
                    <strong>Jenis Alat:</strong>
                    <span id="modalEquipmentType"></span>
                </div>
                <div class="mb-2">
                    <strong>Jumlah:</strong>
                    <span id="modalQuantity"></span>
                </div>
                <div class="mb-2">
                    <strong>Alasan:</strong>
                    <span id="modalReason"></span>
                </div>
                <div class="mb-2">
                    <strong>Status:</strong>
                    <span id="modalStatus"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const submission = localStorage.getItem('submissionSuccess');

        if(submission){
            localStorage.removeItem('submissionSuccess');

            location.reload();
        }
        // Datatables initialization
        $('#activityTable').DataTable({
            "pageLength": 10,
            "lengthChange": false
        });

        const detailButtons = document.querySelectorAll('.detail-button');

        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                const request = JSON.parse(this.getAttribute('data-request'));

                document.getElementById('modalPurchaseId').textContent = request.id;
                document.getElementById('karyawanId').textContent = request.karyawan_id;
                document.getElementById('modalEquipmentType').textContent = request.jenis_alat_berat;
                document.getElementById('modalStatus').textContent = request.status;
                document.getElementById('modalQuantity').textContent = request.jumlah;
                document.getElementById('modalReason').textContent = request.alasan;

                // Tampilkan modal
                const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
                detailModal.show();
            });
        });

        const logoutBtn = document.getElementById('logout');

        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Apakah Anda yakin ingin logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route("logout") }}')
                    .then(response => {
                        if(!response.ok){
                            throw new Error('Response is not confirmed');
                        }
                        return response.json();
                    })
                    .then(datas => {
                        Swal.fire({
                            title: 'Success',
                            text: datas.data.message,
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        })
                        .then(() => {
                            window.location.href = datas.data.redirect;
                        })
                    })
                    .catch(error => console.log(error))
                }
            });
        });
    });
</script>
@endsection
