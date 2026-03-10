{{-- resources/views/penyewa/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Manajemen Penyewa')
@section('breadcrumb', 'Penyewa / Daftar')
@section('method_badge')
@endsection

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header-row">
    <div class="page-title-block">
        <h1 class="title">👤 Manajemen Penyewa</h1>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('penyewa.laporan') }}" class="btn btn-outline-secondary px-3">
            <i class="bi bi-graph-up"></i> Laporan
        </a>
        <a href="{{ route('penyewa.create') }}" class="btn btn-success-kost px-4">
            <i class="bi bi-person-plus-fill"></i> Daftar Penyewa
        </a>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card blue">
            <div class="stat-num">{{ $statistik['total'] }}</div>
            <div class="stat-label">Total Penyewa</div>
            <i class="bi bi-people-fill stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card green">
            <div class="stat-num">{{ $statistik['aktif'] }}</div>
            <div class="stat-label">Aktif</div>
            <i class="bi bi-person-check-fill stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card slate">
            <div class="stat-num">{{ $statistik['selesai'] }}</div>
            <div class="stat-label">Selesai Kontrak</div>
            <i class="bi bi-person-dash-fill stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card amber">
            <div class="stat-num">{{ $statistik['segera_habis'] ?? 0 }}</div>
            <div class="stat-label">Habis ≤ 7 Hari</div>
            <i class="bi bi-alarm-fill stat-icon"></i>
        </div>
    </div>
</div>


{{-- FILTER --}}
<div class="filter-card">
    <form method="GET" action="{{ route('penyewa.index') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Cari Penyewa</label>
            <div class="input-group">
                <span class="input-group-text" style="border-right:0;border-radius:9px 0 0 9px;">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search" class="form-control" style="border-left:0;border-radius:0 9px 9px 0;"
                    placeholder="Nama, KTP, telepon..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-2">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">Semua</option>
                <option value="aktif"   {{ request('status')=='aktif'  ?'selected':'' }}>🟢 Aktif</option>
                <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>⚪ Selesai</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Kamar</label>
            <select name="kamar_id" class="form-select">
                <option value="">Semua Kamar</option>
                @foreach($kamars as $k)
                    <option value="{{ $k->id }}" {{ request('kamar_id')==$k->id?'selected':'' }}>
                        Kamar {{ $k->nomor_kamar }} ({{ ucfirst($k->tipe_kamar) }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">&nbsp;</label>
            <button type="submit" class="btn btn-success-kost w-100">
                <i class="bi bi-search"></i> Cari
            </button>
        </div>
        <div class="col-md-2">
            <label class="form-label">&nbsp;</label>
            <a href="{{ route('penyewa.index') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-counterclockwise"></i> Reset
            </a>
        </div>
    </form>
</div>

{{-- TABLE --}}
<div class="table-card">
    <div class="table-card-header">
        <div class="table-card-title">
            <i class="bi bi-table me-2" style="color:#10b981;"></i>
            Data Penyewa
            <span class="badge ms-2" style="background:#f1f5f9;color:#64748b;font-size:.7rem;border-radius:6px;">
                {{ $penyewas->total() }} data
            </span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th style="width:45px" class="ps-4">#</th>
                    <th>Penyewa</th>
                    <th>No. KTP</th>
                    <th>Kamar</th>
                    <th>PIC</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="text-center" style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penyewas as $i => $p)
                <tr>
                    <td class="ps-4 text-muted" style="font-size:.8rem;">{{ $penyewas->firstItem() + $i }}</td>
                    <td>
                        <div class="fw-700" style="font-size:.9rem;">{{ $p->nama_penyewa }}</div>
                        <div style="font-size:.75rem;color:#94a3b8;">{{ $p->no_telepon }}</div>
                    </td>
                    <td>
                        <span style="font-family:'DM Mono',monospace;font-size:.75rem;color:#64748b;">
                            {{ $p->no_ktp }}
                        </span>
                    </td>
                    <td>
                        @if($p->kamar)
                            <div class="d-flex align-items-center gap-1">
                                <span class="tipe-badge tipe-{{ $p->kamar->tipe_kamar }}">
                                    {{ $p->kamar->nomor_kamar }}
                                </span>
                            </div>
                            <div style="font-size:.72rem;color:#94a3b8;margin-top:2px;">{{ ucfirst($p->kamar->tipe_kamar) }}</div>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-size:.8rem;">{{ $p->karyawan->nama ?? '—' }}</div>
                    </td>
                    <td>
                        <div style="font-size:.8rem;white-space:nowrap;">{{ $p->tanggal_masuk->format('d M Y') }}</div>
                    </td>
                    <td>
                        <div style="font-size:.8rem;white-space:nowrap;">{{ $p->tanggal_keluar->format('d M Y') }}</div>
                        @if($p->status == 'aktif' && $p->sisa_hari <= 7 && $p->sisa_hari >= 0)
                            <span class="sisa-badge">{{ $p->sisa_hari }}hr lagi</span>
                        @endif
                    </td>
                    <td>
                        <div class="fw-700" style="color:#059669;font-size:.875rem;white-space:nowrap;">
                            Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                        </div>
                        <div style="font-size:.7rem;color:#94a3b8;">{{ $p->lama_sewa }} bulan</div>
                    </td>
                    <td>
                        <span class="badge-status badge-{{ $p->status }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-1">
                            {{-- Detail --}}
                            <a href="{{ route('penyewa.show', $p->id) }}"
                               class="btn-action btn-view" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            {{-- Edit --}}
                            <a href="{{ route('penyewa.edit', $p->id) }}"
                               class="btn-action btn-edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            {{-- Checkout atau Delete --}}
                            @if($p->status == 'aktif')
                                <form action="{{ route('penyewa.checkout', $p->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="button"
                                            class="btn-action btn-checkout btn-swal-checkout"
                                            data-nama="{{ $p->nama_penyewa }}"
                                            data-kamar="{{ $p->kamar->nomor_kamar ?? '' }}"
                                            title="Checkout">
                                        <i class="bi bi-box-arrow-right"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('penyewa.destroy', $p->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                            class="btn-action btn-delete btn-swal-delete"
                                            data-nama="{{ $p->nama_penyewa }}"
                                            data-type="Penyewa"
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10">
                        <div class="empty-state">
                            <div class="empty-icon">👤</div>
                            <div class="empty-title mb-1">Belum ada data penyewa</div>
                            <div class="empty-sub mb-3">Daftarkan penyewa pertama kost Anda</div>
                            <a href="{{ route('penyewa.create') }}" class="btn btn-success-kost btn-sm px-4">
                                <i class="bi bi-person-plus-fill me-1"></i>Daftar Penyewa
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($penyewas->hasPages())
    <div class="px-4 py-3" style="border-top:1px solid var(--border);">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">
                Menampilkan {{ $penyewas->firstItem() }}–{{ $penyewas->lastItem() }} dari {{ $penyewas->total() }} data
            </small>
            {{ $penyewas->withQueryString()->links() }}
        </div>
    </div>
    @endif
</div>

@endsection