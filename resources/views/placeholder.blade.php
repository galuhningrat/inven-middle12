@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ $title ?? 'Halaman' }}</h1>
            </div>
            <div class="d-flex align-items-center py-1">
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="/dashboard" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">{{ $title ?? 'Halaman' }}</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-fluid">
            <div class="card">
                <div class="card-body text-center py-20">
                    <!-- Icon -->
                    <div class="mb-10">
                        <i class="bi bi-tools text-primary" style="font-size: 5rem;"></i>
                    </div>

                    <!-- Title -->
                    <h1 class="fw-bolder text-gray-900 mb-5">Fitur Sedang Dikembangkan</h1>

                    <!-- Description -->
                    <div class="text-gray-600 fw-semibold fs-5 mb-10">
                        Halaman <strong>{{ $title ?? 'ini' }}</strong> sedang dalam tahap pengembangan.<br>
                        Tim IT kami sedang bekerja keras untuk menghadirkan fitur terbaik untuk Anda.
                    </div>

                    <!-- Status Badge -->
                    <div class="mb-10">
                        <span class="badge badge-light-warning fs-6 px-5 py-3">
                            <i class="bi bi-clock-history me-2"></i>
                            Coming Soon
                        </span>
                    </div>

                    <!-- Back Button -->
                    <a href="/dashboard" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-2"></i>
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
