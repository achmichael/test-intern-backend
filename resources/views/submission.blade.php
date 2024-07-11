@extends('layout.app')
@section('navbar')
<style>
    .spinner-border {
        width: 1.5rem;
        height: 1.5rem;
        border-width: 0.2em;
    }
    #userIcon{
        cursor: pointer;
    }

    .icon-back{
        color: black;
    }
    .icon-back:hover{
        color: white;
    }
    .sidebar {
        position: fixed;
        top: 0;
        right: -250px;
        width: 250px;
        height: 100%;
        transition: all 0.3s ease;
    }

    .sidebar.active {
        right: 0;
    }

    .sidebar .btn-danger {
        width: 100%;
        margin-top: 20px;
    }

    @media (max-width: 992px) {
        .sidebar {
            width: 200px; /* Ubah lebar sidebar untuk tablet */
         }
    }

    @media (max-width: 768px) {
        html, th, td, span{
            font-size: 55%;
        }
        #userIcon{
            width: 20px;
            height: 20px;
        }
        .sidebar {
        width: 150px; /* Ubah lebar sidebar untuk tampilan mobile */
        }
    }

    @media (max-width: 400px) {
       html, th, td, span{
        font-size: 45%;
       }
       #userIcon{
        width: 15px;
        height: 15px;
       }
    }


</style>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary p-4">
    <div class="container-fluid d-flex justify-content-between align-items-center p-2">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('dashboard')}}"><i data-feather="arrow-left" class="icon-back fw-semibold"></i></a>
            <span class="fs-5 ms-2 me-2 fw-bold text-white">ApprovalGear</span>
        </div>
        <div class="icon ms-2 me-2 d-flex align-items-center">
            <img class="rounded-circle color-white" src="{{ asset('img/user.png') }}" id="userIcon" alt="kosong" width="25px" height="25px">
            <p class="fs-6 fw-semibold mb-0 ms-2"> {{ isset($username) ? 'Haii ' . $username . ' 🟢' : ''}}</p>
        </div>
    </div>
</nav>

@endsection

@section('content')
<div class="row my-5" id="register">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-center">Form Pengajuan Pembeian Alat Berat</h5>
                    <form id="submission-form">
                        @csrf
                        <div class="mb-3">
                            <label for="equipment_type" class="form-label">Jenis Alat Berat</label>
                            <input type="text" class="form-control" id="equipment_type" name="equipment_type" placeholder="Masukkan Jenis Alat Berat">
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Masukkan jumlah" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Alasan Pembelian</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Masukkan alasan pembelian" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" name="btn" disabled>Ajukan</button>
                    </form>
            </div>
        </div>
    </div>
</div>

<div id="sidebar" class="sidebar bg-primary">
    <div class="d-flex flex-column align-items-center p-4">
        <img id="sidebarUserIcon" class="rounded-circle mb-3" src="{{ asset('img/user.png') }}" alt="kosong" width="50px" height="50px">
        <p class="text-white mb-0"> {{ isset($username) ? 'Haii ' . $username . ' 🟢' : ''}}</p>
        <button id="sidebarLogout" class="btn btn-danger">Logout</button>
    </div>
</div>
<script>
    feather.replace();
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('submission-form');
        const btnAjukan = document.querySelector('[name="btn"]');
        const userIcon = document.getElementById('userIcon');
        const sidebar = document.getElementById('sidebar');
        const sidebarLogout = document.getElementById('sidebarLogout');

        function checkFormValidity() {
            const equipmentType = document.getElementById('equipment_type').value.trim();
            const quantity = document.getElementById('quantity').value.trim();
            const reason = document.getElementById('reason').value.trim();

            if (equipmentType !== '' && quantity !== '' && reason !== '') {
                btnAjukan.removeAttribute('disabled');
            } else {
                btnAjukan.setAttribute('disabled', 'disabled');
            }
        }

        form.addEventListener('input', function () {
            checkFormValidity();
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            btnAjukan.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memuat...';
            btnAjukan.setAttribute('disabled', 'disabled');

            const token = localStorage.getItem('token');

            const _token = document.querySelector('input[name="_token"]').value;
            if(!token || !_token) {
                console.log('Token not found');
                return;
            }

            const formData = new FormData(form);

            const data = {
                equipment_type : document.getElementById('equipment_type').value.trim(),
                quantity : document.getElementById('quantity').value.trim(),
                reason : document.getElementById('reason').value.trim(),
            }

            fetch("{{ route('submission')}}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': _token,
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                console.log(response);
                return response.json();
            })
            .then(result => {
                if (result.status) {
                    Swal.fire({
                        title: 'Success',
                        text: result.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: true
                    })
                    .then(() => {
                            btnAjukan.innerHTML = 'Sukses';
                            btnAjukan.setAttribute('disabled', 'disabled');
                            localStorage.setItem('submissionSuccess', true);
                            setTimeout(() => {
                                window.location.href = "{{ route('dashboard') }}";
                            }, 1000);
                        });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: result.message,
                        icon: 'error'
                    })
                    .then(() => {
                        btnAjukan.setAttribute('disabled', 'disabled');
                        btnAjukan.innerHTML = 'Gagal';
                    });
                }
            })
            .catch(error => {
                btnAjukan.innerHTML = 'Ajukan';
                btnAjukan.removeAttribute('disabled');
                Swal.fire({
                    title: 'Error',
                    text: error.stack,
                    icon: 'error'
                })
                .then(() => {
                    btnAjukan.setAttribute('disabled', 'disabled');
                    btnAjukan.innerHTML = 'Gagal';
                });
                console.error('Error:', error);
            })
        });

        userIcon.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });

        document.addEventListener('click', function(e) {
            if (!sidebar.contains(e.target) && !userIcon.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });

        sidebarLogout.addEventListener('click', function() {
            Swal.fire({
                title: 'Logout',
                text: "Anda yakin ingin keluar?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Keluar'
            }).then((response) => {
                if(response.isConfirmed){
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
