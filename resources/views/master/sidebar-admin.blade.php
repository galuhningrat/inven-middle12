@php

// Cek apakah salah satu submenu aktif
$isMenuOpen = request()->is('data*')
|| request()->is('fakultas*')
|| request()->is('prodi*')
|| request()->is('tahun-akademik*')
|| request()->is('rombel*')
|| request()->is(patterns:'ruangan');

@endphp

<!-- Data Master -->
<div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $isMenuOpen ? 'hover show' : '' }}">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="bi bi-folder fs-3"></i>
                    </span>
                    <span class="menu-title">Data Master</span>
                    <span class="menu-arrow"></span>
                </span>
    <div class="menu-sub menu-sub-accordion menu-active-bg">
        <div class="menu-item">
            <a class="menu-link {{ request()->is('fakultas*') ? 'active' : '' }}" href="/fakultas">
                            <span class="menu-bullet">
                                <span class="bi bi-bank"></span>
                            </span>
                <span class="menu-title">Fakultas</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link {{ request()->is('prodi*') ? 'active' : '' }}" href="/prodi">
                            <span class="menu-bullet">
                                <span class="bi bi-journal-bookmark"></span>
                            </span>
                <span class="menu-title">Program Studi</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link {{ request()->is('tahun-akademik*') ? 'active' : '' }}" href="/tahun-akademik">
                            <span class="menu-bullet">
                                <span class="bi bi-calendar"></span>
                            </span>
                <span class="menu-title">Tahun Akademik</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link {{ request()->is('rombel*') ? 'active' : '' }}" href="/rombel">
                            <span class="menu-bullet">
                                <span class="bi bi-people"></span>
                            </span>
                <span class="menu-title">Rombel</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link {{ request()->is('ruangan*') ? 'active' : '' }}" href="/ruangan">
                            <span class="menu-bullet">
                                <span class="bi-door-open"></span>
                            </span>
                <span class="menu-title">Ruangan</span>
            </a>
        </div>
    </div>
</div>

<!-- Data Dosen -->
<div class="menu-item">
    <a class="menu-link {{ request()->is('dosen*') ? 'active' : '' }}" href="/dosen">
                    <span class="menu-icon">
                        <i class="bi bi-person-lines-fill fs-3"></i>
                    </span>
        <span class="menu-title">Data Dosen</span>
    </a>
</div>

<!-- Data Mahasiswa -->
<div class="menu-item">
    <a class="menu-link {{ request()->is('mahasiswa*') ? 'active' : '' }}" href="/mahasiswa">
                    <span class="menu-icon">
                        <i class="bi bi-person-circle fs-3"></i>
                    </span>
        <span class="menu-title">Data Mahasiswa</span>
    </a>
</div>

<!-- Data Karyawan -->
<div class="menu-item">
    <a class="menu-link {{ request()->is('karyawan*') ? 'active' : '' }}" href="/karyawan">
                    <span class="menu-icon">
                        <i class="bi bi-person-badge-fill fs-3"></i>
                    </span>
        <span class="menu-title">Data Karyawan</span>
    </a>
</div>

<!-- Kata Kuliah -->
<div class="menu-item">
    <a class="menu-link {{ request()->is('matakuliah') ? 'active' : '' }}" href="/matakuliah">
                    <span class="menu-icon">
                        <i class="bi bi-book fs-3"></i>
                    </span>
        <span class="menu-title">Data Mata Kuliah</span>
    </a>
</div>

<!-- Jadwal Kuliah -->
<div class="menu-item">
    <a class="menu-link {{ request()->is('jadwal') ? 'active' : '' }}" href="/jadwal">
                    <span class="menu-icon">
                        <i class="bi bi-journals fs-3"></i>
                    </span>
        <span class="menu-title">Data Jadwal Kuliah</span>
    </a>
</div>

<!-- KRS -->
<div class="menu-item">
    <a class="menu-link {{ request()->is('krs') ? 'active' : '' }}" href="/krs">
                    <span class="menu-icon">
                        <i class="bi bi-file-earmark-text fs-3"></i>
                    </span>
        <span class="menu-title">Rencana Studi</span>
    </a>
</div>

<!-- Nilai -->
<div class="menu-item">
    <a class="menu-link {{ request()->is('nilai') ? 'active' : '' }}" href="/nilai">
                    <span class="menu-icon">
                        <i class="bi bi-graph-up fs-3"></i>
                    </span>
        <span class="menu-title">Nilai</span>
    </a>
</div>

<!-- Data Master -->
<div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('master*') ? 'hover show' : '' }}">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="bi bi-people-fill fs-3"></i>
                    </span>
                    <span class="menu-title">Master Pengguna</span>
                    <span class="menu-arrow"></span>
                </span>
    <div class="menu-sub menu-sub-accordion menu-active-bg">
        <div class="menu-item">
            <a class="menu-link {{ request()->is('master-pengguna*') ? 'active' : '' }}" href="/master-pengguna">
                            <span class="menu-bullet">
                                <span class="bi bi-circle"></span>
                            </span>
                <span class="menu-title">Data Pengguna</span>
            </a>
            <a class="menu-link {{ request()->is('master-role*') ? 'active' : '' }}" href="/master-role">
                            <span class="menu-bullet">
                                <span class="bi bi-circle"></span>
                            </span>
                <span class="menu-title">Role</span>
            </a>
            <a class="menu-link {{ request()->is('master-session*') ? 'active' : '' }}" href="/master-session">
                            <span class="menu-bullet">
                                <span class="bi bi-circle"></span>
                            </span>
                <span class="menu-title">Session</span>
            </a>
        </div>
    </div>
</div>
