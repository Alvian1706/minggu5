{{-- resources/views/kamar/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Manajemen Kamar')
@section('breadcrumb', 'Kamar / Daftar')
@section('method_badge')
@endsection

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header-row">
    <div class="page-title-block">
        <h1 class="title">🚪 Manajemen Kamar</h1>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('kamar.laporan') }}" class="btn btn-outline-secondary px-3">
            <i class="bi bi-bar-chart-line"></i> Laporan
        </a>
        <a href="{{ route('kamar.create') }}" class="btn btn-primary-kost px-4">
            <i class="bi bi-plus-lg"></i> Tambah Kamar
        </a>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card blue">
            <div class="stat-num">{{ $statistik['total'] }}</div>
            <div class="stat-label">Total Kamar</div>
            <i class="bi bi-building stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card green">
            <div class="stat-num">{{ $statistik['tersedia'] }}</div>
            <div class="stat-label">Tersedia</div>
            <i class="bi bi-door-open stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card rose">
            <div class="stat-num">{{ $statistik['terisi'] }}</div>
            <div class="stat-label">Terisi</div>
            <i class="bi bi-person-fill stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card amber">
            <div class="stat-num">{{ $statistik['perbaikan'] }}</div>
            <div class="stat-label">Perbaikan</div>
            <i class="bi bi-tools stat-icon"></i>
        </div>
    </div>
</div>

{{-- FILTER --}}
<div class="filter-card">
    <form method="GET" action="{{ route('kamar.index') }}" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Cari Kamar</label>
            <div class="input-group">
                <span class="input-group-text" style="border-right:0;border-radius:9px 0 0 9px;">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search" class="form-control" style="border-left:0;border-radius:0 9px 9px 0;"
                    placeholder="Nomor, tipe..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-2">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="tersedia"  {{ request('status')=='tersedia'  ?'selected':'' }}>✅ Tersedia</option>
                <option value="terisi"    {{ request('status')=='terisi'    ?'selected':'' }}>🔴 Terisi</option>
                <option value="perbaikan" {{ request('status')=='perbaikan' ?'selected':'' }}>🔧 Perbaikan</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Tipe</label>
            <select name="tipe" class="form-select">
                <option value="">Semua Tipe</option>
                <option value="standar" {{ request('tipe')=='standar'?'selected':'' }}>Standar</option>
                <option value="deluxe"  {{ request('tipe')=='deluxe' ?'selected':'' }}>Deluxe</option>
                <option value="vip"     {{ request('tipe')=='vip'    ?'selected':'' }}>VIP</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">&nbsp;</label>
            <button type="submit" class="btn btn-primary-kost w-100">
                <i class="bi bi-search"></i> Cari
            </button>
        </div>
        <div class="col-md-2">
            <label class="form-label">&nbsp;</label>
            <a href="{{ route('kamar.index') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-counterclockwise"></i> Reset
            </a>
        </div>
    </form>
</div>

{{-- TABLE --}}
<div class="table-card">
    <div class="table-card-header">
        <div class="table-card-title">
            <i class="bi bi-table me-2 text-primary"></i>
            Data Kamar
            <span class="badge ms-2" style="background:#f1f5f9;color:#64748b;font-size:.7rem;border-radius:6px;">
                {{ $kamars->total() }} data
            </span>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if(request('search') || request('status') || request('tipe'))
                <span class="badge" style="background:#fef3c7;color:#92400e;font-size:.7rem;padding:.35rem .75rem;border-radius:6px;">
                    <i class="bi bi-funnel me-1"></i>Filter aktif
                </span>
            @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th style="width:50px" class="ps-4">#</th>
                    <th>Nomor Kamar</th>
                    <th>Tipe</th>
                    <th>Luas</th>
                    <th>Harga / Bulan</th>
                    <th>Fasilitas</th>
                    <th>Status</th>
                    <th class="text-center" style="width:130px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kamars as $i => $kamar)
                <tr>
                    <td class="ps-4 text-muted fw-500" style="font-size:.8rem;">
                        {{ $kamars->firstItem() + $i }}
                    </td>
                    <td>
                        <div class="fw-700" style="font-size:.95rem;letter-spacing:-.2px;">
                            {{ $kamar->nomor_kamar }}
                        </div>
                    </td>
                    <td>
                        <span class="tipe-badge tipe-{{ $kamar->tipe_kamar }}">
                            {{ strtoupper($kamar->tipe_kamar) }}
                        </span>
                    </td>
                    <td>
                        <span class="text-muted">{{ $kamar->luas_kamar }} m²</span>
                    </td>
                    <td>
                        <div class="fw-700" style="color:#059669;font-size:.95rem;">
                            Rp {{ number_format($kamar->harga_bulan, 0, ',', '.') }}
                        </div>
                        <div style="font-size:.72rem;color:#94a3b8;">/bulan</div>
                    </td>
                    <td>
                        <div style="max-width:180px;font-size:.8rem;color:#64748b;line-height:1.3;">
                            {{ \Illuminate\Support\Str::limit($kamar->fasilitas ?? '—', 40) }}
                        </div>
                    </td>
                    <td>
                        <span class="badge-status badge-{{ $kamar->status }}">
                            {{ ucfirst($kamar->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-1">
                            {{-- Detail --}}
                            <a href="{{ route('kamar.show', $kamar->id) }}"
                               class="btn-action btn-view" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            {{-- Edit --}}
                            <a href="{{ route('kamar.edit', $kamar->id) }}"
                               class="btn-action btn-edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            {{-- Delete via SweetAlert --}}
                            <form id="delete-form-{{ $kamar->id }}"
                                  action="{{ route('kamar.destroy', $kamar->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return false;">
                                @csrf @method('DELETE')
                                <button type="button"
                                        class="btn-action btn-delete btn-swal-delete"
                                        data-nama="{{ $kamar->nomor_kamar }}"
                                        data-type="Kamar"
                                        data-form-id="delete-form-{{ $kamar->id }}"
                                        title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="empty-icon">🚪</div>
                            <div class="empty-title mb-1">Belum ada data kamar</div>
                            <div class="empty-sub mb-3">Tambahkan kamar pertama kost Anda</div>
                            <a href="{{ route('kamar.create') }}" class="btn btn-primary-kost btn-sm px-4">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Kamar
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($kamars->hasPages())
    <div class="px-4 py-3" style="border-top:1px solid var(--border);">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Menampilkan {{ $kamars->firstItem() }}–{{ $kamars->lastItem() }} dari {{ $kamars->total() }} data
            </small>
            {{ $kamars->withQueryString()->links() }}
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    // Pastikan handler ini berjalan setelah DOM & SweetAlert siap
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-swal-delete').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const nama    = btn.getAttribute('data-nama');
                const type    = btn.getAttribute('data-type');
                const formId  = btn.getAttribute('data-form-id');
                const form    = document.getElementById(formId);

                Swal.fire({
                    title: 'Hapus ' + type + '?',
                    html: 'Data <strong>' + nama + '</strong> akan dihapus permanen.<br>Tindakan ini tidak dapat dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="bi bi-trash me-1"></i>Ya, Hapus',
                    cancelButtonText: '<i class="bi bi-x me-1"></i>Batal',
                    reverseButtons: true,
                    focusCancel: true,
                }).then(function (result) {
                    if (result.isConfirmed && form) {
    
                        form.onsubmit = null;
                        form.submit();
                    }
                    
                });
            });
        });
    });
</script>
@endpush