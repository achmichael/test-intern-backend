@extends('layout.app')
<body>    
@section('navbar')
<div class="d-flex align-items-center p-4 bg-primary">
    <div class="icon-back rounded d-flex align-items-center justify-content-center ms-1 p-3" style="height: 1em; width: 5em;">
        <a href="{{ route('home') }}" class="fw-semibold text-white mb-0 text-decoration-none">Back</a>
    </div>
</div>
@endsection
@section('content')
<div class="row my-5" id="login">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-center">Login</h5>
                <form id="login-form">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <div class="invalid-feedback">
                            Username tidak boleh kosong.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="invalid-feedback">
                            Password tidak boleh kosong.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option selected disabled>Pilih Role</option>
                            <option value="karyawan">Karyawan</option>
                            <option value="admin">Admin</option>
                            <option value="direktur">Direktur</option>
                        </select>
                        <div class="invalid-feedback">
                            Pilih peran untuk login.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <div class="text-center mt-3">
                    <a id="forgot-password" class="text-decoration-none text-danger" href="/forgot-password">Forgot Password?</a>
                </div>
                <div class="text-center mt-3">
                    <p class="text-decoration-none text-black">Do you already have an account? <a href="/auth/register" class="text-danger text-decoration-none cursor-pointer"> Register </a></p>
                </div>
            </div>
        </div>
    </div>
</div>

@section('footer')
<x-footer></x-footer>
@endsection
<script>
    feather.replace();
</script>
<script>

document.getElementById('forgot-password').addEventListener('click', (e) => {
    e.preventDefault();
    Swal.fire({
        title: 'Reset Password',
        html:
            '<input type="text" id="swal-username" class="swal2-input" placeholder="Username">' +
            '<input type="password" id="swal-password" class="swal2-input" placeholder="New Password">',
        showCancelButton: true,
        confirmButtonText: 'Submit',
        preConfirm: () => {
            const username = Swal.getPopup().querySelector('#swal-username').value;
            const password = Swal.getPopup().querySelector('#swal-password').value;
            if (!username || !password) {
                Swal.showValidationMessage(`Please enter username and password`);
            }
            return { username: username, password: password };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { username, password } = result.value;
            fetch('{{ route('forgot-password') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ username: username, password: password })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Username tidak ditemukan atau gagal mereset password');
                }
                return response.json();
            })
            .then(datas => {
                if(datas.status){
                    Swal.fire({
                        title: 'Berhasil!',
                        text: datas.data.message,
                        icon: 'success'
                    });
                }else{
                    Swal.fire({
                        title: 'Gagal!',
                        text: datas.message,
                        icon: 'error'
                    });
                }                
            })
            .catch(error => {
                Swal.fire({
                    title: 'Gagal',
                    text: `Request gagal: ${error.message}`,
                    icon: 'error'
                });
            });
        }
    });
});


    document.getElementById('login-form').addEventListener('submit', (e) => {
        e.preventDefault();

        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        const role = document.getElementById('role').value.trim();

        // Validasi input username
        if (username === '') {
            Swal.fire({
                title: 'Failed',
                text: 'Username tidak boleh kosong.',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
            return;
        }

        // Validasi input password
        if (password === '') {
            Swal.fire({
                title: 'Failed',
                text: 'Password tidak boleh kosong.',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
            return;
        }

        // Validasi input role
        if (role === '') {
            Swal.fire({
                title: 'Failed',
                text: 'Role harus dipilih.',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
            return;
        }

        if(!validatePassword(password)){
            Swal.fire({
                title: 'Failed',
                text: 'Password harus terdiri dari 8 karakter, mengandung minimal satu huruf besar, huruf kecil, angka, dan karakter spesial.',
                icon: 'error',
                confirmButtonText: 'Okay'
            })
            return;
        }

        const _token = document.querySelector('input[name="_token"]').value;
        const data = {
            username: username,
            password: password,
            role: role
        };

        fetch("{{ route('login')}}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': _token
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                return response.json()
                .then(error => {
                    throw new Error(error.message);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.kode) {
                return Swal.fire({
                    title: 'Failed',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'Okay'
                });
            }
            if (data.status === false) {
                return Swal.fire({
                    title: 'Failed',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'Okay'
                });
            } else {
                const jwtToken = data.data.token;
                localStorage.setItem('token', jwtToken);
                return Swal.fire({
                    title: 'Success',
                    text: data.data.message,
                    icon: 'success',
                    confirmButtonText: 'Okay'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log(data.data.redirect_url);
                        window.location.href = data.data.redirect_url;
                    }
                });
            }
        })
        .catch((error) => {
            console.log('error lur');
            return Swal.fire({
                title: 'Error',
                text: error.message,
                icon: 'error',
                confirmButtonText: 'Okay'
            });
        });
    });

    function validatePassword(password) {
        const regex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/; 
        return regex.test(password);
    }
</script>
</body>
@endsection
