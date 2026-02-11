@php
    $users = Auth::user();
@endphp

<div id="kt_header" style="" class="header align-items-stretch">
    <div class="container-fluid d-flex align-items-stretch justify-content-between">

        <!--begin::Aside mobile toggle (Mobile Only)-->
        <div class="d-flex align-items-center d-lg-none ms-n3 me-1" title="Show aside menu">
            <div class="btn btn-icon btn-active-color-white" id="kt_aside_mobile_toggle">
                <i class="bi bi-list fs-1"></i>
            </div>
        </div>
        <!--end::Aside mobile toggle-->

        <!--begin::Desktop Toggle Button (Desktop Only)-->
        <div class="d-none d-lg-flex align-items-center ms-n3 me-3">
            <button class="btn btn-icon btn-active-light-primary w-40px h-40px" id="kt_aside_desktop_toggle"
                title="Toggle Sidebar">
                <span class="svg-icon svg-icon-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none">
                        <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
                            fill="currentColor" />
                        <path opacity="0.3"
                            d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
                            fill="currentColor" />
                    </svg>
                </span>
            </button>
        </div>
        <!--end::Desktop Toggle Button-->

        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="/dashboard" class="d-lg-none">
                <img alt="Logo" src="{{ asset('assets/media/logo/logo.png') }}" class="h-25px" />
            </a>
        </div>

        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
            <div class="d-flex align-items-stretch" id="kt_header_nav">
            </div>

            <div class="d-flex align-items-stretch flex-shrink-0">
                <div class="topbar d-flex align-items-stretch flex-shrink-0">
                    <div class="d-flex align-items-stretch" id="kt_header_user_menu_toggle">
                        {{-- Avatar Trigger --}}
                        <div class="topbar-item cursor-pointer symbol symbol-circle px-3 px-lg-5 me-n3 me-lg-n5 symbol-45px symbol-md-50px"
                            data-kt-menu-trigger="click" data-kt-menu-attach="parent"
                            data-kt-menu-placement="bottom-end" data-kt-menu-flip="bottom">
                            @php
                                $avatarPath = $users->img ?? 'foto_users/default.png';
                                $avatarUrl = file_exists(public_path($avatarPath))
                                    ? asset($avatarPath)
                                    : asset('foto_users/default.png');
                            @endphp
                            <img src="{{ $avatarUrl }}" alt="{{ $users->nama }}" class="avatar-shadow"
                                style="object-fit: cover; border-radius: 50%;" />
                        </div>

                        {{-- Dropdown Menu --}}
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px"
                            data-kt-menu="true">
                            <div class="menu-item px-3">
                                <div class="menu-content d-flex align-items-center px-3">
                                    <div class="symbol symbol-circle symbol-50px me-5">
                                        <img alt="{{ $users->nama }}" src="{{ $avatarUrl }}" class="avatar-border"
                                            style="object-fit: cover; border-radius: 50%;" />
                                    </div>

                                    <div class="d-flex flex-column">
                                        <div class="fw-bolder d-flex align-items-center fs-5">{{ $users->nama }}</div>
                                        <a href="#"
                                            class="fw-bold text-muted text-hover-primary fs-7">{{ $users->email }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="separator my-2"></div>
                            <div class="menu-item px-5">
                                <a href="#" class="menu-link px-5">Profil</a>
                            </div>
                            <div class="menu-item px-5">
                                <a href="#" class="menu-link px-5">Pengaturan Akun</a>
                            </div>
                            <div class="separator my-2"></div>
                            <div class="menu-item px-5">
                                <a href="#" class="menu-link px-5"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit(); sessionStorage.removeItem('hideWelcomeCard');">
                                    Keluar
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
