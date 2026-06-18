<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Purple Admin</title>

        {{-- Style Global --}}
        <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

        {{-- Style Custom Admin --}}
        <link rel="stylesheet" href="{{ asset('assets/css/custom-admin.css') }}">

        {{-- Style Page --}}
        @yield('style_page')

    </head>

    <body>

        <div class="container-scroller">
            @include('layouts.partials.navbar')
            <div class="container-fluid page-body-wrapper">
                @include('layouts.partials.sidebar')
                <div class="main-panel">
                    <div class="content-wrapper">
                        {{-- Content --}}
                        @yield('content')
                    </div>
                    @include('layouts.partials.footer')
                </div>
            </div>
        </div>

        {{-- JS Global --}}
        <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
        <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
        <script src="{{ asset('assets/js/misc.js') }}"></script>
        <script>
            if(window.innerWidth < 992){

                document.querySelectorAll('.nav-link').forEach(function(item){

                    item.addEventListener('click', function(){

                        const sidebar =
                            document.querySelector('.sidebar-offcanvas');

                        if(sidebar){
                            sidebar.classList.remove('active');
                        }
                    });
                });
            }
        </script>

        @yield('js_global')

        {{-- JS Page --}}
        @yield('js_page')

    </body>
</html>