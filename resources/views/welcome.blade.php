<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AKOC Control Inmobiliario | Residencias de Lujo</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        /* --- Paleta de Colores y Fuentes --- */
        :root {
            --bg-color: #fdfaf6; /* Un blanco cálido, marfil */
            --text-color: #3a3a3a; /* Un gris oscuro, más suave que el negro */
            --accent-color: #c0a062; /* Un dorado/bronce sutil */
            --font-serif: 'Playfair Display', serif; /* Fuente elegante para títulos */
            --font-sans: 'Lato', sans-serif; /* Fuente limpia para el cuerpo del texto */
        }

        /* --- Estilos Globales --- */
        body, html {
            margin: 0;
            padding: 0;
            font-family: var(--font-sans);
            background-color: var(--bg-color);
            color: var(--text-color);
            scroll-behavior: smooth;
            overflow-x: hidden;
        }
        
        h1, h2, h3 {
            font-family: var(--font-serif);
            font-weight: 600;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* --- Encabezado y Navegación --- */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            box-sizing: border-box;
            transition: background-color 0.4s ease, box-shadow 0.4s ease;
        }

        /* Estilo del header cuando se hace scroll */
        .header.scrolled {
            background-color: rgba(253, 250, 246, 0.9); /* Fondo marfil semitransparente */
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .header .logo {
            font-family: var(--font-serif);
            font-size: 28px;
            font-weight: 700;
            color: var(--text-color);
            text-decoration: none;
        }

        .header .access-buttons a {
            text-decoration: none;
            color: var(--text-color);
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 5px;
            margin-left: 15px;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .header .access-buttons .cliente-btn:hover {
            color: var(--accent-color);
            border-color: var(--accent-color);
        }
        .header .access-buttons .admin-btn {
            background-color: var(--accent-color);
            color: white;
        }
        .header .access-buttons .admin-btn:hover {
            background-color: #a9894e; /* Dorado más oscuro al pasar el ratón */
        }

        /* --- Sección de Héroe (Imagen Principal) --- */
        .hero-section {
            position: relative;
            height: 100vh;
            /* IMAGEN DE ALTA CALIDAD Y ELEGANTE */
            background-image: url('https://images.pexels.com/photos/3935320/pexels-photo-3935320.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            color: white;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(0, 0, 0, 0.5), transparent); /* Sombra sutil a la izquierda */
        }

        .hero-content {
            position: relative;
            z-index: 1;
            padding: 20px;
        }

        .hero-content h1 {
            font-size: 4.5rem;
            margin: 0 0 15px 0;
            text-shadow: 1px 1px 5px rgba(0,0,0,0.4);
            line-height: 1.1;
            font-weight: 700;
        }

        .hero-content p {
            font-size: 1.3rem;
            max-width: 500px;
            line-height: 1.6;
            font-weight: 300;
        }

        /* --- Carrusel de Fotos --- */
        .carousel-section {
            padding: 100px 20px;
            text-align: center;
        }
        
        .carousel-section h2 {
            font-size: 3rem;
            margin-bottom: 60px;
        }

        .carousel-container {
            position: relative;
            max-width: 1000px;
            margin: auto;
            overflow: hidden;
        }

        .carousel-slide {
            display: none;
            animation: fadeIn 1.2s;
        }

        .carousel-slide img {
            width: 100%;
            height: 600px;
            object-fit: cover;
            vertical-align: middle;
            border-radius: 5px;
        }
        
        .carousel-slide .caption {
            font-family: var(--font-serif);
            font-size: 1.4rem;
            margin-top: 15px;
            color: #555;
        }

        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 45%;
            width: auto;
            padding: 16px;
            color: var(--text-color);
            font-weight: bold;
            font-size: 24px;
            transition: 0.3s ease;
            user-select: none;
            opacity: 0.5;
        }
        .next { right: -50px; }
        .prev { left: -50px; }
        .carousel-container:hover .next { right: 10px; opacity: 1;}
        .carousel-container:hover .prev { left: 10px; opacity: 1;}

        /* --- Sección de Características --- */
        .features-section {
            padding: 100px 20px;
            background-color: #fff;
            text-align: center;
        }
        .features-section h2 { font-size: 3rem; margin-bottom: 60px; }

        .features-grid {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
        }

        .feature-item {
            padding: 30px;
            width: 30%;
            min-width: 280px;
            box-sizing: border-box;
        }
        .feature-item h3 { font-size: 1.8rem; color: var(--accent-color); }
        .feature-item p { color: #666; line-height: 1.7; }

        /* --- Pie de Página --- */
        .footer {
            background-color: #ece5da; /* Un beige un poco más oscuro */
            color: #5c5c5c;
            text-align: center;
            padding: 50px 20px;
        }
        .footer a {
            color: var(--accent-color);
            text-decoration: none;
            margin: 0 15px;
        }
        .footer a:hover { text-decoration: underline; }

        /* --- Animaciones y Media Queries --- */
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        
        @media (max-width: 768px) {
            .header { padding: 15px 20px; }
            .hero-content h1 { font-size: 2.8rem; }
            .hero-content p { font-size: 1.1rem; }
            .carousel-slide img { height: 400px; }
            .features-grid { flex-direction: column; align-items: center; }
            .feature-item { width: 80%; }
        }
    </style>
</head>
<body>

    <header class="header">
        <a href="/" class="logo">AKOC</a>
        <div class="access-buttons">
            <a href="/cliente/login" class="cliente-btn">Portal Clientes</a>
            <a href="/admin/login" class="admin-btn">Administrador</a>
        </div>
    </header>

    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1>El Arte de Vivir, <br>Redefinido.</h1>
                <p>Descubra residencias donde el diseño excepcional y el confort se encuentran en perfecta armonía.</p>
            </div>
        </div>
    </section>

    <section id="proyectos" class="carousel-section">
        <div class="container">
            <h2>Nuestros Espacios</h2>
            <div class="carousel-container">
                <div class="carousel-slide">
                    <img src="https://images.pexels.com/photos/6284227/pexels-photo-6284227.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="Sala de estar luminosa y elegante">
                    <p class="caption">Luminosidad y Amplitud en Cada Rincón</p>
                </div>
                <div class="carousel-slide">
                    <img src="https://images.pexels.com/photos/8089255/pexels-photo-8089255.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="Cocina moderna con acabados de lujo">
                    <p class="caption">Acabados Premium y Diseño Funcional</p>
                </div>
                <div class="carousel-slide">
                    <img src="https://images.pexels.com/photos/7031407/pexels-photo-7031407.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="Dormitorio sereno y confortable">
                    <p class="caption">Santuarios de Descanso y Serenidad</p>
                </div>

                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </div>
        </div>
    </section>

    <section class="features-section">
        <div class="container">
            <h2>Filosofía de Diseño</h2>
            <div class="features-grid">
                <div class="feature-item">
                    <h3>Elegancia Atemporal</h3>
                    <p>Creamos espacios que trascienden las tendencias, enfocados en la belleza duradera y la calidad excepcional.</p>
                </div>
                <div class="feature-item">
                    <h3>Ubicación Privilegiada</h3>
                    <p>Cada proyecto se sitúa en enclaves estratégicos, ofreciendo un estilo de vida inmejorable y una inversión sólida.</p>
                </div>
                <div class="feature-item">
                    <h3>Atención al Detalle</h3>
                    <p>Desde la distribución hasta el último acabado, cada elemento es cuidadosamente seleccionado para su confort.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} AKOC Control Inmobiliario. Todos los derechos reservados.</p>
            <p style="margin-top: 20px;">
                <a href="#">Aviso de Privacidad</a>
                <a href="#">Contacto</a>
            </p>
        </div>
    </footer>

    <script>
        // --- Lógica del Carrusel ---
        let slideIndex = 1;
        showSlides(slideIndex);
        function plusSlides(n) { showSlides(slideIndex += n); }
        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("carousel-slide");
            if (n > slides.length) { slideIndex = 1 }
            if (n < 1) { slideIndex = slides.length }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slides[slideIndex - 1].style.display = "block";
        }
        setInterval(() => { plusSlides(1); }, 6000);

        // --- Lógica para el header con scroll ---
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>