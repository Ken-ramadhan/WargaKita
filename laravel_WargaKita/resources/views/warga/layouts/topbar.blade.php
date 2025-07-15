<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <button class="btn btn-link d-md-none rounded-circle mr-3" data-toggle="modal"
            data-target="#mobileSidebarModal" aria-label="Toggle sidebar">
        <i class="fa fa-bars"></i>
    </button>

    {{-- Logika PHP Blade untuk Judul Halaman --}}
    @php
        $segment = request()->segment(2) ?? request()->segment(1);

        $judulHalaman = match ($segment) {
            'rw' => 'Dashboard',
            'warga' => 'Manajemen Warga',
            'pengumuman' => 'Pengumuman',
            'kartu_keluarga' => 'Kartu Keluarga',
            'rukun_tetangga' => 'Rukun Tetangga',
            'tagihan' => 'Tagihan',
            'iuran' => 'Iuran',
            'kategori_golongan' => 'Kategori Golongan',
            'pengeluaran' => 'Pengeluaran',
            'laporan_pengeluaran_bulanan' => 'Laporan Pengeluaran Bulanan',
            default => ucwords(str_replace('-', ' ', $segment)),
        };
    @endphp

    <h1 class="h3 mb-0 text-gray-800 mx-2 text-truncate">{{ $judulHalaman }}</h1>

    <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown no-arrow position-relative"> {{-- position-relative tetap di sini --}}
            {{-- Menggunakan data-bs-toggle agar konsisten dengan modal --}}
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                {{-- Nama pengguna selalu muncul, akan dipotong jika terlalu panjang di mobile --}}
                <span class="mr-3 text-gray-600 small user-name-display">{{ Auth::user()->nama }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <button type="button" class="btn btn-sm btn-warning dropdown-item" data-bs-toggle="modal"
                    data-bs-target="#modalUbahPassword">
                    <i class="fas fa-lock fa-sm fa-fw mr-2 text-gray-400"></i> Ubah Password
                </button>
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    </ul>

    <div class="modal fade" id="modalUbahPassword" tabindex="-1" aria-labelledby="modalUbahPasswordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('update.password') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalUbahPasswordLabel">
                            <i class="fas fa-key text-primary me-1"></i> Ubah Password
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-floating mb-3 position-relative">
                            <input type="password" name="current_password" class="form-control" id="current_password" placeholder="Password Lama" required>
                            <label for="current_password">
                                <i class="fas fa-lock me-2"></i>Password Lama
                            </label>
                            <button type="button"
                                class="toggle-password-btn position-absolute top-50 end-0 translate-middle-y me-3 btn btn-link p-0 text-decoration-none"
                                onclick="togglePassword('current_password', this)" aria-label="Toggle password visibility">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>

                        <div class="form-floating mb-3 position-relative">
                            <input type="password" name="password" class="form-control" id="password" placeholder="Password Baru" required minlength="6">
                            <label for="password">
                                <i class="fas fa-lock me-2"></i>Password Baru
                            </label>
                            <button type="button"
                                class="toggle-password-btn position-absolute top-50 end-0 translate-middle-y me-3 btn btn-link p-0 text-decoration-none"
                                onclick="togglePassword('password', this)" aria-label="Toggle password visibility">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>

                        <div class="form-floating mb-3 position-relative">
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Konfirmasi Password Baru" required>
                            <label for="password_confirmation">
                                <i class="fas fa-lock me-2"></i>Konfirmasi Password
                            </label>
                            <button type="button"
                                class="toggle-password-btn position-absolute top-50 end-0 translate-middle-y me-3 btn btn-link p-0 text-decoration-none"
                                onclick="togglePassword('password_confirmation', this)" aria-label="Toggle password visibility">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>

                        @if ($errors->hasAny(['current_password', 'password', 'password_confirmation']))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="password-error-alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert" id="password-success-alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script untuk menampilkan/menyembunyikan password dan menangani alert --}}
    <script>
        function togglePassword(id, button) {
            const input = document.getElementById(id);
            const icon = button.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const errorAlert = document.getElementById('password-error-alert');
            const successAlert = document.getElementById('password-success-alert');

            if (successAlert) {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(successAlert);
                    bsAlert.close();
                }, 5000); // Sembunyikan setelah 5 detik
            }

            // Tampilkan modal jika ada error (setelah refresh halaman karena validasi gagal)
            @if ($errors->hasAny(['current_password', 'password', 'password_confirmation']))
                var myModal = new bootstrap.Modal(document.getElementById('modalUbahPassword'));
                myModal.show();
            @endif
        });
    </script>

    <style>
        .toggle-password-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.25rem;
            color: #6c757d;
        }

        .toggle-password-btn:hover {
            color: #0d6efd;
        }

        /* ----- Aturan CSS untuk Desktop (Lebar Layar >= 768px) ----- */
        /* Pastikan elemen li induk memiliki posisi relatif */
        .navbar-nav .nav-item.dropdown {
            position: relative; /* Ini sudah ada di HTML, tapi dikonfirmasi di sini */
        }

        /* Atur ulang dropdown ke perilaku default Bootstrap di desktop */
        @media (min-width: 768px) {
            .navbar-nav .dropdown-menu {
                position: absolute; /* Default Bootstrap */
                top: 100%; /* Default Bootstrap */
                right: 0; /* Untuk dropdown-menu-right */
                left: auto; /* Untuk dropdown-menu-right */
                transform: none; /* Biarkan Popper.js yang mengaturnya jika ada */
                margin-top: 0.125rem; /* Margin default Bootstrap */
                padding: 0.5rem 0; /* Padding default Bootstrap */
                min-width: 10rem; /* min-width default Bootstrap (160px) */
                max-width: none; /* Hapus batasan max-width dari mobile */
                width: auto; /* Biarkan lebar otomatis */
                border-radius: 0.25rem; /* Default Bootstrap */
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); /* Default Bootstrap */
            }

            .navbar-nav .dropdown-menu .dropdown-item {
                text-align: left; /* Default Bootstrap */
                padding: 0.5rem 1rem; /* Default Bootstrap */
                white-space: nowrap; /* Default Bootstrap */
                overflow: visible; /* Biarkan overflow terlihat di desktop */
                text-overflow: clip; /* Biarkan teks utuh */
                font-size: 1rem; /* Ukuran font default Bootstrap */
            }

            /* Nama pengguna di desktop */
            .user-name-display {
                max-width: none; /* Hapus batasan max-width dari mobile */
                overflow: visible; /* Biarkan terlihat penuh */
                text-overflow: clip; /* Biarkan teks utuh */
                white-space: nowrap; /* Teks tidak patah baris */
                display: inline !important; /* Default display */
            }

            /* Judul halaman di desktop */
            .h3.text-truncate {
                max-width: none; /* Hapus batasan max-width dari mobile */
            }
        }

        /* ----- Aturan CSS untuk Mobile (Lebar Layar < 768px) ----- */
        @media (max-width: 767.98px) {
            .navbar-nav .dropdown-menu {
                /* Posisi: Pastikan menempel ke bawah dari elemen toggle */
                position: absolute !important;
                top: calc(100% + 5px) !important; /* Posisikan tepat di bawah, dengan sedikit spasi */
                left: auto !important;
                right: 0 !important; /* Rata kanan dari toggle */
                transform: none !important; /* Pastikan tidak ada transform yang mengganggu */
                margin: 0 !important; /* Hapus margin default yang mungkin ada */
                padding: 0.5rem 0; /* Padding vertikal di dalam dropdown */

                /* Ukuran dan Penampilan */
                min-width: 160px; /* Lebar minimum yang cukup */
                max-width: 200px; /* Lebar maksimum yang pas */
                width: auto; /* Sesuaikan dengan konten, tapi dibatasi min/max */

                border-radius: 0.35rem;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            }

            .navbar-nav .dropdown-menu .dropdown-item {
                text-align: left;
                padding: 0.75rem 1rem; /* Padding item agar mudah disentuh */
                white-space: nowrap; /* Pastikan teks tidak patah baris */
                overflow: hidden; /* Sembunyikan jika ada overflow */
                text-overflow: ellipsis; /* Tampilkan elipsis jika terpotong */
                font-size: 0.9rem; /* Ukuran font sedikit lebih kecil */
            }

            /* Pastikan nama pengguna tidak terlalu panjang */
            .user-name-display {
                max-width: 80px; /* Batasi lebar nama pengguna di mobile */
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                display: inline-block !important;
            }

            /* Penyesuaian judul halaman */
            .h3.text-truncate {
                max-width: calc(100% - 130px); /* Beri ruang lebih di topbar */
            }
        }

        /* Penyesuaian khusus untuk layar sangat kecil (misal: lebar < 400px) */
        @media (max-width: 400px) {
            .navbar-nav .dropdown-menu {
                min-width: unset;
                width: 90%;
                left: 5% !important; /* Pusatkan */
                right: auto !important;
            }
            .user-name-display {
                max-width: 60px;
            }
             .h3.text-truncate {
                max-width: calc(100% - 110px);
            }
        }
    </style>

</nav>