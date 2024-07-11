@extends('layout.app')
@section('navbar')
@section('content')
<!-- Register Form -->
<div class="row my-5" id="register">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-center">Register</h5>
                <form id="register-form">
                    @csrf
                    <div class="mb-3">
                        <label for="reg_username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="reg_username" name="reg_username" value="{{ old('reg_username') }}" required>
                        @if($errors->has('reg_username'))
                            <div class="invalid-feedback">
                                {{ $errors->first('reg_username') }}
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="reg_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="reg_password" name="reg_password" required>
                        @if($errors->has('reg_password'))
                            <div class="invalid-feedback">
                                {{ $errors->first('reg_password') }}
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="reg_confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="reg_confirm_password" name="reg_password_confirmation" required>
                        @if($errors->has('reg_password_confirmation'))
                            <div class="invalid-feedback">
                                {{ $errors->first('reg_password_confirmation') }}
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="reg_role" class="form-label">Role</label>
                        <select class="form-select" id="reg_role" name="reg_role" required>
                            <option value="" disabled {{ old('reg_role') ? '' : 'selected' }}>Pilih Role</option>
                            <option value="karyawan" {{ old('reg_role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                            <option value="admin" {{ old('reg_role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="direktur" {{ old('reg_role') == 'direktur' ? 'selected' : '' }}>Direktur</option>
                        </select>
                        @if($errors->has('reg_role'))
                            <div class="invalid-feedback">
                                {{ $errors->first('reg_role') }}
                            </div>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-secondary w-100" name="btn">Register</button>
                </form>
                <div class="text-center mt-3">
                    <a class="text-decoration-none text-danger" href="/forgot-password">Forgot Password?</a>
                </div>
                <div class="text-center mt-3">
                    <p class="text-decoration-none text-black">Do you already have an account? <a href="/auth/login" class="text-decoration-none text-danger">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>    
    document.getElementById('register-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const username = document.getElementById('reg_username').value.trim();
        const password = document.getElementById('reg_password').value.trim();
        const confirmPassword = document.getElementById('reg_confirm_password').value.trim();
        const role = document.getElementById('reg_role').value.trim();

        if (username === '') {
            Swal.fire({
                title: 'Failed',
                text: 'Username tidak boleh kosong.',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
            return;
        }

        if (password === '') {
            Swal.fire({
                title: 'Failed',
                text: 'Password tidak boleh kosong.',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
            return;
        }

        if (confirmPassword === '') {
            Swal.fire({
                title: 'Failed',
                text: 'Konfirmasi Password tidak boleh kosong.',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
            return;
        }

        if (!validatePassword(password)) {
            Swal.fire({
                title: 'Failed',
                text: 'Password harus terdiri dari 8 karakter, mengandung minimal satu huruf besar, huruf kecil, angka, dan karakter spesial.',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
            return;
        }

        if (password !== confirmPassword) {
            Swal.fire({
                title: 'Failed',
                text: 'Password dan Konfirmasi Password harus sama.',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
            return;
        }

        if (role === '') {
            Swal.fire({
                title: 'Failed',
                text: 'Role harus dipilih.',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
            return;
        }

        const _token = document.querySelector('input[name="_token"]').value;
        console.log(_token);            
        const data = {
            username: username,
            password: password,
            repassword: confirmPassword,
            role: role
        };

        fetch("{{ route('register')}}", { 
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': _token
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(error => {
                    throw new Error(error.message);
                });
            }
            const result = response.json(); 
            console.log(result);
            return result;
        })
        .then(data => {
            Swal.fire({
                title: 'Success',
                text: data.message + ', Silahkan Login',
                icon:'success',
                confirmButtonText: 'Login',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-secondary',
                confirmButtonText: 'Login',
                reverseButtons: true
            }).then(response => {
                if (response.isConfirmed) {
                    window.location.href = "/auth/login";
                }
            })
        })
        .catch((error) => {
           console.log(error)
        });
    });

    function validatePassword(password) {
        const regex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/;
        const isValid = regex.test(password);
        console.log("Password Valid:", isValid); // Debugging log
        return isValid;
    }
</script>
@endsection
