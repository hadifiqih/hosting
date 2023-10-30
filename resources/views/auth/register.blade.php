<!DOCTYPE html>

<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('sneat') }}/assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar Akun - Antree</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('adminlte') }}/dist/img/antree-150x150.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="{{ asset('sneat') }}/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('sneat') }}/assets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register Card -->
          <div class="card my-2">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="index.html" class="app-brand-link gap-2">
                  <span class="app-brand-text demo text-body fw-bolder">Antree</span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-2">Daftar Akun Antree 🚀</h4>
              <p class="mb-4">Silahkan isi form dibawah guys !</p>

              <form id="formAuthentication" class="mb-3" action="{{ route('auth.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Menampilkan error dari validator --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                  <label for="name" class="form-label">Nama Lengkap</label>
                  <input
                    type="text"
                    class="form-control"
                    id="name"
                    name="nama"
                    placeholder="Masukkan nama lengkap"
                    autofocus
                    required
                  />
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan email" autocomplete="off" required/>
                </div>
                {{-- Input Nomor Telepon --}}
                <div class="mb-3">
                    <label for="telepon" class="form-label">Nomor Telepon</label>
                    <input
                      type="text"
                      class="form-control"
                      id="telepon"
                      name="telepon"
                      placeholder="Masukkan nomor telepon"
                      autofocus
                      required
                    />
                  </div>
                {{-- Input Alamat --}}
                <div class="mb-3 form-password-toggle">
                  <label class="form-label" for="password">Password</label>
                  <div class="input-group input-group-merge">
                    <input type="password" id="password" class="form-control" name="password" placeholder="Masukkan password" aria-describedby="password" required/>
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                  <div class="invalid-feedback" id="passwordError">Password minimal 8 karakter</div>
                </div>

                <div class="mb-3">
                    <label for="tahun" class="form-label">Tahun Masuk</label>
                    <input
                      type="text"
                      class="form-control"
                      id="tahun"
                      name="tahunMasuk"
                      placeholder="Tahun Masuk Kerja"
                      required/>
                  </div>

                <div class="mb-3">
                    <label for="divisi" class="form-label">Divisi/Jabatan</label>
                    <select id="divisi" class="form-select" name="divisi" required>
                        <option value="" selected disabled>-- Pilih Divisi --</option>
                        <option value="manajemen">Manajemen</option>
                        <option value="produksi">Produksi</option>
                        <option value="sales">Pemasaran & Penjualan</option>
                        <option value="desain">Desain Grafis</option>
                        <option value="keuangan">Keuangan & Administrasi</option>
                        <option value="logistik">Logistik & Pengiriman</option>
                    </select>
                </div>

                <div class="mb-3" id="inputSales" style="display: none;">
                  <label for="inputSalesField" class="form-label">Brand</label>
                  <select id="inputSalesField" class="form-select" name="salesApa">
                    <option value="" selected disabled>-- Pilih --</option>
                    @foreach ($sales as $item)
                      <option value="{{ $item->id }}">{{ $item->sales_name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="mb-3" id="inputRoleManajemen" style="display: none">
                    <label for="roleManajemen">Jabatan</label>
                    <select class="form-select" name="roleManajemen" id="roleManajemen">
                        <option value="" selected disabled>-- Pilih --</option>
                        <option value="ceo">CEO</option>
                        <option value="direktur">Direktur</option>
                        <option value="manager">Manager</option>
                        <option value="hrd">HRD</option>
                    </select>
                </div>

                <div class="mb-3" id="inputRoleProduksi" style="display: none">
                    <label for="roleProduksi" class="form-label">Jabatan</label>
                    <select id="roleProduksi" class="form-select" name="roleProduksi">
                        <option value="" selected disabled>-- Pilih --</option>
                        <option value="estimator">Estimator / Supervisor / Kepala Cabang</option>
                        <option value="stempel">Staff Stempel</option>
                        <option value="advertising">Staff Advertising</option>
                        <option value="admin">Admin Workshop</option>
                        <option value="dokumentasi">Staff Dokumentasi</option>
                    </select>
                </div>
                <div class="mb-3" id="inputRoleSales" style="display: none">
                    <label for="roleSales" class="form-label">Jabatan</label>
                    <select id="roleSales" class="form-select" name="roleSales">
                        <option value="" selected disabled>-- Pilih --</option>
                        <option value="sales">Supervisor</option>
                        <option value="sales">Sales</option>
                    </select>
                </div>
                <div class="mb-3" id="inputRoleDesain" style="display: none">
                    <label for="roleDesain" class="form-label">Jabatan</label>
                    <select id="roleDesain" class="form-select" name="roleDesain">
                        <option value="" selected disabled>-- Pilih --</option>
                        <option value="desain">Supervisor</option>
                        <option value="desain">Staff Desain</option>
                    </select>
                </div>
                <div class="mb-3" id="inputRoleKeuangan" style="display: none">
                    <label for="roleKeuangan" class="form-label">Jabatan</label>
                    <select id="roleKeuangan" class="form-select" name="roleKeuangan">
                        <option value="" selected disabled>-- Pilih --</option>
                        <option value="staffAdmin">Staff Admin</option>
                    </select>
                </div>
                <div class="mb-3" id="inputRoleLogistik" style="display: none">
                    <label for="roleLogistik" class="form-label">Jabatan</label>
                    <select id="roleLogistik" class="form-select" name="roleLogistik">
                        <option value="" selected disabled>-- Pilih --</option>
                        <option value="staffGudang">Staff Gudang / Logistik</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="lokasi" class="form-label">Tempat Kerja</label>
                    <select id="lokasi" class="form-select" name="lokasi" required>
                        <option selected disabled>-- Pilih Tempat Kerja --</option>
                        <option value="Malang">Malang</option>
                        <option value="Surabaya">Surabaya</option>
                        <option value="Kediri">Kediri</option>
                        <option value="Sidoarjo">Sidoarjo</option>
                      </select>
                </div>

                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" value="1" required/>
                    <label class="form-check-label" for="terms-conditions">
                      Saya mematuhi
                      <a href="javascript:void(0);">syarat & ketentuan</a>
                    </label>
                  </div>
                </div>
                <button id="btnDaftar" type="submit" class="btn btn-primary d-grid w-100" disabled>Daftar</button>
              </form>

              <p class="text-center">
                <span>Sudah punya akun?</span>
                <a href="{{ route('auth.login') }}">
                  <span>Masuk</span>
                </a>
              </p>
            </div>
          </div>
          <!-- Register Card -->
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
    <script src="{{ asset('sneat') }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ asset('sneat') }}/assets/js/main.js"></script>

    <!-- Page JS -->
    <script>
      $(document).ready(function() {
          // Ketika pilihan dalam dropdown berubah
          $("#divisi").change(function() {
              var selectedDivisi = $(this).val();

                //reset semua select ke default
                $('#roleProduksi').prop('selectedIndex',0);
                $('#roleSales').prop('selectedIndex',0);
                $('#roleDesain').prop('selectedIndex',0);
                $('#roleKeuangan').prop('selectedIndex',0);
                $('#roleLogistik').prop('selectedIndex',0);
                $('#roleManajemen').prop('selectedIndex',0);

              // Cek apakah pilihan yang dipilih adalah "Sales"
              if (selectedDivisi == "sales") {

                    $("#inputSales").show();
                    $("#inputRoleSales").show();
                    $("#inputRoleDesain").hide();
                    $("#inputRoleKeuangan").hide();
                    $("#inputRoleLogistik").hide();
                    $("#inputRoleProduksi").hide();
                    $("#inputRoleManajemen").hide();

              } else if (selectedDivisi == "desain") {
                    $("#inputSales").hide();
                    $("#inputRoleSales").hide();
                    $("#inputRoleDesain").show();
                    $("#inputRoleKeuangan").hide();
                    $("#inputRoleLogistik").hide();
                    $("#inputRoleProduksi").hide();
                    $("#inputRoleManajemen").hide();
              } else if (selectedDivisi == "keuangan") {
                    $("#inputSales").hide();
                    $("#inputRoleSales").hide();
                    $("#inputRoleDesain").hide();
                    $("#inputRoleKeuangan").show();
                    $("#inputRoleLogistik").hide();
                    $("#inputRoleProduksi").hide();
                    $("#inputRoleManajemen").hide();

              } else if (selectedDivisi == "logistik") {
                    $("#inputSales").hide();
                    $("#inputRoleSales").hide();
                    $("#inputRoleDesain").hide();
                    $("#inputRoleKeuangan").hide();
                    $("#inputRoleLogistik").show();
                    $("#inputRoleProduksi").hide();
                    $("#inputRoleManajemen").hide();

              } else if (selectedDivisi == "produksi") {
                    $("#inputSales").hide();
                    $("#inputRoleSales").hide();
                    $("#inputRoleDesain").hide();
                    $("#inputRoleKeuangan").hide();
                    $("#inputRoleLogistik").hide();
                    $("#inputRoleProduksi").show();
                    $("#inputRoleManajemen").hide();

              } else if (selectedDivisi == "manajemen") {
                    $("#inputSales").hide();
                    $("#inputRoleSales").hide();
                    $("#inputRoleDesain").hide();
                    $("#inputRoleKeuangan").hide();
                    $("#inputRoleLogistik").hide();
                    $("#inputRoleProduksi").hide();
                    $("#inputRoleManajemen").show();

              }
            });

          $("#password").keyup(function() {
            var password = $(this).val();
            var passwordError = $("#passwordError");

            if (password.length < 8) {
              passwordError.html("Password minimal 8 karakter");
            } else if (password.includes(" ")) {
              passwordError.html("Password tidak boleh mengandung spasi");
            } else {
              passwordError.html("");
            }
          });
          //Jika tombol checkbox dicentang maka tombol submit akan aktif
            $("#terms-conditions").click(function() {
                if ($(this).is(":checked")) {
                $("#btnDaftar").removeAttr("disabled");
                } else {
                $("#btnDaftar").attr("disabled", true);
                }
            });
      });
  </script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
