@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Dashboard</h1>
                <!--end::Title-->
                <!--begin::Separator-->
                <span class="h-20px border-gray-200 border-start mx-4"></span>
                <!--end::Separator-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center py-1">
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-dark">Dashboard</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Container-->
    </div>
@endsection

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <!--begin::Card-->
            <div class="card mb-7" id="welcome_card" style="background-color: #efefef;">
                <!--begin::Card body-->
                <div class="card-body d-flex align-items-center justify-content-between py-5 position-relative">

                    <!--begin::Close button-->
                    <button type="button" class="btn btn-sm btn-icon btn-light position-absolute top-0 end-0 m-3" id="close_welcome_card">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.314" width="16" height="2" rx="1" transform="rotate(-45 6 17.314)" fill="black"/>
                            <rect x="7.414" y="6" width="16" height="2" rx="1" transform="rotate(45 7.414 6)" fill="black"/>
                        </svg>
                    </button>
                    <!--end::Close button-->

                    <!--begin::Text-->
                    <div>
                        <h2 class="fw-bold mb-2">
                            Selamat Datang, <span>{{ $user->nama }}</span> 👋
                        </h2>
                        <p class="text-muted mb-0">Selamat datang di dashboard admin. Tetap semangat melayani civitas akademika!</p>
                    </div>
                    <!--end::Text-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->

            <!--begin::Card-->
            <div class="card mb-7">
                <!--begin::Card body-->
                <div class="card-body d-flex align-items-center justify-content-between py-5 position-relative">
                    <!--begin::Text-->
                    <div>
                        <h2 class="fw-bold mb-2">Isi Dashboard Karyawan</h2>
                    </div>
                    <!--end::Text-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
@endsection
