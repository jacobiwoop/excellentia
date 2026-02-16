<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
  data-assets-path="{{ asset('admin/') }}" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>Dashboard</title>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('admin/assets/img/favicon/excellentia -logo BLANC 2.png') }}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />
  <!-- Icons -->
  <link rel="stylesheet" href="{{ asset('admin/assets/vendor/fonts/boxicons.css') }}" />
  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('admin/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{ asset('admin/assets/vendor/css/theme-default.css') }}"
    class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{ asset('admin/assets/css/demo.css') }}" />

  <!-- Ajouter ces lignes dans la section <head> de ton fichier Blade -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-calendar/1.4.0/css/calendar.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-calendar/1.4.0/js/calendar.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/fr.js'></script>
  <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@5.10.1/main.global.min.js'></script>
  <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@5.10.1/main.global.min.js'></script>
  <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@5.10.1/main.global.min.js'></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{ asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
  <link rel="stylesheet" href="{{ asset('admin/assets/vendor/libs/apex-charts/apex-charts.css') }}" />

  <style>
    body {
      background: white;
    }

    .menu-item.active .menu-icon {
      color: #ffffff;
      /* Couleur de l'icône de l'élément actif */
    }

    .menu-item a {
            color: white;
        }
        .menu-item a:hover {
            text-decoration: none;
        }

    /* Styles pour les boutons de filtre de filière */
    .btn-group .btn {
      border-radius: 0.25rem;
    }


    .filter-btn {
      cursor: pointer;
    }

    .table img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 50%;
    }

    .table td,
    .table th {
      vertical-align: middle;
    }

    .badge {
      background-color: #e30f80;
      /* Couleur de fond de la notification */
      color: #fff;
      /* Couleur du texte */
    }

    /* Profile image circle */
    .navbar-dropdown .dropdown-user img {
      border: 2px solid #fff;
      /* Bordure blanche pour l'image */
    }

    /* Dropdown menu styling */
    .dropdown-menu {
      min-width: 10rem;
      /* Largeur minimale du menu */
    }

    .dropdown-menu .dropdown-item {
      font-size: 0.875rem;
      /* Taille de police */
    }

    .dropdown-menu .dropdown-item:hover {
      background-color: #e9ecef;
      /* Couleur de fond au survol */
    }

    /* Styles pour le tableau */
    .table-custom {
      border-radius: 0.75rem;
      border: 1px solid #dee2e6;
      background-color: #fff;
    }

    .table-custom th,
    .table-custom td {
      padding: 1rem;
      vertical-align: middle;
    }

    .table-custom thead {
      background-color: #f8f9fa;
    }

    .table-custom tbody tr:hover {
      background-color: #f1f1f1;
    }

    .student-img {
      width: 50px;
      height: 50px;
      object-fit: cover;
    }
    
    /* Style pour le bouton de déconnexion dans la sidebar */
    .logout-menu-item {
      position: absolute;
      bottom: 0;
      width: 100%;
      padding: 1rem;
    }

    .logout-menu-item .menu-link {
      color: #fff;
      font-weight: 500;
    }

    .logout-menu-item .menu-link:hover {
      background-color: rgba(220, 53, 69, 0.1);
    }

    .logout-menu-item .menu-icon {
      color: #fff;
    }

    /* Style pour le nom d'utilisateur dans la navbar */
    .user-name {
      font-weight: 500;
      color: #495057;
      padding: 0.5rem 1rem;
    }


  </style>
  <!-- Helpers -->
  <script src="{{ asset('admin/assets/vendor/js/helpers.js') }}"></script>
  <script src="{{ asset('admin/assets/js/config.js') }}"></script>
</head>

