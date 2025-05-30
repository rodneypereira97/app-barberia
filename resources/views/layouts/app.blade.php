<!DOCTYPE html>
<html lang="es">
<head>


    <meta charset="UTF-8">
    <title>@yield('title', 'Barbería')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('') }}">

    <style>
    .fullscreen-mode .main-footer,
    .fullscreen-mode .nav-tabs,
    .fullscreen-mode .content-header {
        display: none !important;
    }

    .fullscreen-mode .content-wrapper {
        margin-top: 0 !important;
    }
    </style>



    @stack('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    @auth
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item d-flex justify-content-between align-items-center w-100">
                <!-- Izquierda: botón pantalla completa -->
                <div>
                    <button id="fullscreenBtn" class="btn btn-sm btn-outline-light ml-2" title="Pantalla completa">
                        <i class="fas fa-expand" id="fullscreenIcon"></i>
                    </button>
                </div>

                <!-- Derecha: nombre usuario + logout -->
                <form method="POST" action="{{ route('logout') }}" class="d-flex align-items-center gap-2">
                    @csrf

                    <span class="text-gray-800 dark:text-gray-200 font-medium"> 
                        {{ Auth::user()->name }} Barber
                    </span>
<h1 class="">   </h1>
                    <button type="submit" class="btn btn-danger btn-sm" title="Cerrar sesión">
                        <i class="fas fa-sign-out-alt"></i> 
                    </button>
                </form>
            </li>

        </ul>
    </nav>
    @endauth

    <!-- Sidebar -->
    @auth
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="#" class="brand-link">
            <span class="brand-text font-weight-light">Barbería</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">
                    <li class="nav-item">
                        <a href="{{ route('citas.calendario') }}" class="nav-link">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Inicio</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('clientes.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Clientes</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('servicios.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-cut"></i>
                            <p>Servicios</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('citas.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Citas</p>
                        </a>
                    </li>
                    <!-- Agrega más menús según necesites -->
                </ul>
            </nav>
        </div>
    </aside>
    @endauth

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content p-3">
            @yield('content')
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer text-center">
        <strong>&copy; {{ date('Y') }} Barbería App</strong> - Todos los derechos reservados.
    </footer>
</div>

<!-- AdminLTE JS -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
    document.getElementById('fullscreenBtn').addEventListener('click', function () {
        const body = document.body;
        const icon = document.getElementById('fullscreenIcon');

        const isFullscreen = body.classList.toggle('fullscreen-mode');

        // Cambiar ícono
        if (isFullscreen) {
            icon.classList.remove('fa-expand');
            icon.classList.add('fa-compress');

            if (!document.fullscreenElement) {
                body.requestFullscreen().catch(err => {
                    console.error(`Error al entrar en pantalla completa: ${err.message}`);
                });
            }
        } else {
            icon.classList.remove('fa-compress');
            icon.classList.add('fa-expand');

            if (document.fullscreenElement) {
                document.exitFullscreen();
            }
        }
    });
</script>

@vite(['resources/js/app.js'])
@stack('scripts')
</body>
</html>
