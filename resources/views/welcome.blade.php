<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>web</title>
</head>

<body>
    <header>
        <div class="menu container">
       
            <img class="logo-1" src="{{ asset('images/logo.jpg') }}" alt="">
            <input type="checkbox" id="menu">
            <label for="menu">
                <img src="{{ asset('images/menu.png') }}" class="menu-icono">
            </label>

            <nav class="navbar">
                <div class="menu-1">
                    <ul>
                        <li><a href="">Inicio</a></li>
                        <li><a href="">Así somos</a></li>
                        <li><a href="">Ubicación</a></li>0
                    </ul>
                </div>

                <img class="logo-2" src="{{ asset('images/logo.jpg') }}" alt="">

                <div class="menu-2">
                    <ul>
                        <li><a href="{{ route('login') }}">Login</a></li>
                    </ul>

                    <div class="socials">
                        <a href="">
                            <div class="social">
                                <img src="{{ asset('images/s1.svg') }}" alt="">
                            </div>
                        </a>

                        <a href="">
                            <div class="social">
                                <img src="{{ asset('images/s2.svg') }}" alt="">
                            </div>
                        </a>

                        <a href="">
                            <div class=social>
                                <img src="{{ asset('images/s3.svg') }}" alt="">
                            </div>
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <section class="hero-video-section">
        <video autoplay loop muted playsinline class="background-video">
          
            <source src="{{ asset('video/web.mp4') }}" type="video/mp4">
            Tu navegador no soporta el tag de video.
        </video>
        <div class="video-gradient-overlay"></div>
        <div class="hero-content">
            <h1>Bienvenido a Clínica Salud</h1>
            <p>Donde tu bienestar es nuestra prioridad.</p>
            <a href="#" class="btn-cita">Agenda tu Cita</a>
        </div>
    </section>

    <section class="hero-section">
        <div class="hero-content-left">
            <p class="subtitle">Cuidando tu salud y toda tu familia.</p>
            <h2>Nosotros Proporcionamos Todos los Aspectos Medicos para Toda Tu Familia!</h2>
        </div>
        <div class="hero-content-right">
            <p>"Nos enorgullece brindar atención médica de excelencia, combinando la más avanzada tecnología con un equipo humano altamente calificado y comprometido con su bienestar. Desde nuestra fundación, nuestra misión ha sido ser un pilar de salud y esperanza para la comunidad, ofreciendo servicios integrales y personalizados que abordan las necesidades de cada paciente con la calidez y el respeto que merecen. Nos enorgullece ser un centro de innovación y cuidado, donde su salud es siempre nuestra máxima prioridad."</p>

            <ul class="checklist">
                <li><i class="fas fa-check-circle checkmark-icon"></i> Sistema de Control de Calidad</li>
                <li><i class="fas fa-check-circle checkmark-icon"></i> Mano de Obra Inigualable</li>
                <li><i class="fas fa-check-circle checkmark-icon"></i> 100% Trabajo Responsable</li>
            </ul>
        </div>
    </section>

    <section class="recent-events">
        <h2 class="section-title">Recent Events</h2>
        <div class="articles-container">
            <article class="article-card">
                <div class="article-image">
                    <img src="" alt=""> {{-- Esta imagen está vacía, no se puede corregir con asset() --}}
                    <span class="category-i a">Mental Health</span>
                </div>
                <div class="article-content">
                    <div class="article_dates">
                        <span class="date">Jan 22,2025</span>
                        <span class="author">Wayne King</span>
                    </div>

                    <h3 class="article-title">6 Tips to Protect Your Mental Health</h3>
                    <p class="article-description">It's natural to feel anxious, worry and grief any time you're diagnosed with a condition that's relatively from If you get positive for COVID-19, or are presumed to be positive.</p>
                    <a href="#" class="read-more-btn">Read More</a>
                </div>
            </article>

            <article class="article-card">
                <div class="article-image">
                    <img src="" alt=""> {{-- Esta imagen está vacía, no se puede corregir con asset() --}}
                    <span class="category-i b">Wellness</span>
                </div>
                <div class="article-content">
                    <div class="article_dates">
                        <span class="date">Jan 22,2025</span>
                        <span class="author">Wayne King</span>
                    </div>

                    <h3 class="article-title">Unsure About Wearing a Face Mask? Here's How and Why</h3>
                    <p class="article-description">It's natural to feel anxious, worry and grief any time you're diagnosed with a condition that's relatively from If you get positive for COVID-19, or are presumed to be positive.</p>
                    <a href="#" class="read-more-btn">Read More</a>
                </div>
            </article>

            <article class="article-card">
                <div class="article-image">
                    <img src="" alt=""> {{-- Esta imagen está vacía, no se puede corregir con asset() --}}
                    <span class="category-i c">Mental Health</span>
                </div>
                <div class="article-content">
                    <div class="article_dates">
                        <span class="date">Jan 22,2025</span>
                        <span class="author">Wayne King</span>
                    </div>

                    <h3 class="article-title">6 Tips to Protect Your Mental Health</h3>
                    <p class="article-description">It's natural to feel anxious, worry and grief any time you're diagnosed with a condition that's relatively from If you get positive for COVID-19, or are presumed to be positive.</p>
                    <a href="#" class="read-more-btn">Read More</a>
                </div>
            </article>
        </div>
    </section>
