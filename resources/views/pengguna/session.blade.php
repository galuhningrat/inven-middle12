@extends('master.app')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Data Session</h1>
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
                    <li class="breadcrumb-item text-muted">
                        <a href="/dashboard" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-dark">Data Session</li>
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
    <!--begin::Tampil Session-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            @include('master.notification')
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="d-flex flex-wrap justify-content-between align-items-center w-100">
                        <!--begin::Search-->
                        <div id="custom-search-container" class="mb-2 mb-md-0"></div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table id="tabel-custom"
                            class="table table-bordered table-striped table-sm align-middle fs-6 gy-2 w-100">
                            <thead class="bg-gray-100 text-gray-800 border-bottom border-gray-300">
                                <tr class="fw-bold fs-5 text-uppercase">
                                    <th class="text-center py-4 px-2 min-w-50px">No</th>
                                    <th class="py-4 px-3 min-w-250px">Nama Pengguna</th>
                                    <th class="py-4 px-3 min-w-100px">Level</th>
                                    <th class="py-4 px-3 min-w-150px">IP Address</th>
                                    <th class="py-4 px-3 min-w-250px">User Agent</th>
                                    <th class="text-center py-4 px-2 min-w-50px">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="fw-bold text-gray-600">
                                @foreach ($session as $ses)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                <div class="symbol-label">
                                                    @if ($ses->user->img && file_exists(public_path('storage/' . $ses->user->img)))
                                                        <img src="{{ asset('storage/' . $ses->user->img) }}"
                                                            alt="{{ $ses->user->nama }}" class="w-100" />
                                                    @else
                                                        <img src="{{ asset('storage/foto_users/default.png') }}"
                                                            alt="default" class="w-100" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="#"
                                                    class="text-gray-800 text-hover-primary mb-1">{{ $ses->user->nama }}</a>
                                                <span>{{ $ses->user->email }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $ses->user->role->nama_role }}</td>
                                        <td>{{ $ses->ip_address }}</td>
                                        <td>{{ $ses->user_agent }}</td>
                                        <td class="text-center">
                                            <!-- Hapus -->
                                            <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-hapus"
                                                title="Hapus" data-id="{{ $ses->user_id }}">
                                                <i class="bi bi-trash-fill fs-5"></i>
                                            </button>

                                            <!-- Hidden Form -->
                                            <form id="form-hapus-{{ $ses->user_id }}"
                                                action="{{ route('master-session.destroy', $ses->user_id) }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Tampil Session-->
@endsection
