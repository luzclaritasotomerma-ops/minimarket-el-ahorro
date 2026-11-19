
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title') - Minimarket El Ahorro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f1f5f9;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background: #17324d;
        }

        .main-container {
            max-width: 1200px;
            margin: 35px auto;
        }

        .content-card {
            background: white;
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,.06);
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            EL AHORRO | Sistema de Ventas
        </a>

        <div class="d-flex align-items-center gap-3 text-white">
            <span>{{ auth()->user()->name }}</span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-light btn-sm">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container main-container">

    @yield('content')

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