</div>

<footer>
    <div class="footer-content">
        <div class="footer-section about">
            <div class="logo">
                {{-- Imagen en el footer. Asume que moviste 'images/sf.png' a 'public/images/sf.png' --}}
                <img src="{{ asset('images/sf.png') }}" alt="Medcity Logo"> <h2>San Francisco</h2>
            </div>
            <p>
                Nuestro objetivo es brindar atención de calidad con cortesía, respeto y compasión. Esperamos que nos permita atenderlo y nos esforzamos por ser la primera y mejor opción para la atención médica de su familia.
            </p>
            <a href="#" class="make-appointment">Hacer cita →</a>
        </div>

        <div class="footer-section departments">
            <h3>Departamentos</h3>
            <ul>
                <li><a href="#">Clinica Neurológica</a></li>
                <li><a href="#">Clinica Cardiologica</a></li>
                <li><a href="#">Clinica Patológica</a></li>
                <li><a href="#">Laboratorio de Análisis</a></li>
                <li><a href="#">Pediatria</a></li>
            </ul>
        </div>

        <div class="footer-section links">
            <h3>Links</h3>
            <ul>
                <li><a href="#">Acerca de nosotros</a></li>
                <li><a href="#">Nuestra ubicacion</a></li>
                <li><a href="#">Nuestros Doctores</a></li>
                <li><a href="#">Noticias</a></li>
            </ul>
        </div>

        <div class="footer-section quick-contacts">
            <h3>Contactos Rápidos</h3>
            <p>Si tienes alguna pregunta o necesitas ayuda, sientete libre de contactar con nuestro equipo.</p>
            <p class="phone">01061245741</p>
            <p class="address">2307 Beverley Rd Brooklyn, New York 11226 United States.</p>
            <a href="https://www.google.com/maps/dir/?api=1&destination=2307+Beverley+Rd+Brooklyn,+New+York+11226+United+States" target="_blank" class="get-directions">Get Directions →</a>
            <div class="social-icons">
                {{-- Aquí asumo que tienes estos iconos en la carpeta public/images/ --}}
                <a href="#"><img src="{{ asset('images/facebook-icon.png') }}" alt="Facebook"></a>
                <a href="#"><img src="{{ asset('images/twitter-icon.png') }}" alt="Twitter"></a>
                <a href="#"><img src="{{ asset('images/instagram-icon.png') }}" alt="Instagram"></a>
            </div>
        </div>

        <div class="footer-section map-container">
            <h3>Our Location</h3>
            <div id="google-map"></div>
        </div>
    </div>
</footer>

</body>
</html>