<body>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <a href="{{ route('admin.students.index') }}" class="app-brand-link">
            <span class="app-brand-logo demo mb-3">
              <img src="{{ asset('admin/assets/img/favicon/excellentia -logo BLANC 2.png') }}" alt="Logo"
                style="width: 220px; height: auto; margin-top: 80px; padding-bottom: 60px;">
            </span>
          </a>


        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Dashboard -->
          <li class="menu-item ">
            <a href="{{ route('admin.accounting_dashboard.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-home-circle"></i>
              <div data-i18n="Analytics">Tableau de bord</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{ route('admin.students.index') }}" class="menu-link">
              <i class="fa-solid fa-users" style="margin-right: 15px"></i>
              <div data-i18n="Basic">Etudiants</div>
            </a>
          </li>

          <li class="menu-item ">
            <a href="{{ route('admin.student_fees.index') }}" class="menu-link">
              <i class="fas fa-euro-sign" style="margin-right: 15px"></i>
              <div data-i18n="Basic">Comptabilité</div>
            </a>
          </li>

           <li class="menu-item ">
            <a href="{{ route('admin.fees.index') }}" class="menu-link">
              <i class="fas fa-euro-sign" style="margin-right: 15px"></i>
              <div data-i18n="Basic">Frais</div>
            </a>
          </li>
        

         <!-- Bouton de déconnexion en bas de la sidebar -->
         <li class="menu-item logout-menu-item">
          <a href="{{ route('logout') }}" class="menu-link"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="menu-icon tf-icons bx bx-power-off fa-2x "></i>
            <div data-i18n="Basic">Déconnexion</div>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
          </form>
        </li>
        </ul>
      </aside>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->
        <nav
          class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
          id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="bx bx-menu bx-sm"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              

            <!-- Navbar Items -->
            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- Notification Icon -->
             

              <!-- User Profile Icon -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown pe-2">
                <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center" href="javascript:void(0);"
                  data-bs-toggle="dropdown">
                  <span class="ms-2 d-none d-md-block">{{ Auth::user()->name }}</span>
                </a>
                
              </li>
            </ul>
          </div>
        </nav>

        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper" style="background: #fff;">
          <div class="content-backdrop fade"></div>
          @yield('content')
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
  <!-- / Layout wrapper -->

  <!-- Core JS -->
  <script src="{{ asset('admin/assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/js/menu.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Vendors JS -->
  <script src="{{ asset('admin/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

  <!-- Main JS -->
  <script src="{{ asset('admin/assets/js/main.js') }}"></script>

  <!-- Page JS -->
  <script src="{{ asset('admin/assets/js/dashboards-analytics.js') }}"></script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>


  </script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Initialisation des tooltips
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    });

    function confirmDelete(formId) {
      const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
      modal.show();

      document.getElementById('confirmDeleteBtn').onclick = function () {
        document.getElementById(formId).submit();
      };
    }
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Basculer entre notes et bulletin
        document.getElementById('showGradesBtn').addEventListener('click', function() {
            document.getElementById('gradesSection').style.display = 'block';
            document.getElementById('bulletinSection').style.display = 'none';
            this.classList.remove('btn-outline-dark');
            this.classList.add('btn-dark');
            document.getElementById('showBulletinBtn').classList.remove('btn-dark');
            document.getElementById('showBulletinBtn').classList.add('btn-outline-dark');
        });

        document.getElementById('showBulletinBtn').addEventListener('click', function() {
            document.getElementById('gradesSection').style.display = 'none';
            document.getElementById('bulletinSection').style.display = 'block';
            this.classList.remove('btn-outline-dark');
            this.classList.add('btn-dark');
            document.getElementById('showGradesBtn').classList.remove('btn-dark');
            document.getElementById('showGradesBtn').classList.add('btn-outline-dark');
        });

        // Changer de trimestre pour le bulletin
        document.querySelectorAll('.change-term').forEach(btn => {
            btn.addEventListener('click', function() {
                const term = this.getAttribute('data-term');
                // Recharger les données du bulletin pour le trimestre sélectionné
                window.location.href = window.location.pathname + "?term=" + term;
            });
        });

        // Au chargement, si un trimestre est spécifié dans l'URL, afficher le bulletin
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.has('term')) {
            document.getElementById('showBulletinBtn').click();
        }
    });
</script>

</body>

</html>