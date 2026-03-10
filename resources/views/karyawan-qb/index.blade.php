@extends('layouts.app')
@section('title', 'Karyawan (Query Builder)')
@section('breadcrumb', 'Karyawan QB / Daftar')
@section('method_badge')
    <span class="method-pill method-pill-qb"><i class="bi bi-database-fill me-1"></i>Query Builder</span>
@endsection
@section('content')

<div class="page-header-row">
    <div class="page-title-block">
        <h1 class="title">🪪 Manajemen Karyawan <span style="color:#3b82f6;font-size:1rem;">(QB)</span></h1>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary btn-sm px-3">
            <i class="bi bi-arrow-left-right me-1"></i>Versi Eloquent
        </a>
        <a href="{{ route('karyawan-qb.create') }}" class="btn btn-primary-kost px-4">
            <i class="bi bi-person-plus-fill"></i> Tambah Karyawan
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card blue">
            <div class="stat-num">{{ $jabatanList->sum('total') }}</div>
            <div class="stat-label">Total Karyawan</div>
            <i class="bi bi-people-fill stat-icon"></i>
        </div>
    </div>
    @foreach([['admin','green','bi-person-gear-fill','Admin'],['teknisi','amber','bi-wrench-adjustable','Teknisi'],['kebersihan','purple','bi-stars','Kebersihan']] as [$jabatan,$warna,$icon,$label])
    <div class="col-6 col-md-3">
        <div class="stat-card {{ $warna }}">
            <div class="stat-num">{{ $jabatanList->firstWhere('jabatan',$jabatan)->total ?? 0 }}</div>
            <div class="stat-label">{{ $label }}</div>
            <i class="bi {{ $icon }} stat-icon"></i>
        </div>
    </div>
    @endforeach
</div>

