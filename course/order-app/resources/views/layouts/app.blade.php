<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order App — @yield('title', 'Orders')</title>

    {{-- LESSON: CSRF token in meta tag — Axios reads this and sends it with every request --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap 5 CSS from CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- LESSON: @yield('styles') is a placeholder.
         Child views can inject their own CSS using @section('styles') --}}
    @yield('styles')
</head>
<body class="bg-light">

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('orders.index') }}">📦 Order App</a>
            <div>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-light btn-sm">All Orders</a>
                <a href="{{ route('orders.create') }}" class="btn btn-success btn-sm ms-2">+ New Order</a>
            </div>
        </div>
    </nav>

    {{-- Main content area --}}
    <div class="container">

        {{-- LESSON: Session flash messages — shown once after a redirect.
             We set these in the controller with: return redirect()->with('success', '...') --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- LESSON: @yield('content') is where each page's content gets injected --}}
        @yield('content')
    </div>

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Axios — HTTP client for making API calls from the browser --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    {{-- LESSON: We set the CSRF token as a default header for all Axios requests.
         Laravel requires this token on every POST/PUT/DELETE request to prevent
         Cross-Site Request Forgery attacks. --}}
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
    </script>

    {{-- LESSON: @yield('scripts') lets child views add their own JS at the bottom --}}
    @yield('scripts')
</body>
</html>
