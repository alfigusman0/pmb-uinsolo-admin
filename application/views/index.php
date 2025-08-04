<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="<?= base_url() ?>assets/" data-template="vertical-menu-template-starter">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title><?= $title ?></title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="<?= base_url() ?>assets/img/logo-admisi2.png" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

  <!-- Icons. Uncomment required icon fonts -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/boxicons.css" />

  <!-- <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/fontawesome.css" /> -->
  <!-- <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/flag-icons.css" /> -->

  <!-- Core CSS -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/rtl/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/rtl/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="<?= base_url() ?>assets/libs/typeahead-js/typeahead.css" />
  <link rel="stylesheet" href="<?= base_url() ?>assets/libs/select2/select2.css" />
  <link rel="stylesheet" href="<?= base_url() ?>assets/libs/tagify/tagify.css" />
  <link rel="stylesheet" href="<?= base_url() ?>assets/libs/bootstrap-select/bootstrap-select.css" />
  <link rel="stylesheet" href="<?= base_url() ?>assets/libs/typeahead-js/typeahead.css" />

  <!-- Page CSS -->
  <?php $this->load->view($css); ?>

  <!-- Helpers -->
  <script src="<?= base_url() ?>assets/js/helpers.js"></script>

  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
  <script src="<?= base_url() ?>assets/js/template-customizer.js"></script>
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="<?= base_url() ?>assets/js/config.js"></script>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-MG0QF37HNX"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-MG0QF37HNX');
  </script>
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->

      <?php $this->load->view('layout/sidebar'); ?>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->

        <?php $this->load->view('layout/navbar'); ?>

        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->

          <?php $this->load->view($content); ?>
          <!-- / Content -->

          <!-- Footer -->
          <?php $this->load->view('layout/footer'); ?>
          <!-- / Footer -->

          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
  </div>
  <!-- / Layout wrapper -->

  <?php $this->load->view($modal); ?>

  <!-- Core JS -->
  <!-- build:jsassets/js/core.js -->
  <script src="<?= base_url() ?>assets/libs/jquery/jquery.js"></script>
  <script src="<?= base_url() ?>assets/libs/popper/popper.js"></script>
  <script src="<?= base_url() ?>assets/js/bootstrap.js"></script>
  <script src="<?= base_url() ?>assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

  <script src="<?= base_url() ?>assets/libs/hammer/hammer.js"></script>

  <script src="<?= base_url() ?>assets/js/menu.js"></script>
  <!-- endbuild -->

  <!-- Vendors JS -->
  <script src="<?= base_url() ?>assets/libs/select2/select2.js"></script>
  <script src="<?= base_url() ?>assets/libs/tagify/tagify.js"></script>
  <script src="<?= base_url() ?>assets/libs/bootstrap-select/bootstrap-select.js"></script>
  <script src="<?= base_url() ?>assets/libs/typeahead-js/typeahead.js"></script>
  <script src="<?= base_url() ?>assets/libs/bloodhound/bloodhound.js"></script>

  <!-- Main JS -->
  <script src="<?= base_url() ?>assets/js/main.js"></script>

  <!-- Page JS -->
  <?php $this->load->view($javascript); ?>

  <script>
    const select2 = $('.select2');
    $(document).ready(function(){
      if (select2.length) {
        select2.each(function () {
          var $this = $(this);
          $this.wrap('<div class="position-relative"></div>').select2({
            placeholder: '-- Pilih --',
            dropdownParent: $this.parent()
          });
        });
      }
    })
  </script>
</body>

</html>