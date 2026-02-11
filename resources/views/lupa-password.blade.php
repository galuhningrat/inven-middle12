<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password - Siakad STTI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('assets/media/logo/favicon.ico') }}" />
    <!-- Metronic Core CSS (ganti path sesuai struktur proyek Anda) -->
    <link href="{{asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
</head>
<body id="kt_body" class="bg-body">

<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Authentication - Forgot Password -->
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <!--begin::Body-->
        <div class="d-flex flex-column flex-lg-row-auto w-xl-600px bg-light p-10">
            <!--begin::Content-->
            <div class="d-flex flex-center flex-column flex-column-fluid">
                <a href="/" class="mb-12">
                    <img alt="Logo" src="{{ asset('assets/media/logo/logo-login.png') }}" class="h-60px" />
                </a>
                <h1 class="fw-bolder text-dark mb-7">Lupa Password</h1>
                <div class="text-gray-600 fs-4 text-center mb-7">
                    Silakan hubungi <a href="https://api.whatsapp.com/send?phone=6282130961754&text=Hai%20Admin%2C%20Saya%20lupa%20Username%20%2F%20Password%20Siakad%20" class="fw-bold text-primary">Admin STTI</a> untuk bantuan reset password Anda.
                </div>
                <a href="/login" class="btn btn-primary">Kembali ke Login</a>
            </div>
            <!--end::Content-->
        </div>
        <!--end::Body-->

        <!-- Optional image or right panel -->
        <div class="d-flex flex-column flex-lg-row-fluid bgi-size-cover bgi-position-center" style="background-image: url('{{asset('assets/media/img/gedung.jpg')}}')">
        </div>
    </div>
    <!--end::Authentication-->
</div>
<!--end::Main-->

<!-- Metronic JS -->
<script src="{{asset('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{asset('assets/js/scripts.bundle.js')}}"></script>
</body>
</html>
