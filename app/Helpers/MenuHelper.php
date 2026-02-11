<?php

namespace App\Helpers;

class MenuHelper
{
    /**
     * Get menu berdasarkan role ID
     * Super Admin (ID 1) mendapat SEMUA menu
     */
    public static function getMenuByRole($roleId)
    {
        // ========== SUPER ADMIN: FULL ACCESS ==========
        if ($roleId == 1) {
            return [
                'dashboard' => [
                    'title' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'bi bi-speedometer2'
                ],
                'data_master' => [
                    'title' => 'Data Master',
                    'icon' => 'bi bi-folder',
                    'active_pattern' => 'fakultas*|prodi*|tahun-akademik*|rombel*|ruangan*',
                    'submenu' => [
                        ['title' => 'Fakultas', 'route' => 'fakultas.index', 'icon' => 'bi bi-bank'],
                        ['title' => 'Program Studi', 'route' => 'data-prodi.index', 'icon' => 'bi bi-journal-bookmark'],
                        ['title' => 'Tahun Akademik', 'route' => 'tahun-akademik.index', 'icon' => 'bi bi-calendar-event'],
                        ['title' => 'Rombel', 'route' => 'rombel.index', 'icon' => 'bi bi-people'],
                        ['title' => 'Ruangan', 'route' => 'ruangan.index', 'icon' => 'bi bi-door-open'],
                    ]
                ],
                'dosen' => [
                    'title' => 'Data Dosen',
                    'route' => 'dosen.index',
                    'icon' => 'bi bi-person-lines-fill'
                ],
                'mahasiswa' => [
                    'title' => 'Data Mahasiswa',
                    'route' => 'mahasiswa.index',
                    'icon' => 'bi bi-person-circle'
                ],
                'karyawan' => [
                    'title' => 'Data Karyawan',
                    'route' => 'karyawan.index',
                    'icon' => 'bi bi-person-badge-fill'
                ],
                'matakuliah' => [
                    'title' => 'Mata Kuliah',
                    'route' => 'matakuliah.index',
                    'icon' => 'bi bi-book'
                ],
                'jadwal' => [
                    'title' => 'Jadwal Kuliah',
                    'route' => 'jadwal.index',
                    'icon' => 'bi bi-journals'
                ],
                'krs' => [
                    'title' => 'Rencana Studi (KRS)',
                    'route' => 'krs.index',
                    'icon' => 'bi bi-file-earmark-text'
                ],
                'nilai' => [
                    'title' => 'Manajemen Nilai',
                    'route' => 'nilai.index',
                    'icon' => 'bi bi-graph-up'
                ],
                'keuangan' => [
                    'title' => 'Keuangan',
                    'icon' => 'bi bi-cash-stack',
                    'active_pattern' => 'komponen-biaya*|tagihan*|pembayaran*',
                    'submenu' => [
                        ['title' => 'Komponen Biaya', 'route' => 'komponen-biaya.index', 'icon' => 'bi bi-currency-dollar'],
                        ['title' => 'Tagihan Mahasiswa', 'route' => 'tagihan.index', 'icon' => 'bi bi-receipt'],
                        ['title' => 'Validasi Pembayaran', 'route' => 'pembayaran.index', 'icon' => 'bi bi-credit-card-2-front'],
                    ]
                ],
                'operasional' => [
                    'title' => 'Operasional',
                    'icon' => 'bi bi-building',
                    'active_pattern' => 'inventaris*|arsip*',
                    'submenu' => [
                        ['title' => 'Inventaris', 'route' => 'inventaris.index', 'icon' => 'bi bi-box-seam'],
                        ['title' => 'Arsip Dokumen', 'route' => 'arsip.index', 'icon' => 'bi bi-archive'],
                    ]
                ],
                'pengguna' => [
                    'title' => 'Master Pengguna',
                    'icon' => 'bi bi-people-fill',
                    'active_pattern' => 'master-pengguna*|master-role*|master-session*',
                    'submenu' => [
                        ['title' => 'Data Pengguna', 'route' => 'master-pengguna.index', 'icon' => 'bi bi-person-lines-fill'],
                        ['title' => 'Role & Permission', 'route' => 'master-role.index', 'icon' => 'bi bi-shield-check'],
                        ['title' => 'Login Session', 'route' => 'master-session.index', 'icon' => 'bi bi-clock-history'],
                    ]
                ],
                'laporan' => [
                    'title' => 'Laporan & Analitik',
                    'icon' => 'bi bi-graph-up-arrow',
                    'active_pattern' => 'laporan*',
                    'submenu' => [
                        ['title' => 'Dashboard Eksekutif', 'route' => 'laporan.eksekutif', 'icon' => 'bi bi-speedometer2'],
                        ['title' => 'Laporan Fakultas', 'route' => 'laporan.fakultas', 'icon' => 'bi bi-building'],
                        ['title' => 'Laporan Prodi', 'route' => 'laporan.prodi', 'icon' => 'bi bi-mortarboard'],
                    ]
                ],
            ];
        }

        // ========== ADMIN AKADEMIK (ID 2) ==========
        if ($roleId == 2) {
            return [
                'dashboard' => [
                    'title' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'bi bi-speedometer2'
                ],
                'data_master' => [
                    'title' => 'Data Master',
                    'icon' => 'bi bi-folder',
                    'active_pattern' => 'fakultas*|prodi*|tahun-akademik*|rombel*|ruangan*',
                    'submenu' => [
                        ['title' => 'Fakultas', 'route' => 'fakultas.index', 'icon' => 'bi bi-bank'],
                        ['title' => 'Program Studi', 'route' => 'data-prodi.index', 'icon' => 'bi bi-journal-bookmark'],
                        ['title' => 'Tahun Akademik', 'route' => 'tahun-akademik.index', 'icon' => 'bi bi-calendar-event'],
                        ['title' => 'Rombel', 'route' => 'rombel.index', 'icon' => 'bi bi-people'],
                        ['title' => 'Ruangan', 'route' => 'ruangan.index', 'icon' => 'bi bi-door-open'],
                    ]
                ],
                'dosen' => [
                    'title' => 'Data Dosen',
                    'route' => 'dosen.index',
                    'icon' => 'bi bi-person-lines-fill'
                ],
                'mahasiswa' => [
                    'title' => 'Data Mahasiswa',
                    'route' => 'mahasiswa.index',
                    'icon' => 'bi bi-person-circle'
                ],
                'matakuliah' => [
                    'title' => 'Mata Kuliah',
                    'route' => 'matakuliah.index',
                    'icon' => 'bi bi-book'
                ],
                'jadwal' => [
                    'title' => 'Jadwal Kuliah',
                    'route' => 'jadwal.index',
                    'icon' => 'bi bi-journals'
                ],
                'krs' => [
                    'title' => 'Rencana Studi (KRS)',
                    'route' => 'krs.index',
                    'icon' => 'bi bi-file-earmark-text'
                ],
                'nilai' => [
                    'title' => 'Monitoring Nilai',
                    'route' => 'nilai.index',
                    'icon' => 'bi bi-graph-up'
                ],
            ];
        }

        // ========== DOSEN (ID 7) ==========
        if ($roleId == 7) {
            return [
                'dashboard' => [
                    'title' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'bi bi-speedometer2'
                ],
                'jadwal' => [
                    'title' => 'Jadwal Mengajar',
                    'route' => 'jadwal.index',
                    'icon' => 'bi bi-journals'
                ],
                'nilai' => [
                    'title' => 'Input Nilai',
                    'route' => 'nilai.index',
                    'icon' => 'bi bi-pencil-square'
                ],
                'krs' => [
                    'title' => 'KRS Mahasiswa PA',
                    'route' => 'krs.index',
                    'icon' => 'bi bi-file-earmark-text'
                ],
            ];
        }

        // ========== KAPRODI (ID 6) ==========
        if ($roleId == 6) {
            return [
                'dashboard' => [
                    'title' => 'Dashboard Prodi',
                    'route' => 'dashboard',
                    'icon' => 'bi bi-speedometer2'
                ],
                'mahasiswa' => [
                    'title' => 'Data Mahasiswa',
                    'route' => 'mahasiswa.index',
                    'icon' => 'bi bi-person-circle'
                ],
                'matakuliah' => [
                    'title' => 'Mata Kuliah',
                    'route' => 'matakuliah.index',
                    'icon' => 'bi bi-book'
                ],
                'jadwal' => [
                    'title' => 'Jadwal Kuliah',
                    'route' => 'jadwal.index',
                    'icon' => 'bi bi-journals'
                ],
                'krs' => [
                    'title' => 'Approval KRS',
                    'route' => 'krs.index',
                    'icon' => 'bi bi-file-earmark-check'
                ],
                'nilai' => [
                    'title' => 'Monitoring Nilai',
                    'route' => 'nilai.index',
                    'icon' => 'bi bi-graph-up'
                ],
            ];
        }

        // ========== MAHASISWA (ID 8) ==========
        if ($roleId == 8) {
            return [
                'dashboard' => [
                    'title' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'bi bi-speedometer2'
                ],
                'profil' => [
                    'title' => 'Profil',
                    'icon' => 'bi bi-person',
                    'active_pattern' => 'profil*',
                    'submenu' => [
                        // UBAH SEMUA ROUTE DI BAWAH INI MENJADI profil.biodata
                        ['title' => 'Biodata', 'route' => 'profil.biodata', 'icon' => 'bi bi-person-badge'],
                        ['title' => 'Data Pendidikan', 'route' => 'profil.biodata', 'icon' => 'bi bi-mortarboard'],
                        ['title' => 'Data Orang Tua', 'route' => 'profil.biodata', 'icon' => 'bi bi-people'],
                        ['title' => 'Data Akademik', 'route' => 'profil.biodata', 'icon' => 'bi bi-journal-text'],
                        ['title' => 'Dokumen', 'route' => 'profil.biodata', 'icon' => 'bi bi-folder2-open'],
                    ]
                ],
                'nilai' => [
                    'title' => 'Nilai Akademik',
                    'route' => 'nilai.index',
                    'icon' => 'bi bi-trophy'
                ],
            ];
        }

        // Other roles remain the same...
        // (Bagian Keuangan, Rektor, Dekan, Karyawan tidak berubah)

        // Default: Dashboard only
        return [
            'dashboard' => [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'bi bi-speedometer2'
            ],
        ];
    }
}
