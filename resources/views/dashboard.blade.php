
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel - Minimarket El Ahorro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f1f5f9;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background: #17324d;
            color: white;
        }

        .sidebar h4 {
            color: #ffffff;
        }

        .menu-link {
            display: block;
            color: #dce6ef;
            text-decoration: none;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        .menu-link:hover {
            background: #284d70;
            color: white;
        }

        .panel-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,.06);
        }

        .topbar {
            background: white;
            border-radius: 12px;
            padding: 18px;
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- MENÚ LATERAL -->
        <aside class="col-md-3 col-lg-2 sidebar p-3">

            <h4 class="text-center mt-3 mb-4">
                EL AHORRO
            </h4>

            <hr>

            <a href="{{ route('dashboard') }}" class="menu-link">
                🏠 Panel principal
            </a>

            @if(auth()->user()->role->name === 'Administrador')

                <a href="{{ route('admin.panel') }}" class="menu-link">
                    👤 Administración
                </a>

            @endif

            <a href="{{ route('clientes.index') }}" class="menu-link">
                👥 Clientes
            </a>

            <a href="{{ route('ventas.index') }}" class="menu-link">
                🛒 Ventas
            </a>

            <hr>

            <div class="mt-4">
                <small>Usuario conectado</small>

                <p class="fw-bold mb-1">
                    {{ auth()->user()->name }}
                </p>

                <span class="badge bg-success">
                    {{ auth()->user()->role->name }}
                </span>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                    Cerrar sesión
                </button>
            </form>

        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="col-md-9 col-lg-10 p-4">

            <div class="topbar mb-4">
                <h3 class="mb-1">Sistema de Ventas e Inventario</h3>
                <p class="text-muted mb-0">
                    Minimarket El Ahorro
                </p>
            </div>

            <h4 class="mb-3">
                Bienvenido, {{ auth()->user()->name }}
            </h4>

            <p class="text-muted">
                Panel de control del sistema.
            </p>

            <div class="row g-4">

                @if(auth()->user()->role->name === 'Administrador')


 <a href="{{ route('admin.users.index') }}"
   class="text-decoration-none text-dark">
    <div class="card panel-card p-3">
        <h5>👤 Usuarios</h5>
        <p class="text-muted mb-0">
            Administración de usuarios y permisos.
        </p>
    </div>
</a>

                    <a href="{{ route('admin.products.index') }}"
   class="text-decoration-none text-dark">
    <div class="card panel-card p-3">
        <h5>📦 Productos</h5>
        <p class="text-muted mb-0">
            Registrar, buscar y administrar productos.
        </p>
    </div>
</a>

                   <a href="{{ route('admin.categories.index') }}"
   class="text-decoration-none text-dark">
    <div class="card panel-card p-3">
        <h5>📂 Categorías</h5>
        <p class="text-muted mb-0">
            Administrar categorías de productos.
        </p>
    </div>
</a>

                    <a href="{{ route('admin.suppliers.index') }}" class="text-decoration-none text-dark">
    <div class="card panel-card p-3">
        <h5>🏭 Proveedores</h5>
        <p class="text-muted mb-0">Registrar y administrar proveedores.</p>
    </div>
</a>

                   <a href="{{ route('admin.purchases.index') }}" class="text-decoration-none text-dark">
    <div class="card panel-card p-3">
        <h5>🛒 Compras</h5>
        <p class="text-muted mb-0">Registrar compras y aumentar el inventario.</p>
    </div>
</a>


 <a href="{{ route('admin.reports.index') }}" class="text-decoration-none text-dark">
    <div class="card panel-card p-3">
        <h5>📊 Reportes</h5>
        <p class="text-muted mb-0">Consultas y reportes del negocio.</p>
    </div>
</a>
                
                @endif

                <a href="{{ route('customers.index') }}" class="text-decoration-none text-dark">
    <div class="card panel-card p-3">
        <h5>👥 Clientes</h5>
        <p class="text-muted mb-0">Registrar y administrar clientes.</p>
    </div>
</a>

                <a href="{{ route('sales.index') }}" class="text-decoration-none text-dark">
    <div class="card panel-card p-3">
        <h5>🧾 Ventas</h5>
        <p class="text-muted mb-0">Registrar ventas y consultar operaciones.</p>
    </div>
</a>

<a href="{{ route('inventory.index') }}" class="text-decoration-none text-dark">
    <div class="card panel-card p-3">
        <h5>📋 Inventario</h5>
        <p class="text-muted mb-0">Control de existencias.</p>
    </div>
</a>
                

            </div>

            <div class="alert alert-primary mt-4">
                Bienvenido al sistema de gestión del Minimarket El Ahorro.
            </div>

        </main>

    </div>
</div>

</body>
</html>
