<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
  data-assets-path="{{ asset('admin/') }}" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>Formateurs Dashboard</title>

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

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{ asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
  <link rel="stylesheet" href="{{ asset('admin/assets/vendor/libs/apex-charts/apex-charts.css') }}" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">


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

    .table td.moy-interro,
    .table td.moy-continue,
    .table td.moy-finale {
      background-color: #f8f9fa !important;
      min-width: 100px;
    }

    .display-value {
      font-size: 1.1rem;
      min-width: 50px;
      display: inline-block;
    }

    .bulletin {
      border: 1px solid #e2e8f0;
      background: linear-gradient(to bottom, #ffffff, #f8fafc);
    }

    .bulletin-header {
      background-color: #1e40af;
      color: white;
      padding: 1rem;
      border-radius: 0.5rem 0.5rem 0 0;
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

    .bulletin-container {
      max-width: 800px;
      margin: 0 auto;
      border: 1px solid #e2e8f0;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    /* Styles spécifiques pour les présences */
    .btn-group-toggle .btn {
      border: 1px solid transparent;
      transition: all 0.3s ease;
    }

    .btn-group-toggle .btn-outline-success {
      color: #28a745;
      border-color: #28a745;
    }

    .btn-group-toggle .btn-outline-warning {
      color: #ffc107;
      border-color: #ffc107;
    }

    .btn-group-toggle .btn-outline-danger {
      color: #dc3545;
      border-color: #dc3545;
    }

    .btn-group-toggle .btn-success {
      background-color: #28a745;
      color: white;
    }

    .btn-group-toggle .btn-warning {
      background-color: #ffc107;
      color: white;
    }

    .btn-group-toggle .btn-danger {
      background-color: #dc3545;
      color: white;
    }

    .btn-presence-present {
      color: #28a745;
      border-color: #28a745;
      background-color: transparent;
    }

    /* Au survol pour TOUS les boutons */
    .btn-presence-present:hover {
      background-color: rgba(40, 167, 69, 0.08);
    }

    /* Quand sélectionné/enregistré - version générique */
    .btn-check:checked+.btn-presence-present,
    input[name^="attendances["][value="1"]:checked+.btn-presence-present {
      background-color: #28a745 !important;
      border-color: #28a745 !important;
      color: white !important;
    }

    /* Empêcher la modification manuelle de la date */
    input[type="date"]::-webkit-calendar-picker-indicator {
      opacity: 1;
      cursor: pointer;
    }

    input[type="date"]::-webkit-inner-spin-button {
      display: none;
    }

    input[type="date"]::-webkit-clear-button {
      display: none;
    }

    /* Pour le sélecteur de date */
    #date {
      max-width: 200px;
    }

    .avatar {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 32px;
      height: 32px;
    }

    .btn-check:checked+.btn-outline-success {
      background-color: var(--bs-success);
      color: white !important;
    }

    .btn-check:checked+.btn-outline-warning {
      background-color: var(--bs-warning);
      color: white !important;
    }

    .btn-check:checked+.btn-outline-danger {
      background-color: var(--bs-danger);
      color: white !important;
    }

    @media (max-width: 576px) {
      .table-responsive {
        font-size: 0.875rem;
      }

      .btn-group .btn {
        padding: 0.25rem;
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
          <a href="{{ route('formateur.dashboard') }}" class="app-brand-link">
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
            <a href="{{ route('formateur.dashboard') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-home-circle"></i>
              <div data-i18n="Analytics">Tableau de bord</div>
            </a>
          </li>

          <!-- Etudiants -->
          <li class="menu-item">
            <a href="{{ route('formateur.students.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-group"></i>
              <div data-i18n="Basic">Etudiants</div>
            </a>
          </li>

          <!-- Gestion de notes -->
          <li class="menu-item">
            <a href="{{ route('formateur.grades.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-clipboard"></i>
              <div data-i18n="Basic">Gestion de notes</div>
            </a>
          </li>

          <!-- Présence -->
          <li class="menu-item">
            <a href="{{ route('formateur.attendance.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-check-circle"></i>
              <div data-i18n="Basic">Présence</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{ route('formateur.programmes.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-clipboard"></i>
              <div data-i18n="Basic">Programmes</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{ route('formateur.cours.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-download"></i>
              <div data-i18n="Basic">Fichiers</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{ route('formateur.videos.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-video"></i>
              <div data-i18n="Basic">Vidéos</div>
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

        <nav
          class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
          id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="bx bx-menu bx-sm"></i>
            </a>
          </div>
        </nav>


        <div class="content-wrapper" style="background: #fff;">
          <div class="content-backdrop fade"></div>
          @yield('content')
        </div>
        <!-- Content wrapper -->
      </div>
      <!--  -->
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script>
    // Configuration PDF
    const {
      jsPDF
    } = window.jspdf;

    document.getElementById('export-pdf').addEventListener('click', function() {
      const btn = this;
      const originalContent = btn.innerHTML;

      // Désactiver temporairement le bouton
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-file-pdf mr-1"></i> <span class="d-none d-md-inline">Génération...</span>';

      // Options pour la capture
      const options = {
        scale: 2,
        useCORS: true,
        scrollX: 0,
        scrollY: 0,
        windowWidth: document.getElementById('pdf-content').scrollWidth,
        windowHeight: document.getElementById('pdf-content').scrollHeight,
        logging: false
      };

      // Capture du contenu
      html2canvas(document.getElementById('pdf-content'), options).then(canvas => {
        // Création du PDF en paysage pour plus d'espace
        const pdf = new jsPDF('l', 'mm', 'a4');
        const imgData = canvas.toDataURL('image/png');
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

        // Ajout de l'image au PDF
        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);

        // Téléchargement automatique
        pdf.save('Emploi-du-temps-' + new Date().toLocaleDateString('fr-FR') + '.pdf');

      }).catch(error => {
        console.error("Erreur lors de la génération du PDF:", error);
        alert("Une erreur est survenue lors de la génération du PDF");
      }).finally(() => {
        // Réactiver le bouton
        btn.disabled = false;
        btn.innerHTML = originalContent;
      });
    });

    // Adaptation responsive
    function handleResponsive() {
      if (window.innerWidth < 768) {
        document.querySelectorAll('.table-responsive-lg').forEach(el => {
          el.classList.add('mobile-scroll');
        });
      } else {
        document.querySelectorAll('.table-responsive-lg').forEach(el => {
          el.classList.remove('mobile-scroll');
        });
      }
    }

    // Initialisation et écouteur de redimensionnement
    document.addEventListener('DOMContentLoaded', handleResponsive);
    window.addEventListener('resize', handleResponsive);
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialisation des tooltips
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    });

    function confirmDelete(formId) {
      const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
      modal.show();

      document.getElementById('confirmDeleteBtn').onclick = function() {
        document.getElementById(formId).submit();
      };
    }
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const toggler = document.querySelector('.layout-menu-toggle');
      const menu = document.getElementById('layout-menu');

      if (toggler && menu) {
        toggler.addEventListener('click', function() {
          menu.classList.toggle('d-block');
        });
      }
    });
  </script>
  <script>
    $(document).ready(function() {
      // Animation lors du changement d'onglet
      $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        $(e.target).addClass('animate__animated animate__fadeIn');
        setTimeout(() => {
          $(e.target).removeClass('animate__animated animate__fadeIn');
        }, 300);
      });

      // Formatage des nombres dans les tableaux
      $('table').on('draw.dt', function() {
        $('td').each(function() {
          const val = parseFloat($(this).text());
          if (!isNaN(val)) {
            $(this).text(val.toFixed(2));
          }
        });
      });
    });
  </script>
  <script>
    // Adaptation mobile
    document.addEventListener('DOMContentLoaded', function() {
      if (window.innerWidth < 768) {
        document.querySelectorAll('tbody tr td').forEach((td, index) => {
          const labels = ['Date', 'Horaire', 'Matière', 'Formateur', 'Salle'];
          td.setAttribute('data-label', labels[index]);
        });
      }
    });
  </script>

  <script>
    function setStatus(btn, studentId, status) {
      // Reset les boutons du groupe
      const group = btn.closest('.btn-group');
      group.querySelectorAll('.btn').forEach(b => {
        b.classList.replace('btn-success', 'btn-outline-success');
        b.classList.replace('btn-warning', 'btn-outline-warning');
        b.classList.replace('btn-danger', 'btn-outline-danger');
      });

      // Active le bouton cliqué
      if (status == 1) {
        btn.classList.replace('btn-outline-success', 'btn-success');
      } else if (status == 2) {
        btn.classList.replace('btn-outline-warning', 'btn-warning');
      } else {
        btn.classList.replace('btn-outline-danger', 'btn-danger');
      }

      // Met à jour la valeur cachée
      document.getElementById('status-' + studentId).value = status;
    }
  </script>


</body>

</html>