<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Exclusivo - AKOC Control Inmobiliario</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Paleta de colores sofisticada */
            --background-white: #ffffff;
            --image-panel-bg: #f4f4f4; /* Un gris muy claro para el panel de imagen */
            --text-dark: #2c3e50; /* Un gris azulado oscuro, más suave que el negro */
            --text-light: #7f8c8d;
            --accent-gold: #c0a062; /* Dorado/bronce para el botón */
            --border-color: #e0e0e0;
            --focus-glow: rgba(192, 160, 98, 0.25); /* Resplandor dorado al hacer focus */
        }

        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Lato', sans-serif;
            background-color: var(--background-white);
            color: var(--text-dark);
        }

        /* Contenedor principal de pantalla dividida */
        .split-container {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Dos columnas de igual tamaño */
            height: 100vh;
        }

        /* Panel izquierdo con la imagen */
        .image-panel {
            background-image: url('https://images.pexels.com/photos/2724749/pexels-photo-2724749.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2');
            background-size: cover;
            background-position: center;
        }

        /* Panel derecho con el formulario */
        .login-panel {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            box-sizing: border-box;
        }

        .login-wrapper {
            width: 100%;
            max-width: 400px; /* Ancho máximo para el formulario */
        }

        .logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 40px;
        }

        h1 {
            font-family: 'Playfair Display', serif; /* Fuente elegante */
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0 0 10px 0;
        }

        .subtitle {
            color: var(--text-light);
            margin: 0 0 40px 0;
            font-size: 1rem;
        }

        .form-group { margin-bottom: 25px; }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 14px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1rem;
            font-family: 'Lato', sans-serif;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        input:focus {
            outline: none;
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 3px var(--focus-glow);
        }

        button {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 4px;
            background-color: var(--accent-gold);
            color: white;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background-color 0.3s, transform 0.2s;
        }
        button:hover {
            background-color: #a9894e; /* Dorado más oscuro */
            transform: translateY(-2px);
        }

        .error-message {
            color: #c0392b;
            font-size: 0.9rem;
            margin-bottom: 20px;
            background-color: #f2dede;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }

        .secondary-link {
            text-align: center;
            margin-top: 30px;
        }
        .secondary-link a {
            color: var(--text-light);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }
        .secondary-link a:hover {
            color: var(--accent-gold);
            text-decoration: underline;
        }

        /* Responsive: apilar en una sola columna en pantallas pequeñas */
        @media (max-width: 900px) {
            .split-container {
                grid-template-columns: 1fr; /* Una sola columna */
            }
            .image-panel {
                display: none; /* Ocultar la imagen en móviles para centrarse en el login */
            }
            .login-panel {
                background-color: var(--background-white); /* Asegurar fondo blanco en móvil */
            }
        }
    </style>
</head>
<body>

    <div class="split-container">
        <div class="image-panel"></div>

        <div class="login-panel">
            <div class="login-wrapper">
                <img src="https://inmobiliariaakoc.com.mx/img/logoakoc.png" alt="Logo AKOC Control Inmobiliario" class="logo">
                
                <h1>Bienvenido de Vuelta</h1>
                <p class="subtitle">Ingrese a su cuenta para continuar.</p>

                @if ($errors->any())
                    <div class="error-message">
                        {{ $errors->first('email') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit">Iniciar Sesión</button>
                </form>

                <div class="secondary-link">
                    <a href="#">¿Olvidó su contraseña?</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>