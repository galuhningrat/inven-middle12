@php
    use Illuminate\Support\Facades\Auth;
    $users = Auth::user();
    $roleId = $users->id_role;
    $roleName = $users->role->nama_role ?? '-';
@endphp

<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_aside_mobile_toggle">

    <!-- User Profile Section -->
    <div class="aside-user d-flex align-items-center px-5 py-4">
        <div class="symbol symbol-circle symbol-50px">
            @php
                $avatarPath = $users->img ?? 'foto_users/default.png';
                $avatarUrl = file_exists(public_path($avatarPath))
                    ? asset($avatarPath)
                    : asset('foto_users/default.png');
            @endphp
            <img src="{{ $avatarUrl }}" alt="{{ $users->nama }}" class="avatar-border"
                style="object-fit: cover; border-radius: 50%;" />
        </div>
        <div class="aside-user-info flex-row-fluid flex-wrap ms-4">
            <div class="d-flex">
                <div class="flex-grow-1 me-2">
                    <a href="#" class="text-white text-hover-primary fs-6 fw-bolder">{{ $users->nama }}</a>
                    <span class="text-gray-600 fw-bold d-block fs-8 mb-1">{{ $roleName }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="separator mt-2 mb-0 mx-5"></div>

    <!-- Menu Section -->
    <div class="aside-menu flex-column-fluid">
        <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu"
            data-kt-scroll-offset="0">

            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
                id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">

                @php
                    $menus = \App\Helpers\MenuHelper::getMenuByRole(Auth::user()->id_role);
                @endphp

                @foreach ($menus as $menuKey => $menu)
                    @if (isset($menu['submenu']))
                        {{-- Menu dengan Submenu --}}
                        <div data-kt-menu-trigger="click"
                            class="menu-item menu-accordion {{ request()->is($menu['active_pattern'] ?? 'never-match*') ? 'hover show' : '' }}">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="{{ $menu['icon'] }} fs-2"></i>
                                </span>
                                <span class="menu-title">{{ $menu['title'] }}</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                @foreach ($menu['submenu'] as $submenu)
                                    <div class="menu-item">
                                        <a class="menu-link {{ request()->routeIs($submenu['route']) ? 'active' : '' }}"
                                            href="{{ route($submenu['route']) }}">
                                            <span class="menu-bullet">
                                                <span class="{{ $submenu['icon'] ?? 'bullet bullet-dot' }}"></span>
                                            </span>
                                            <span class="menu-title">{{ $submenu['title'] }}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        {{-- Menu Tanpa Submenu --}}
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs($menu['route']) ? 'active' : '' }}"
                                href="{{ route($menu['route']) }}">
                                <span class="menu-icon">
                                    <i class="{{ $menu['icon'] }} fs-2"></i>
                                </span>
                                <span class="menu-title">{{ $menu['title'] }}</span>
                            </a>
                        </div>
                    @endif
                @endforeach

            </div>
        </div>
    </div>
</div>
