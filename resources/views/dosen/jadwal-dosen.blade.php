@extends('dosen.detail-dosen')

@php $hari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; @endphp

@section('jadwal')
<div class="card-header"><div class="card-title"><h3 class="fw-bolder m-0">Jadwal Mengajar</h3></div></div>
<div class="card-body p-9">
@if(isset($jadwal) && count($jadwal))
    <div class="row g-4">
        @foreach($hari as $h)
        <div class="col-md-6 col-lg-4">
            <div class="border rounded h-100 d-flex flex-column">
                <div class="p-3 border-bottom fw-bold">{{ $h }}</div>
                <div class="p-3 d-grid gap-3">
                    @forelse($jadwal->where('hari',$h)->sortBy('jam_mulai') as $item)
                        <div class="p-3 rounded bg-light">
                            <div class="fw-bold mb-1">{{ $item->matkul->nama_mk ?? '-' }}</div>
                            <div class="small text-muted mb-1">Kelas: {{ $item->rombel->nama_rombel ?? '-' }}</div>
                            <div class="badge bg-primary">
                                {{ \Carbon\Carbon::createFromFormat('H:i:s',$item->jam_mulai)->format('H:i') }}
                                – {{ \Carbon\Carbon::createFromFormat('H:i:s',$item->jam_selesai)->format('H:i') }}
                            </div>
                        </div>
                    @empty
                        <div class="text-muted small">Tidak ada jadwal.</div>
                    @endforelse
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="alert alert-info text-center">Belum ada jadwal.</div>
@endif
</div>
@endsection
