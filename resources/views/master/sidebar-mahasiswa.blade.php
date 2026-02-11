<div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('profil*') ? 'hover show' : '' }}">
    <span class="menu-link">
        <span class="menu-icon">
            <i class="bi bi-person fs-3"></i>
        </span>
        <span class="menu-title">Profil</span>
        <span class="menu-arrow"></span>
    </span>
    <div class="menu-sub menu-sub-accordion menu-active-bg">
        <div class="menu-item">
            <a class="menu-link {{ request()->is('profil/biodata*') ? 'active' : '' }}" href="/profil/biodata">
                <span class="menu-bullet">
                    <span class="bi bi-person-badge"></span>
                </span>
                <span class="menu-title">Biodata</span>
            </a>
            <a class="menu-link {{ request()->is('profil/data-pendidikan') ? 'active' : '' }}" href="/profil/data-pendidikan">
                <span class="menu-bullet">
                    <span class="bi bi-mortarboard"></span>
                </span>
                <span class="menu-title">Data Pendidikan</span>
            </a>
            <a class="menu-link {{ request()->is('profil/data-orang-tua*') ? 'active' : '' }}" href="/profil/data-orang-tua">
                <span class="menu-bullet">
                    <span class="bi bi-people"></span>
                </span>
                <span class="menu-title">Data Orang Tua</span>
            </a>
            <a class="menu-link {{ request()->is('profil/data-akademik*') ? 'active' : '' }}" href="/profil/data-akademik">
                <span class="menu-bullet">
                    <span class="bi bi-journal-text"></span>
                </span>
                <span class="menu-title">Data Akademik</span>
            </a>
            <a class="menu-link {{ request()->is('profil/dokumen*') ? 'active' : '' }}" href="/profil/dokumen">
                <span class="menu-bullet">
                    <span class="bi bi-folder2-open"></span>
                </span>
                <span class="menu-title">Dokumen</span>
            </a>
        </div>
    </div>
</div>
