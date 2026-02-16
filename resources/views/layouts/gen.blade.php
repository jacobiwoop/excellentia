<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
  data-assets-path="{{ asset('admin/') }}" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>Admin Général Dashboard</title>
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('admin/assets/img/favicon/gest.png') }}" />
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
  <link rel="stylesheet" href="{{ asset('admin/assets/vendor/libs/apex-charts/apex-charts.css') }}" />

  <style>
    body {
      background: white;
      font-family:Arial;
    }

    .btn-primary,
    .bg-primary,
    .badge-primary,
    .alert-primary,
    .text-primary,
    {
    background-color: #ffc107 !important;
    border-color: #ffc107 !important;
    }

    .btn-outline-primary {
      border-color: #ffc107 !important;
    }

    .btn-primary {
      background: #ffc107 !important;
      border-color: #ffc107 !important;

    }

    .btn-outline-primary:hover {
      background-color: #ffc107 !important;

    }

    .menu-item.active .menu-icon {
      color: #ffffff;
    }

    .menu-item.active .menu-icon:hover {
      text-decoration: none;
    }

    .menu-item a {
      color: white;
    }

    .menu-item a:hover {
      text-decoration: none;
    }

    .btn-group .btn {
      border-radius: 0.25rem;
    }

    .btn-group .btn.active {
      box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.1);
    }

    .nav-link i.bx-user-circle {
  color: #ffc107;   /* couleur sombre */
  font-size: 1.8rem; /* taille personnalisée */
}

    .filter-btn {
      cursor: pointer;
    }

    /* Styles pour le menu de filtrage */
    .filter-menu {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      /* Espacement entre les éléments */
      justify-content: center;
      /* Centre les éléments horizontalement */
    }

    .filter-item {
      display: inline-block;
      padding: 10px 20px;
      border-radius: 5px;
      background-color: #E4335F;
      color: #fff;
      text-decoration: none;
      font-weight: bold;
      text-align: center;
      transition: background-color 0.3s, color 0.3s, transform 0.2s;
      flex: 1 1 auto;
      /* Permet à chaque élément de s'adapter à l'espace disponible */
      max-width: 200px;
      /* Limite la largeur des éléments pour une apparence plus cohérente */
    }

    .filter-item:hover,
    .filter-item.active {
      background-color: #D52C6E;
      /* Couleur plus foncée pour l'élément actif et au survol */
      color: #fff;
      transform: scale(1.05);
      /* Légère augmentation de taille au survol */
    }


    /* Styles pour les étudiants */
    .etudiant {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
    }

    /* Styles personnalisés pour le champ de date */
    .custom-date-picker {
      border-radius: 0.25rem;
      border: 1px solid #ced4da;
      padding: 0.375rem 0.75rem;
      font-size: 1rem;
      line-height: 1.5;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .custom-date-picker:focus {
      border-color: #80bdff;
      outline: 0;
      box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
    }

    .input-group .btn {
      border-radius: 0.25rem;
    }

    /* Styles pour le champ de date */
    .custom-date-picker {
      width: 150px;
      /* Réduit la largeur du champ de date */
      border-radius: 0.25rem;
      border: 1px solid #ced4da;
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
      /* Réduit la taille du texte */
      line-height: 1.5;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .custom-date-picker:focus {
      border-color: #80bdff;
      outline: 0;
      box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
    }

    .input-group .btn {
      border-radius: 0.25rem;
    }

    .presence-btn {
      cursor: pointer;
      background-color: #f0f0f0;
      border: 1px solid #ddd;
      padding: 5px;
      border-radius: 5px;
      margin: 2px;
      font-size: 1em;
    }

    .btn-success {
      background-color: #28a745;
      color: white;
      border-color: #28a745;
    }

    .btn-outline-success {
      border-color: #28a745;
      color: #28a745;
    }

    .btn-danger {
      background-color: #dc3545;
      color: white;
      border-color: #dc3545;
    }

    .btn-outline-danger {
      border-color: #dc3545;
      color: #dc3545;
    }

    .btn-warning {
      background-color: #ffc107;
      color: white;
      border-color: #ffc107;
    }

    .btn-outline-warning {
      border-color: #ffc107;
      color: #ffc107;
    }

    .active {
      opacity: 0.8;
    }

    .table th,
    .table td {
      vertical-align: middle;
    }

    /* Navbar background color and shadow */
    .bg-navbar-theme {
      background-color: white;
      /* Changer la couleur selon le thème */
    }

    /* Search input styling */
    .navbar-nav .form-control {
      border-radius: 0.375rem;
      /* Coins arrondis */
      padding: 0.5rem 1rem;
      font-size: 0.875rem;
      /* Taille de police */
    }

    /* Notification badge styling */
    .badge {
      background-color: #e30f80;
      /* Couleur de fond de la notification */
      color: #fff;
      /* Couleur du texte */
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


    @media print {
      body * {
        visibility: hidden;
      }

      .bulletin-container,
      .bulletin-container * {
        visibility: visible;
      }

      .bulletin-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none;
        box-shadow: none;
      }

      .no-print {
        display: none !important;
      }
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
          <a href="{{ url('admingen/dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo mb-3">
              <img src="{{ asset('admin/assets/img/favicon/excellentia -logo BLANC 2.png') }}" alt="Logo"
                style="width: 200px; height: auto; margin-top: 80px; padding-bottom: 60px;">
            </span>
          </a>

          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Dashboard -->
          <li class="menu-item">
            <a href="{{ url('admingen/dashboard') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-home-circle"></i>
              <div data-i18n="Analytics">Tableau de bord</div>
            </a>
          </li>

          <!-- Admins -->
          <li class="menu-item">
            <a href="{{ route('admingen.admins.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-user-check"></i>
              <div data-i18n="Basic">Admins</div>
            </a>
          </li>

          <!-- Promotions -->
          <li class="menu-item">
            <a href="{{ route('admingen.promotions.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-calendar-alt"></i>
              <div data-i18n="Basic">Promotions</div>
            </a>
          </li>

          <!-- Filières -->
          <li class="menu-item">
            <a href="{{ route('admingen.filieres.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-book-alt"></i>
              <div data-i18n="Basic">Filières</div>
            </a>
          </li>

          <!-- Etudiants -->
          <li class="menu-item">
            <a href="{{ route('admingen.students.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-group"></i>
              <div data-i18n="Basic">Etudiants</div>
            </a>
          </li>

          <!-- Assignations -->
          <li class="menu-item">
            <a href="{{ route('admingen.assignations.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-task"></i>
              <div data-i18n="Basic">Assignations</div>
            </a>
          </li>

          <!-- Matières -->
          <li class="menu-item">
            <a href="{{ route('admingen.subjects.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-book"></i>
              <div data-i18n="Basic">Matières</div>
            </a>
          </li>

          <!-- Formateurs -->
          <li class="menu-item">
            <a href="{{ route('admingen.formateurs.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-user-voice"></i> <!-- Icône modifiée ici -->
              <div data-i18n="Basic">Formateurs</div>
            </a>
          </li>

          <!-- Programmes -->
          <li class="menu-item">
            <a href="{{ route('admingen.programmes.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-time-five"></i>
              <div data-i18n="Basic">Programmes</div>
            </a>
          </li>

          <!-- Présence -->
          <li class="menu-item">
            <a href="{{ route('admingen.attendance.overview') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-check-circle"></i>
              <div data-i18n="Basic">Présence</div>
            </a>
          </li>

          <!-- Déconnexion -->
          <li class="menu-item logout-menu-item">
            <a href="{{ route('logout') }}" class="menu-link"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="menu-icon tf-icons bx bx-power-off"></i>
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
            <!-- Search Bar -->
            <!--   <div class="navbar-nav align-items-center me-3">
              <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search fs-4 lh-0 me-2"></i>
                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..."
                  aria-label="Search..." />
              </div>
            </div>   -->

            <!-- Navbar Items -->
            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- Notification Icon -->
              <li class="nav-item pe-2">
                <a class="nav-link text-dark fw-bold fs-3" href="" title="Modifier mon profil">
                  <i class="bx bx-user-circle"></i>
                </a>
              </li>
              

              <!-- User Profile (sans dropdown) -->
              <li class="nav-item pe-2">
                <span class="user-name">{{ Auth::user()->name }}</span>
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


  <!-- Core JS -->
  <script src="{{ asset('admin/assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/js/menu.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
    // Gestion de la visibilité des mots de passe
    document.querySelectorAll('.toggle-password').forEach(button => {
      button.addEventListener('click', function () {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = this.querySelector('i');

        if (input.type === 'password') {
          input.type = 'text';
          icon.classList.remove('bi-eye');
          icon.classList.add('bi-eye-slash');
        } else {
          input.type = 'password';
          icon.classList.remove('bi-eye-slash');
          icon.classList.add('bi-eye');
        }
      });
    });
  </script>
  <script>
    // Gestion de l'affichage du champ de fin de récurrence
    document.getElementById('recurrenceSelect').addEventListener('change', function () {
      const endRecurrence = document.getElementById('endRecurrenceContainer');
      endRecurrence.style.display = this.value !== 'ponctuel' ? 'block' : 'none';
    });

    // Alternance entre matière existante et intitulé personnalisé
    document.getElementById('subjectSelect').addEventListener('change', function () {
      if (this.value) document.getElementById('customTitle').value = '';
    });
    document.getElementById('customTitle').addEventListener('input', function () {
      if (this.value) document.getElementById('subjectSelect').value = '';
    });
  </script>
  <script>
    // Script pour l'affichage mobile
    document.addEventListener('DOMContentLoaded', function () {
      // Ajout des labels pour l'affichage mobile
      if (window.innerWidth < 768) {
        const headers = ['Date', 'Horaire', 'Matière', 'Formateur', 'Filière', 'Actions'];
        document.querySelectorAll('tbody tr td').forEach((td, index) => {
          td.setAttribute('data-label', headers[index % headers.length]);
        });
      }
    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Basculer entre notes et bulletin
      document.getElementById('showGradesBtn').addEventListener('click', function () {
        document.getElementById('gradesSection').style.display = 'block';
        document.getElementById('bulletinSection').style.display = 'none';
        this.classList.remove('btn-outline-dark');
        this.classList.add('btn-dark');
        document.getElementById('showBulletinBtn').classList.remove('btn-dark');
        document.getElementById('showBulletinBtn').classList.add('btn-outline-dark');
      });

      document.getElementById('showBulletinBtn').addEventListener('click', function () {
        document.getElementById('gradesSection').style.display = 'none';
        document.getElementById('bulletinSection').style.display = 'block';
        this.classList.remove('btn-outline-dark');
        this.classList.add('btn-dark');
        document.getElementById('showGradesBtn').classList.remove('btn-dark');
        document.getElementById('showGradesBtn').classList.add('btn-outline-dark');
      });

      // Changer de trimestre pour le bulletin
      document.querySelectorAll('.change-term').forEach(btn => {
        btn.addEventListener('click', function () {
          const term = this.getAttribute('data-term');
          // Recharger les données du bulletin pour le trimestre sélectionné
          window.location.href = window.location.pathname + "?term=" + term;
        });
      });

      // Au chargement, si un trimestre est spécifié dans l'URL, afficher le bulletin
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.has('term')) {
        document.getElementById('showBulletinBtn').click();
      }
    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Modal des étudiants
      const modal = document.getElementById('studentsModal');
      if (modal) {
        modal.addEventListener('show.bs.modal', function (event) {
          const button = event.relatedTarget;
          const filiereName = button.getAttribute('data-filiere');
          const students = JSON.parse(button.getAttribute('data-students'));

          document.getElementById('filiereTitle').textContent = filiereName;

          const modalBody = document.getElementById('modalStudentsBody');
          modalBody.innerHTML = '';

          students.forEach((student, index) => {
            let statusClass, statusText, avatarBg;

            if (student.status === 1) {
              statusClass = 'success';
              statusText = 'Présent';
              avatarBg = 'bg-success-light text-success';
            } else if (student.status === 2) {
              statusClass = 'warning';
              statusText = 'Justifié';
              avatarBg = 'bg-warning-light text-warning';
            } else if (student.status === 0) {
              statusClass = 'danger';
              statusText = 'Absent';
              avatarBg = 'bg-danger-light text-danger';
            } else {
              statusClass = 'secondary';
              statusText = '-';
              avatarBg = 'bg-secondary-light text-secondary';
            }

            const formateur = student.formateur && student.formateur.trim() !== ''
              ? student.formateur
              : '-';

            const row = document.createElement('tr');
            row.style.animationDelay = `${index * 0.05}s`;
            row.innerHTML = `
                      <td class="ps-4">
                          <div class="d-flex align-items-center">
                              <div class="avatar avatar-sm ${avatarBg} rounded-circle me-2">
                                  <span>${student.nom?.charAt(0) || ''}</span>
                              </div>
                              <div>
                                  <h6 class="mb-0 text-sm font-weight-bold">${student.nom || ''}</h6>
                              </div>
                          </div>
                      </td>
                      <td>
                          <p class="text-sm text-muted mb-0">${student.matricule || ''}</p>
                      </td>
                      <td class="text-center">
                          <span class="badge bg-${statusClass} rounded-pill px-3 py-1">${statusText}</span>
                      </td>
                      <td class="pe-4">
                          <p class="text-sm text-muted mb-0">${formateur}</p>
                      </td>
                  `;
            modalBody.appendChild(row);
          });
        });
      }

      // Tooltips pour les formateurs tronqués
      const formateursLists = document.querySelectorAll('.formateurs-list');
      formateursLists.forEach(el => {
        if (el.scrollWidth > el.offsetWidth) {
          el.setAttribute('title', el.textContent);
          el.classList.add('text-truncate');
        }
      });
    });
  </script>

</body>

</html>