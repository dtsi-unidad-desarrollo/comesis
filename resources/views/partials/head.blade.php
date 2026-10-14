<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">

<title>@yield('title')</title>

{{-- SEO --}}
<meta content="" name="description">
<meta content="" name="keywords">

<!-- Favicons & App Icons -->
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/icons/favicon-16x16.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/icons/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="48x48" href="{{ asset('assets/img/icons/favicon-48x48.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/icons/apple-touch-icon.png') }}">

<!-- Web App / PWA & Desktop Installation -->
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#2563eb">
<meta name="application-name" content="COMESIS">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="COMESIS">
<meta name="msapplication-TileColor" content="#2563eb">
<meta name="msapplication-TileImage" content="{{ asset('assets/img/icons/icon-192x192.png') }}">

<!-- Google Fonts -->
{{-- <link href="https://fonts.gstatic.com" rel="preconnect"> --}}
{{-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet"> --}}
<link href="{{ asset('assets/css/fuente.css') }}">



<!-- Vendor CSS Files -->
<link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
{{-- <link href="{{ asset('') }}assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"> --}}
<link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">

{{-- Quill Editor --}}
<link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
{{-- Cierre Quill Editor --}}

<link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
{{-- <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet"> --}}

<!-- Template Main CSS File -->
<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
{{-- <link href="{{ asset('assets/css/personalizado.css') }}" rel="stylesheet"> --}}
<link href="{{ asset('/css/jquery-confirm.css') }}" rel="stylesheet">
<script src="{{ asset('js/jquery.min.js') }}" defer></script>
<script src="{{ asset('js/jquery-confirm.js') }}" defer></script>
<script src="{{ asset('js/chart.js') }}" defer></script>

<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('{{ asset('sw.js') }}').catch(function(err) {
                console.log('SW registration error:', err);
            });
        });
    }
</script>




