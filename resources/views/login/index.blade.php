{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login/login.css') }}">
</head>
<body>
    <div class="contenido">
        <div class="login-formulario-contenedor">
            <header>
                <h1>INICIAR SESION</h1>
            </header>
            <form action="{{ route('login.autenticar') }}" method="GET">
                <div class="input-contenedor">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" class="texto" name="email" required>
                </div>
                <div class="input-contenedor">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" class="texto" name="password" required>
                </div>
                <div class="input-contenedor">
                    <label for="password">Recordar Contraseña</label>
                    <input type="checkbox" id="remember" name="remember" class="recordar">
                </div>
                <button type="submit" class="boton-iniciar-sesion">Iniciar sesión</button>
            </form>
        </div>
    </div>
</body>
</html> --}}


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Ventas</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login/login.css') }}">
</head>
<body>

    <div class="container">

        <!-- LEFT -->
        <div class="left">

            <div class="logo">
                <i class="fa-solid fa-chart-line"></i>
                <h1>Sistema de <span>Facturacion</span></h1>
            </div>

            <p class="description">
                Gestiona tus ventas, productos y clientes de forma inteligente con estadísticas en tiempo real.
            </p>

            <div class="dashboard-preview">

                <div class="dashboard-header">
                    <div class="dashboard-title">
                        Resumen de ventas
                    </div>

                    <div class="tag">
                        Este mes
                    </div>
                </div>

                <div class="chart">
                    <div class="line">

                        <svg viewBox="0 0 500 200">

                            <path
                            d="M0 160 C80 90, 120 140, 200 100 S320 40, 500 70"
                            fill="none"
                            stroke="#8b5cf6"
                            stroke-width="6"
                            stroke-linecap="round"/>

                        </svg>

                    </div>
                </div>

                <div class="stats">

                    <div class="stat-card">
                        <h3>Pedidos</h3>
                        <p>128</p>
                    </div>

                    <div class="stat-card">
                        <h3>Productos</h3>
                        <p>56</p>
                    </div>

                    <div class="stat-card">
                        <h3>Ventas</h3>
                        <p class="sales">C$ 3,240</p>
                    </div>

                </div>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="right">

            <div class="login-card">

                <h2>Iniciar sesión</h2>

                <p class="subtitle">
                    Ingresa tus credenciales para acceder al sistema
                </p>

                <form action="{{ route('login.autenticar') }}" method="GET">

                    <div class="form-group">

                        <label>Correo electrónico</label>

                        <div class="input-wrapper">
                            <i class="fa-regular fa-envelope"></i>

                            <input
                            type="email"
                            class="input"
                            placeholder="ejemplo@correo.com" 
                            id="email"
                            name="email"
                            required>
                        </div>

                    </div>

                    <div class="form-group">

                        <label>Contraseña</label>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock"></i>

                            <input
                            type="password"
                            class="input"
                            placeholder="Ingresa tu contraseña"
                            id="password"
                            name="password"
                            required>
                        </div>

                    </div>

                    <div class="row">

                        <label class="remember">
                            <input type="checkbox"
                            id="remember" 
                            name="remember">
                            Recordarme
                        </label>

                        <a href="#" class="forgot">
                            ¿Olvidaste tu contraseña?
                        </a>

                    </div>

                    <button class="btn">
                        Iniciar sesión
                    </button>


                </form>

            </div>

        </div>

    </div>

</body>
</html>
