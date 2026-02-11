@extends('dosen.detail-dosen')

@section('dokumen')
  <div class="card">
    <div class="card-header flex-wrap gap-3 align-items-center justify-content-between">
      <div class="card-title m-0 d-flex align-items-center gap-3">
        <h3 class="fw-bolder m-0">Dokumen</h3>
      </div>
      <div class="d-flex gap-2">
        <a href="#" class="btn btn-primary btn-sm px-4" data-bs-toggle="modal" data-bs-target="#modalUploadBerkas">
          <i class="ki-duotone ki-cloud-upload me-2">
            <span class="path1"></span>
            <span class="path2"></span>
          </i> Upload Files
        </a>
      </div>
    </div>

    <div class="card-body p-6 p-md-9">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
          <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
          <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <div class="table-wrapper">
        <table class="table table-bordered table-striped align-middle" id="tbl-dokumen">
          <thead class="table-light">
            <tr class="text-muted text-uppercase fw-bold">
              <th class="text-center" style="width: 50px;">No</th>
              <th class="col-preview text-center" style="width: 100px;">Preview</th>
              <th class="col-name">Name</th>
              <th class="col-size text-center" style="width: 100px;">Size</th>
              <th class="col-date text-center" style="width: 150px;">Last Modified</th>
              <th class="col-act text-center" style="width: 120px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse(($dokumen ?? collect()) as $file)
              <tr class="row-file">
                <!-- Number Column -->
                <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                
                <!-- Preview/Thumbnail Column -->
                <td class="col-preview-cell text-center">
                  <div class="file-preview-wrapper d-flex justify-content-center">
                    @php
                      $filePath = asset('storage/' . $file->berkas);
                      $extension = strtolower($file->ekstensi ?? pathinfo($file->berkas ?? '', PATHINFO_EXTENSION));
                      $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
                      $isPdf = $extension === 'pdf';
                    @endphp
                    
                    @if($isImage)
                      <!-- Image Thumbnail -->
                      <div class="symbol symbol-70px overflow-hidden rounded cursor-pointer shadow-sm" 
                           onclick="previewFile('{{ $filePath }}', '{{ $file->nama }}', 'image')"
                           style="border: 2px solid #e4e6ef;">
                        <img src="{{ $filePath }}" alt="{{ $file->nama }}" class="file-thumbnail w-100 h-100" style="object-fit: cover;">
                      </div>
                    @elseif($isPdf)
                      <!-- PDF Icon with Preview -->
                      <div class="symbol symbol-70px overflow-hidden rounded cursor-pointer bg-light-danger shadow-sm"
                           onclick="previewFile('{{ $filePath }}', '{{ $file->nama }}', 'pdf')"
                           style="border: 2px solid #f1416c;">
                        <div class="symbol-label d-flex flex-column align-items-center justify-content-center p-2">
                          <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 2.5rem;"></i>
                          <span class="badge badge-danger badge-sm mt-1">PDF</span>
                        </div>
                      </div>
                    @else
                      <!-- Generic File Icon -->
                      <div class="symbol symbol-70px overflow-hidden rounded bg-light shadow-sm"
                           style="border: 2px solid #e4e6ef;">
                        <div class="symbol-label d-flex flex-column align-items-center justify-content-center p-2">
                          <i class="bi bi-file-earmark text-gray-600" style="font-size: 2.5rem;"></i>
                          <span class="badge badge-light-primary badge-sm mt-1">{{ strtoupper($extension) }}</span>
                        </div>
                      </div>
                    @endif
                  </div>
                </td>

                <!-- Name Column -->
                <td class="col-name-cell">
                  <div class="d-flex align-items-center">
                    <div class="min-w-0">
                      <a href="{{ $filePath }}" target="_blank"
                        class="text-gray-800 fw-semibold text-hover-primary d-inline-block text-truncate"
                        style="max-width: 420px" title="{{ $file->nama ?? 'untitled' }}">
                        {{ $file->nama ?? 'untitled' }}
                      </a>
                      <div class="text-muted fs-8 mt-1">
                        {{ strtoupper($extension) }}
                      </div>
                    </div>
                  </div>
                </td>

                <!-- Size Column -->
                <td class="text-center fw-semibold">
                  {{ isset($file->size) ? number_format($file->size / 1024, 0) . ' KB' : '—' }}
                </td>

                <!-- Date Column -->
                <td class="text-center">
                  {{ ($file->updated_at ?? $file->created_at)?->format('d M Y, H:i') ?? '—' }}
                </td>

                <!-- Actions Column -->
                <td class="text-center">
                  <div class="d-inline-flex align-items-center gap-1">
                    <!-- View/Preview Button -->
                    <button type="button" 
                            class="btn btn-icon btn-sm btn-light-primary" 
                            data-bs-toggle="tooltip" 
                            title="Lihat Dokumen"
                            onclick="previewFile('{{ $filePath }}', '{{ $file->nama }}', '{{ $isImage ? 'image' : ($isPdf ? 'pdf' : 'other') }}')">
                      <i class="bi bi-eye-fill"></i>
                    </button>

                    <!-- Download Button -->
                    <a href="{{ $filePath }}" 
                       class="btn btn-icon btn-sm btn-light-success"
                       data-bs-toggle="tooltip" 
                       title="Download" 
                       download>
                      <i class="bi bi-download"></i>
                    </a>

                    <!-- Delete Button -->
                    <form action="{{ route('dosen.dokumen.destroy', [$dosen->id, $file->id]) }}" 
                          method="POST"
                          style="display:inline;"
                          onsubmit="return confirm('Yakin hapus dokumen ini?\n\nFile: {{ $file->nama }}')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" 
                              class="btn btn-icon btn-sm btn-light-danger" 
                              data-bs-toggle="tooltip"
                              title="Hapus">
                        <i class="bi bi-trash-fill"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6">
                  <div class="empty-state text-center py-10">
                    <div class="symbol symbol-60px mb-3">
                      <span class="symbol-label bg-light rounded-4">
                        <i class="ki-duotone ki-folder fs-1"></i>
                      </span>
                    </div>
                    <div class="fw-semibold mb-1">Belum ada dokumen</div>
                    <p class="text-muted">Upload dokumen untuk memulai</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal Upload Berkas -->
  <div class="modal fade" id="modalUploadBerkas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form id="formUploadBerkas" class="needs-validation" action="{{ route('dosen.dokumen.store', $dosen->id) }}"
          method="POST" enctype="multipart/form-data" novalidate>
          @csrf
          <input type="hidden" name="id_dosen" value="{{ $dosen->id }}">

          <div class="modal-header">
            <h5 class="modal-title">Upload Berkas</h5>
            <button type="button" class="btn btn-sm btn-icon" data-bs-dismiss="modal" aria-label="Close">
              <i class="ki-duotone ki-cross fs-2"></i>
            </button>
          </div>

          <div class="modal-body">
            <div class="mb-4">
              <label for="namaBerkas" class="form-label required">Nama Berkas</label>
              <input type="text" id="namaBerkas" name="nama" class="form-control" 
                     placeholder="Mis. SK Pengangkatan"
                     required value="{{ old('nama') }}">
              @error('nama')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
              <div class="invalid-feedback">Nama berkas wajib diisi.</div>
            </div>

            <div class="mb-2">
              <label for="fileBerkas" class="form-label required">Upload Berkas</label>
              <input type="file" id="fileBerkas" name="berkas" class="form-control"
                accept=".pdf,.doc,.docx,.xls,.xlsx,image/*" required>
              @error('berkas')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
              <div class="invalid-feedback">Silakan pilih file untuk diupload.</div>
              <div class="form-text">Format: PDF, DOC, DOCX, XLS, XLSX, atau gambar. Maks 10MB.</div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">
              <i class="ki-duotone ki-cloud-upload me-2"></i> Upload
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Preview File -->
  <div class="modal fade" id="modalPreviewFile" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="previewFileName">Preview Dokumen</h5>
          <button type="button" class="btn btn-sm btn-icon" data-bs-dismiss="modal">
            <i class="ki-duotone ki-cross fs-2"></i>
          </button>
        </div>
        <div class="modal-body p-0" style="min-height: 500px;">
          <div id="previewContent" class="w-100 h-100 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    .file-preview-wrapper {
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .file-preview-wrapper:hover {
      transform: scale(1.08);
    }

    .file-thumbnail {
      width: 70px;
      height: 70px;
      object-fit: cover;
      transition: all 0.3s ease;
    }

    .symbol {
      transition: all 0.3s ease;
    }

    .symbol:hover {
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .table-bordered th {
      background-color: #f5f8fa;
      font-weight: 600;
      border-color: #e4e6ef;
    }

    .table-bordered td {
      border-color: #e4e6ef;
      vertical-align: middle;
    }

    .row-file:hover {
      background-color: #f9fafb;
    }

    #previewContent iframe {
      width: 100%;
      min-height: 600px;
      border: none;
    }

    #previewContent img {
      max-width: 100%;
      height: auto;
      display: block;
      margin: 0 auto;
    }

    .btn-icon {
      width: 32px;
      height: 32px;
      padding: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .btn-icon i {
      font-size: 1rem;
    }
  </style>

  <script>
    // Preview File Function
    function previewFile(fileUrl, fileName, fileType) {
      const modal = new bootstrap.Modal(document.getElementById('modalPreviewFile'));
      const previewContent = document.getElementById('previewContent');
      const previewFileNameEl = document.getElementById('previewFileName');
      
      // Set title
      previewFileNameEl.textContent = fileName;
      
      // Show loading
      previewContent.innerHTML = `
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      `;
      
      modal.show();
      
      // Load content based on type
      setTimeout(() => {
        if (fileType === 'image') {
          previewContent.innerHTML = `
            <img src="${fileUrl}" alt="${fileName}" class="img-fluid p-3">
          `;
        } else if (fileType === 'pdf') {
          previewContent.innerHTML = `
            <iframe src="${fileUrl}" class="w-100"></iframe>
          `;
        } else {
          previewContent.innerHTML = `
            <div class="p-5 text-center">
              <i class="bi bi-file-earmark fs-1 text-muted mb-3"></i>
              <p class="text-muted">Preview tidak tersedia untuk format file ini.</p>
              <a href="${fileUrl}" class="btn btn-primary" download>
                <i class="bi bi-download me-2"></i>Download File
              </a>
            </div>
          `;
        }
      }, 300);
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    });
  </script>
@endsection