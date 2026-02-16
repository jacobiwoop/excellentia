<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Excellentia Gestion Scolaire</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="application scolaire, gestion résultats, programmes cours, Excellentia" name="keywords">
    <meta
        content="Plateforme de gestion scolaire pour l'Institut Excellentia - Résultats étudiants, programmes de cours et administration"
        name="description">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon"
        href="{{ asset('admin/assets/img/favicon/Logo Institut Excellentia@3x.png') }}" />

    <!-- Google Web Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=PT+Serif:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('user/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('user/lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('user/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('user/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('user/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('user/css/styles.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary-color: #000;
            --secondary-color: #343a40;
            --accent-color: #ffc107;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }

        body {
            background: #ffffff;
            font-family: 'Open Sans', sans-serif;
            color: var(--dark-color);
        }

        /* Header Styles */
        .custom-navbar {
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .custom-navbar .navbar-brand img {
            width: 180px;
            height: auto;
            transition: all 0.3s ease;
        }

        .navbar {
            transition: all 0.3s ease;
            padding: 15px 0;
        }

        .navbar:hover {
            background-color: var(--light-color);
        }



        .dropdown-item {
            padding: 0.75rem 1.25rem;
            font-size: 0.875rem;
            color: var(--dark-color);
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: var(--primary-color);
            color: white;
        }

        /* Welcome Section */
        .welcome-section {
            margin-top: -50px;
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
            line-height: 1.3;
        }

        .welcome-subtitle {
            color: var(--secondary-color);
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
            padding: 60px 0;
            border-radius: 15px;
            margin: 50px 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 40px;
            color: var(--primary-color);
            position: relative;
            display: inline-block;
        }

        .section-title:after {
            content: '';
            position: absolute;
            width: 50%;
            height: 3px;
            background: var(--accent-color);
            bottom: -10px;
            left: 0;
        }

        .stat-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            margin: 15px 0;
            transition: all 0.3s ease;
        }

        .stat-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 5px;
        }

        /* Features Section */
        .feature-section {
            padding: 30px 0;
            margin: 40px 0;
        }

        .feature-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            background: white;
            margin-bottom: 30px;
        }



        .feature-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .feature-content {
            padding: 25px;
            flex-grow: 1;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .feature-text {
            color: #555;
            margin-bottom: 20px;
        }

        .feature-link {
            display: inline-flex;
            align-items: center;
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .feature-link:hover {
            color: var(--secondary-color);
        }

        .feature-link i {
            margin-left: 8px;
            transition: all 0.3s ease;
        }

        .feature-link:hover i {
            transform: translateX(5px);
        }



        /* Responsive Adjustments */
        @media (max-width: 991.98px) {
            .welcome-title {
                font-size: 2rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .stat-number {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 767.98px) {
            .navbar-brand img {
                width: 150px !important;
            }

            .welcome-section {
                padding: 50px 0;
            }

            .welcome-title {
                font-size: 1.8rem;
            }

            .section-title {
                font-size: 1.6rem;
            }

            .feature-card {
                margin-bottom: 20px;
            }

            .footer {
                padding: 40px 0 20px;
            }

            .footer-title {
                font-size: 1.3rem;
                margin-bottom: 20px;
            }
        }

        @media (max-width: 575.98px) {
            .welcome-title {
                font-size: 1.5rem;
            }

            .welcome-subtitle {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.4rem;
            }

            .stat-box {
                padding: 20px;
            }

            .stat-icon {
                font-size: 2rem;
            }

            .stat-number {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <div class="container-fluid sticky-top px-0">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm custom-navbar">
            <div class="container">
                <a href="{{ url('/index') }}" class="navbar-brand">
                    <img src="{{ asset('admin/assets/img/favicon/Logo Institut Excellentia@3x.png') }}"
                        alt="Logo Excellentia">
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">

                </button>

                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="btn btn-primary rounded-pill px-4 text-light" href="#services"
                                        onclick="scrollToSection(event)">

                                        </i>Accéder
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                                    role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-2"></i>
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <!-- Welcome Section -->
    <section class="welcome-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-1 order-2">
                    <h1 class="welcome-title">Plateforme de Gestion Scolaire – Institut Excellentia</h1>
                    <p class="welcome-subtitle">
                        Un environnement numérique complet pour suivre les résultats académiques des étudiants,
                        faciliter la gestion des notes, des présences et des sessions de cours pour les formateurs, et
                        offrir un accès sécurisé à l’administration.
                    </p>
                </div>

                <div class="col-lg-6 order-lg-2 order-1 mb-4 mb-lg-0">
                    <img src="{{ asset('admin/assets/img/exc.jpg') }}" alt="École Excellentia"
                        class="img-fluid rounded-3">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section
    <section class="stats-section">
        <div class="container">
            <h2 class="section-title text-center">Excellentia en Chiffres</h2>

            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number" id="studentCount">600</div>
                        <p>Étudiants inscrits</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="stat-number" id="teacherCount">10</div>
                        <p>Formateurs qualifiés</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="fas fa-school"></i>
                        </div>
                        <div class="stat-number" id="siteCount">3</div>
                        <p>Sites de formation</p>
                    </div>
                </div>
            </div>

            <p class="text-center mt-5 lead">
                Fondé sur des valeurs d'excellence et d'innovation, l'Institut Excellentia s'engage à fournir
                une éducation de qualité dans un environnement stimulant et bienveillant.
            </p>
        </div>
    </section>
     -->

    <!-- Features Section -->
    <section class="feature-section py-4" id="services" style="background-color: white;">
        <div class="container">

            <div class="row g-4">

                <div class="col-lg-4 col-md-6 mx-auto">
                    <div class="card border-0 shadow-lg h-100 rounded-4 overflow-hidden">
                        <img src="{{ asset('user/img/pro.jpg') }}" class="card-img-top" alt="Programmes de Cours"
                            style="object-fit: cover; height: 220px;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Portail Admin</h5>
                            <p class="card-text">
                                Gérez efficacement les programmes de cours par site, suivez leur mise à jour,
                                et assurez la cohérence pédagogique entre les différentes filières et promotions.
                            </p>

                            <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-pill mt-2">Accéder <i
                                    class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                <!-- Student Portal -->
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-lg h-100 rounded-4 overflow-hidden">
                        <img src="{{ asset('user/img/group.png') }}" class="card-img-top" alt="Portail Étudiant"
                            style="object-fit: cover; height: 220px;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Portail Étudiant</h5>
                            <p class="card-text">
                                Accédez à vos résultats, consultez votre emploi du temps,
                                téléchargez vos ressources et suivez votre progression.
                            </p>
                            <a href="{{ route('etudiant.login') }}"
                                class="btn btn-outline-dark rounded-pill mt-2">Accéder <i
                                    class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Teacher Portal -->
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-lg h-100 rounded-4 overflow-hidden">
                        <img src="{{ asset('user/img/teach.jpg') }}" class="card-img-top" alt="Portail Formateur"
                            style="object-fit: cover; height: 220px;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Portail Formateur</h5>
                            <p class="card-text">
                                Gestion des notes, planification des cours, partage de ressources
                                et communication avec les étudiants.
                            </p>
                            <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-pill mt-2">Accéder <i
                                    class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Programs -->

            </div>
        </div>
    </section>


</body>

</html>