<div class="filter-card">
    <form method="GET" action="{{ route('karyawan-qb.index') }}" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Cari Karyawan</label>
            <div class="input-group">
                <span class="input-group-text" style="border-right:0;border-radius:9px 0 0 9px;"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" style="border-left:0;border-radius:0 9px 9px 0;"
                    placeholder="Nama, telepon..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label">Jabatan</label>
            <select name="jabatan" class="form-select">
                <option value="">Semua Jabatan</option>
                <option value="admin"      {{ request('jabatan')=='admin'      ?'selected':'' }}>🧑‍💼 Admin</option>
                <option value="teknisi"    {{ request('jabatan')=='teknisi'    ?'selected':'' }}>🔧 Teknisi</option>
                <option value="kebersihan" {{ request('jabatan')=='kebersihan' ?'selected':'' }}>🧹 Kebersihan</option>
                <option value="keamanan"   {{ request('jabatan')=='keamanan'   ?'selected':'' }}>🛡️ Keamanan</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">&nbsp;</label>
            <button type="submit" class="btn btn-primary-kost w-100"><i class="bi bi-search"></i> Cari</button>
        </div>
        <div class="col-md-2">
            <label class="form-label">&nbsp;</label>
            <a href="{{ route('karyawan-qb.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="table-card-header">
        <div class="table-card-title">
            <i class="bi bi-table me-2 text-primary"></i>
            Data Karyawan
            <span class="badge ms-2" style="background:#f1f5f9;color:#64748b;font-size:.7rem;border-radius:6px;">{{ $karyawans->total() }} data</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th style="width:45px" class="ps-4">#</th>
                    <th>Karyawan</th>
                    <th>Jabatan</th>
                    <th>Kontak</th>
                    <th>Gaji</th>
                    <th>Tgl Masuk</th>
                    <th>Status</th>
                    <th class="text-center" style="width:130px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($karyawans as $i => $k)
                <tr>
                    <td class="ps-4 text-muted" style="font-size:.8rem;">{{ $karyawans->firstItem() + $i }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="karyawan-avatar jabatan-{{ $k->jabatan }}">{{ strtoupper(substr($k->nama,0,1)) }}</div>
                            <div>
                                <div class="fw-700" style="font-size:.9rem;">{{ $k->nama }}</div>
                                @if($k->email)<div style="font-size:.72rem;color:#94a3b8;">{{ $k->email }}</div>@endif
                            </div>
                        </div>
                    </td>
                    <td><span class="jabatan-badge jabatan-badge-{{ $k->jabatan }}">{{ ucfirst($k->jabatan) }}</span></td>
                    <td><div style="font-size:.85rem;"><i class="bi bi-telephone me-1 text-muted" style="font-size:.75rem;"></i>{{ $k->no_telepon }}</div></td>
                    <td>
                        <div class="fw-700" style="color:#059669;font-size:.875rem;">Rp {{ number_format($k->gaji,0,',','.') }}</div>
                        <div style="font-size:.7rem;color:#94a3b8;">/bulan</div>
                    </td>
                    <td>
                        <div style="font-size:.82rem;">{{ \Carbon\Carbon::parse($k->tanggal_masuk)->format('d M Y') }}</div>
                        <div style="font-size:.7rem;color:#94a3b8;">{{ \Carbon\Carbon::parse($k->tanggal_masuk)->diffForHumans() }}</div>
                    </td>
                    <td>
                        <form action="{{ route('karyawan-qb.toggle-status', $k->id) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="button" class="badge-status-btn btn-swal-toggle badge-{{ $k->status_kerja=='aktif'?'aktif':'nonaktif' }}"
                                data-nama="{{ $k->nama }}" data-status="{{ $k->status_kerja }}">
                                {{ $k->status_kerja=='aktif'?'● Aktif':'○ Nonaktif' }}
                                <i class="bi bi-arrow-repeat ms-1" style="font-size:.65rem;"></i>
                            </button>
                        </form>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('karyawan-qb.show', $k->id) }}" class="btn-action btn-view"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('karyawan-qb.edit', $k->id) }}" class="btn-action btn-edit"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('karyawan-qb.destroy', $k->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-action btn-delete btn-swal-delete"
                                    data-nama="{{ $k->nama }}" data-type="Karyawan"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8">
                    <div class="empty-state">
                        <div class="empty-icon">🪪</div>
                        <div class="empty-title mb-1">Belum ada data karyawan</div>
                        <a href="{{ route('karyawan-qb.create') }}" class="btn btn-primary-kost btn-sm px-4 mt-2">
                            <i class="bi bi-person-plus-fill me-1"></i>Tambah Karyawan
                        </a>
                    </div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($karyawans->hasPages())
    <div class="px-4 py-3" style="border-top:1px solid var(--border);">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">Menampilkan {{ $karyawans->firstItem() }}–{{ $karyawans->lastItem() }} dari {{ $karyawans->total() }} data</small>
            {{ $karyawans->withQueryString()->links() }}
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.karyawan-avatar{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;flex-shrink:0;}
.karyawan-avatar.jabatan-admin{background:#d1fae5;color:#065f46;}
.karyawan-avatar.jabatan-teknisi{background:#fef3c7;color:#92400e;}
.karyawan-avatar.jabatan-kebersihan{background:#ede9fe;color:#4c1d95;}
.karyawan-avatar.jabatan-keamanan{background:#fee2e2;color:#991b1b;}
.jabatan-badge{display:inline-block;font-size:.72rem;font-weight:700;padding:.28rem .7rem;border-radius:6px;}
.jabatan-badge-admin{background:#d1fae5;color:#065f46;}
.jabatan-badge-teknisi{background:#fef3c7;color:#92400e;}
.jabatan-badge-kebersihan{background:#ede9fe;color:#4c1d95;}
.jabatan-badge-keamanan{background:#fee2e2;color:#991b1b;}
.badge-status-btn{cursor:pointer;border:none;font-size:.72rem;font-weight:700;padding:.3rem .8rem;border-radius:20px;transition:all .2s;display:inline-flex;align-items:center;}
.badge-status-btn:hover{filter:brightness(.92);transform:translateY(-1px);}
.badge-nonaktif{background:#f1f5f9;color:#475569;}
.stat-card.purple{background:linear-gradient(135deg,#7c3aed,#a78bfa);color:#fff;}
</style>
@endpush