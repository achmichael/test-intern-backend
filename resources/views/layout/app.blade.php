<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $title }}</title>
    @include('layout.header')
    
</head>
<body>

    @yield('navbar')
    @yield('content')
    @yield('footer')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
