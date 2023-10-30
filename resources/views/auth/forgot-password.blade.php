<!DOCTYPE html>
<html lang="en" class="light-style layout-wide  customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('sneat') }}/assets/" data-template="vertical-menu-template">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Lupa Password - Antree</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('adminlte') }}/dist/img/antree-150x150.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/libs/typeahead-js/typeahead.css" />
    <!-- Vendor -->
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/libs/@form-validation/umd/styles/index.min.css" />
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/libs/spinkit/spinkit.css">

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/pages/page-auth.css">

    <!-- Helpers -->
    <script src="{{ asset('sneat') }}/assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ asset('sneat') }}/assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('sneat') }}/assets/js/config.js"></script>

</head>

<body>


  <!-- ?PROD Only: Google Tag Manager (noscript) (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0" style="display: none; visibility: hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

  <!-- Content -->

<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">

      <!-- Forgot Password -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center">
            <a href="index.html" class="app-brand-link gap-2">
              <span class="app-brand-text demo text-body fw-bold">Antree</span>
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-2">Lupa Password? 🔒</h4>
          <p class="mb-4">Masukkan email yang terdaftar pada akun kamu!</p>
          <form id="formAuthentication" class="mb-3" action="{{ route('password.email') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
            @endif

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="text" class="form-control" id="email" name="email" placeholder="Ketik email.." autocomplete="off" autofocus>
            </div>
            <button type="submit" id="btnSubmit" class="btn btn-primary w-100">Reset Password</button>
          </form>
          <div class="text-center">
            <a href="{{ route('auth.login') }}" class="d-flex align-items-center justify-content-center">
              <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
              Kembali ke Login
            </a>
          </div>
        </div>
      </div>
      <!-- /Forgot Password -->
    </div>
    </div>
  </div>

<!-- / Content -->

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->

  <script src="{{ asset('sneat') }}/assets/vendor/libs/jquery/jquery.js"></script>
  <script src="{{ asset('sneat') }}/assets/vendor/libs/popper/popper.js"></script>
  <script src="{{ asset('sneat') }}/assets/vendor/js/bootstrap.js"></script>
  <script src="{{ asset('sneat') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="{{ asset('sneat') }}/assets/vendor/libs/hammer/hammer.js"></script>
  <script src="{{ asset('sneat') }}/assets/vendor/libs/i18n/i18n.js"></script>
  <script src="{{ asset('sneat') }}/assets/vendor/libs/typeahead-js/typeahead.js"></script>
  <script src="{{ asset('sneat') }}/assets/vendor/js/menu.js"></script>

  <!-- endbuild -->

  <!-- Vendors JS -->
  <script src="{{ asset('sneat') }}/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js"></script>
  <script src="{{ asset('sneat') }}/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js"></script>
  <script src="{{ asset('sneat') }}/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js"></script>

  <!-- Main JS -->
  <script src="{{ asset('sneat') }}/assets/js/main.js"></script>


  <!-- Page JS -->
  <script src="{{ asset('sneat') }}/assets/js/pages-auth.js"></script>

    <!-- Custom JS -->
    <script>
        //Show loader when form is submitted, and disable submit button
        $(document).ready(function() {
            $('#btnSubmit').click(function() {
                $(this).attr('disabled', true);
                $(this).html('Loading...');
            });
        });
    </script>
</body>

</html>

<!-- beautify ignore:end -->
