<script>
    // ========================================
    // Sidebar Toggle Functionality (All Resolutions)
    // ========================================
    document.addEventListener('DOMContentLoaded', function() {
        // Desktop Toggle Button
        const desktopToggle = document.getElementById('kt_aside_desktop_toggle');

        if (desktopToggle) {
            desktopToggle.addEventListener('click', function() {
                const body = document.body;
                const isMinimized = body.getAttribute('data-kt-aside-minimize') === 'on';

                if (isMinimized) {
                    body.removeAttribute('data-kt-aside-minimize');
                    localStorage.setItem('sidebar-state', 'expanded');
                } else {
                    body.setAttribute('data-kt-aside-minimize', 'on');
                    localStorage.setItem('sidebar-state', 'minimized');
                }
            });
        }

        // Existing aside toggle (in sidebar header)
        const asideToggle = document.getElementById('kt_aside_toggle');

        if (asideToggle) {
            asideToggle.addEventListener('click', function() {
                const body = document.body;
                const isMinimized = body.getAttribute('data-kt-aside-minimize') === 'on';

                if (isMinimized) {
                    body.removeAttribute('data-kt-aside-minimize');
                    localStorage.setItem('sidebar-state', 'expanded');
                } else {
                    body.setAttribute('data-kt-aside-minimize', 'on');
                    localStorage.setItem('sidebar-state', 'minimized');
                }
            });
        }

        // Restore sidebar state from localStorage
        const savedState = localStorage.getItem('sidebar-state');
        if (savedState === 'minimized' && window.innerWidth >= 992) {
            document.body.setAttribute('data-kt-aside-minimize', 'on');
        }

        // Add title attribute to menu links for tooltip
        document.querySelectorAll('.menu-link').forEach(link => {
            const titleElement = link.querySelector('.menu-title');
            if (titleElement && !link.hasAttribute('title')) {
                link.setAttribute('title', titleElement.textContent.trim());
            }
        });

        // Keyboard shortcut: Ctrl + B to toggle sidebar
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'b') {
                e.preventDefault();
                if (desktopToggle) {
                    desktopToggle.click();
                } else if (asideToggle) {
                    asideToggle.click();
                }
            }
        });
    });

    // ========================================
    // Delete Confirmation with SweetAlert2
    // ========================================
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-hapus').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data yang dihapus tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    buttonsStyling: false,
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-danger'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-hapus-' + id).submit();
                    }
                });
            });
        });
    });

    // ========================================
    // Edit Role Modal
    // ========================================
    $(document).ready(function() {
        $('.btn-edit-role').on('click', function() {
            let id = $(this).data('id');
            let url = $(this).data('url');

            $.get(url, function(data) {
                $('#form-edit-role').attr('action', `/master-role/${data.id}`);
                $('#form-edit-role input[name="nama_role"]').val(data.nama_role);
                $('#form-edit-role textarea[name="deskripsi_role"]').val(data.deskripsi_role);
                $('#ubah-role').modal('show');
            });
        });
    });

    // ========================================
    // DataTables Initialization
    // ========================================
    $(document).ready(function() {
        var table = $('#tabel-custom').DataTable({
            responsive: true,
            dom: '<"top"f>rt<"d-flex justify-content-between align-items-center flex-wrap px-4 pt-4"<"d-flex align-items-center gap-2"l><"d-flex align-items-center gap-3"ip>><"clear">',
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            buttons: [{
                    extend: 'copy',
                    text: 'Salin',
                    className: 'btn btn-sm btn-light-primary'
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    className: 'btn btn-sm btn-light-success'
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    className: 'btn btn-sm btn-light-danger'
                },
                {
                    extend: 'print',
                    text: 'Cetak',
                    className: 'btn btn-sm btn-light-dark'
                }
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
                infoEmpty: "Data tidak ditemukan",
                zeroRecords: "Tidak ada data yang cocok",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "›",
                    previous: "‹"
                }
            }
        });

        table.buttons().container().appendTo('#custom-button-container');
        $('#tabel-custom_filter').appendTo('#custom-search-container');
    });

    // ========================================
    // DataTables Without Pagination (tabel-custom-2)
    // ========================================
    $(function() {
        const table2 = $('#tabel-custom-2').DataTable({
            dom: '<"top"f>t',
            paging: false,
            info: false,
            lengthChange: false,
            language: {
                search: "Cari:",
                infoEmpty: "Data tidak ditemukan",
                zeroRecords: "Tidak ada data yang cocok"
            }
        });

        $('#tabel-custom-2_filter').appendTo('#custom-search-container-2');
    });

    // ========================================
    // Two Step Form (Mahasiswa/Karyawan)
    // ========================================
    $(document).ready(function() {
        $('#btn-lanjutkan').on('click', function() {
            let selectedUser = $('#select-user').val();

            if (!selectedUser) {
                alert('Silakan pilih akun pengguna terlebih dahulu.');
                return;
            }

            $('#step-2').slideDown();
            $('#btn-lanjutkan').hide();
            $('#btn-simpan').show();
        });
    });

    // ========================================
    // Form Validation - Checkbox Required
    // ========================================
    document.getElementById('formTambahMahasiswa')?.addEventListener('submit', function(e) {
        const totalChecked = document.querySelectorAll('input[name="mahasiswa_ids[]"]:checked').length;
        if (totalChecked === 0) {
            e.preventDefault();
            alert('Pilih minimal satu mahasiswa dulu.');
        }
    });

    // ========================================
    // Welcome Card Session Storage
    // ========================================
    const welcomeCard = document.getElementById("welcome_card");
    const closeBtn = document.getElementById("close_welcome_card");

    if (welcomeCard && closeBtn) {
        if (sessionStorage.getItem("hideWelcomeCard") === "true") {
            welcomeCard.style.display = "none";
        }

        closeBtn.addEventListener("click", function() {
            welcomeCard.style.display = "none";
            sessionStorage.setItem("hideWelcomeCard", "true");
        });
    }

    // ========================================
    // User Status Toggle (Aktif/Nonaktif)
    // ========================================
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-status').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                let userId = this.getAttribute('data-id');
                let isChecked = this.checked;
                let actionText = isChecked ? 'mengaktifkan' : 'menonaktifkan';
                let checkboxRef = this;

                Swal.fire({
                    title: `Apakah Anda yakin ingin ${actionText} pengguna ini?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, lanjutkan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-danger'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('status-input-' + userId).value =
                            isChecked ? 'true' : 'false';
                        document.getElementById('form-status-' + userId).submit();
                    } else {
                        checkboxRef.checked = !isChecked;
                    }
                });
            });
        });
    });

    // ========================================
    // Sidebar Toggle - Add title attribute for tooltip
    // ========================================
    // Handled in Sidebar Toggle Functionality section above

    // ========================================
    // Smooth Scroll for Anchor Links
    // ========================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>
