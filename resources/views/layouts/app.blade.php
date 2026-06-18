<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        body {
            background: linear-gradient(135deg, rgb(168, 120, 199), #de80de);
            min-height: 100vh;
        }

        .auth .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .btn-gradient-primary {
            background: linear-gradient(90deg, #d4acdb, #9066aa);
            border: none;
        }

        .btn-gradient-primary:hover {
            opacity: 0.9;
        }
    </style>

</head>
<body>

<div class="container-scroller d-flex align-items-center auth">
    <div class="row w-100">
        <div class="col-lg-4 mx-auto">
            @yield('content')
        </div>
    </div>
</div>

<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>

</body>
</html>