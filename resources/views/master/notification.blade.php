@if (session('message'))
    <div class="position-fixed top-0 end-0 mt-4 zindex-tooltip" style="z-index: 1080;">
        <div class="alert bg-{{ session('alert_type', 'light') }}
                    {{ session('alert_type') == 'light' ? 'text-dark' : 'text-white' }}
                    d-flex align-items-center shadow-sm rounded px-6 py-4 small fade show"
             role="alert" style="min-width: 250px; max-width: 320px;">
            <i class="bi
                {{ session('alert_type') == 'primary' ? 'bi-check-circle-fill' : '' }}
                {{ session('alert_type') == 'danger' ? 'bi-trash-fill' : '' }}
                {{ session('alert_type') == 'warning' ? 'bi-info-circle-fill' : '' }}
                me-2 fs-5
                {{ session('alert_type') == 'light' ? 'text-dark' : 'text-white' }}"></i>
            <span class="flex-grow-1">{{ session('message') }}</span>
        </div>
    </div>

    <script>
        setTimeout(() => {
            const alertEl = document.querySelector('.alert');
            if (alertEl) {
                alertEl.classList.add('fade');
                alertEl.classList.remove('show');
                setTimeout(() => alertEl.remove(), 300); // hapus setelah transisi
            }
        }, 3000);
    </script>
@endif
