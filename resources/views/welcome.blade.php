@extends('layout.app')
    <style>
        .running-text {
            animation: marquee 30s linear infinite;
            white-space: nowrap;
            overflow: hidden;
            width: 100%;
        }
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .container-form {
            box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .navbar-custom {
            background-color: #343a40; /* Dark color for navbar */
        }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link {
            color: #f8f9fa; /* Light color for navbar text */
        }
        .jumbotron-custom {
            background-color: white; /* Muted color for jumbotron */
            color: black; /* White color for jumbotron text */
            box-shadow: 0px 5px 5px rgba(0, 0, 0, 0.5);
        }
        .feature-box {
            background-color: #f8f9fa; /* Light background for feature boxes */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .contact-form {
            background-color: #ffffff; /* White background for contact form */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #343a40; /* Primary color for buttons */
            color: #ffffff; /* White color for button text */
        }
        footer {
            background-color: #343a40; /* Dark color for footer */
            color: #f8f9fa; /* Light color for footer text */
        }
    </style>
<body>
    @section('navbar')
    <!-- Navigation Bar -->
    <nav class="navbar sticky-top navbar-expand-lg navbar-custom p-4">
    <div class="container-fluid">
        <a class="navbar-brand fs-5 ms-4 font-weight-bold" href="#">Aplikasi Alat Berat</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link ms-3" aria-current="page" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ms-3" href="/auth/login">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ms-3 me-3" href="auth/register">Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

@section('content')
<!-- Running Text -->
<div class="col-md-12 my-3">
    <div class="running-text text-center py-3">
        <p class="text-dark">Selamat datang di Aplikasi Pembelian Alat Berat. Gunakan fitur kami untuk mengoptimalkan proses pembelian alat berat Anda!</p>
    </div>
</div>

<!-- Jumbotron -->
<div class="container jumbotron-custom text-center py-5 rounded">
    <div class="container">
        <h2 class="display-6">Selamat Datang di Aplikasi Pembelian Alat Berat</h2>
        <p class="lead">Mengelola pengajuan dan persetujuan pembelian alat berat dengan lebih efisien dan efektif.</p>
        <hr class="my-4">
        <p>Gunakan aplikasi ini untuk mengajukan permintaan pembelian dan melacak status persetujuan.</p>
        <a class="btn btn-custom btn-lg" href="#" role="button">Mulai Sekarang</a>
    </div>
</div>

<!-- Features Section -->
<div class="container">
    <h2 class="text-center my-4">Fitur Utama Kami</h2>
    <div class="row">
        <div class="col-md-4 feature-box">
            <h3>Pengelolaan Pembelian</h3>
            <p>Mengelola seluruh proses pembelian alat berat dengan mudah dan cepat.</p>
        </div>
        <div class="col-md-4 feature-box">
            <h3>Pelacakan Status</h3>
            <p>Melacak status persetujuan pembelian Anda secara real-time.</p>
        </div>
        <div class="col-md-4 feature-box">
            <h3>Analisis Data</h3>
            <p>Menyediakan laporan dan analisis data untuk pengambilan keputusan yang lebih baik.</p>
        </div>
    </div>
</div>
<!-- Contact Form Section -->
<div class="container-form container my-5 py-4 shadow-lg rounded">
    <h2 class="text-center my-4">Kontak Kami</h2>
    <form>
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control" id="name" placeholder="Nama Anda">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Email Anda">
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Pesan</label>
            <textarea class="form-control" id="message" rows="3" placeholder="Pesan Anda"></textarea>
        </div>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-custom">Kirim</button>
        </div>
    </form>
</div>
@section('footer')
<x-footer></x-footer>
</body>
@endsection
