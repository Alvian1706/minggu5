@extends('layouts.app')

@section('title', 'Edit Penyewa')
@section('breadcrumb', 'Penyewa / Edit')
@section('method_badge')
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-9 col-lg-11">

    {{-- PAGE HEADER --}}
    <div class="page-header-row">
        <div class="page-title-block">
            <h1 class="title">
                ✏️ Edit Penyewa
                <span style="color:#10b981;">{{ $penyewa->nama_penyewa }}</span>
            </h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('penyewa.show', $penyewa->id) }}" class="btn btn-outline-secondary btn-sm px-3">
                <i class="bi bi-eye me-1"></i>Detail
            </a>
            <a href="{{ route('penyewa.index') }}" class="btn btn-outline-secondary px-3">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    {{-- META INFO --}}
    <div class="d-flex gap-3 mb-4 flex-wrap align-items-center">
        <div class="px-3 py-2 rounded-3 d-flex align-items-center gap-2"
             style="background:#f8fafc;border:1px solid var(--border);font-size:.8rem;">
            <i class="bi bi-hash text-muted"></i>
            <span class="text-muted">ID:</span>
            <strong>#{{ $penyewa->id }}</strong>
        </div>
        <div class="px-3 py-2 rounded-3 d-flex align-items-center gap-2"
             style="background:#f8fafc;border:1px solid var(--border);font-size:.8rem;">
            <i class="bi bi-door-open text-muted"></i>
            <span class="text-muted">Kamar saat ini:</span>
            <strong>{{ $penyewa->kamar->nomor_kamar ?? '—' }}</strong>
        </div>
        <span class="badge-status badge-{{ $penyewa->status }}" style="font-size:.78rem;">
            {{ ucfirst($penyewa->status) }}
        </span>
        @if($penyewa->status == 'aktif')
            <div class="px-3 py-2 rounded-3 d-flex align-items-center gap-2"
                 style="background:#fffbeb;border:1px solid #fde68a;font-size:.8rem;">
                <i class="bi bi-clock" style="color:#d97706;"></i>
                <span style="color:#92400e;">Sisa:</span>
                <strong style="color:#92400e;">{{ $penyewa->sisa_hari }} hari</strong>
            </div>
        @endif
    </div>

    <div class="form-card">
        {{-- HEADER --}}
        <div class="form-card-header" style="background:linear-gradient(135deg,#064e3b,#059669);">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="fw-700 text-white" style="font-size:1rem;">Edit Data Penyewa</div>
                    <div style="font-size:.75rem;color:rgba(255,255,255,.6);">
                        Perubahan kamar akan otomatis memperbarui status kamar lama & baru
                    </div>
                </div>
                
            </div>
        </div>

        <div class="form-card-body">
            <form action="{{ route('penyewa.update', $penyewa->id) }}" method="POST" id="formEditPenyewa">
                @csrf @method('PUT')

                {{-- DATA PRIBADI --}}
                <div class="form-section-head">👤 Data Pribadi</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_penyewa"
                            class="form-control @error('nama_penyewa') is-invalid @enderror"
                            value="{{ old('nama_penyewa', $penyewa->nama_penyewa) }}">
                        @error('nama_penyewa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. KTP / NIK <span class="text-danger">*</span></label>
                        <input type="text" name="no_ktp"
                            class="form-control @error('no_ktp') is-invalid @enderror"
                            value="{{ old('no_ktp', $penyewa->no_ktp) }}"
                            maxlength="16"
                            style="font-family:'DM Mono',monospace;letter-spacing:1px;">
                        @error('no_ktp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:9px 0 0 9px;"><i class="bi bi-phone"></i></span>
                            <input type="text" name="no_telepon"
                                class="form-control @error('no_telepon') is-invalid @enderror"
                                style="border-radius:0 9px 9px 0;"
                                value="{{ old('no_telepon', $penyewa->no_telepon) }}">
                            @error('no_telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:9px 0 0 9px;"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                style="border-radius:0 9px 9px 0;"
                                value="{{ old('email', $penyewa->email) }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- DATA SEWA --}}
                <div class="form-section-head">🏠 Data Sewa</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Kamar <span class="text-danger">*</span></label>
                        <select name="kamar_id" id="kamarEditSelect"
                            class="form-select @error('kamar_id') is-invalid @enderror">
                            @foreach($kamars as $k)
                                <option value="{{ $k->id }}"
                                    data-harga="{{ $k->harga_bulan }}"
                                    {{ old('kamar_id',$penyewa->kamar_id)==$k->id?'selected':'' }}>
                                    Kamar {{ $k->nomor_kamar }} — {{ ucfirst($k->tipe_kamar) }}
                                    (Rp {{ number_format($k->harga_bulan,0,',','.') }}/bln)
                                </option>
                            @endforeach
                        </select>
                        @error('kamar_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">PIC Karyawan <span class="text-danger">*</span></label>
                        <select name="karyawan_id" class="form-select @error('karyawan_id') is-invalid @enderror">
                            @foreach($karyawans as $k)
                                <option value="{{ $k->id }}"
                                    {{ old('karyawan_id',$penyewa->karyawan_id)==$k->id?'selected':'' }}>
                                    {{ $k->nama }} — {{ ucfirst($k->jabatan) }}
                                </option>
                            @endforeach
                        </select>
                        @error('karyawan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_masuk"
                            class="form-control @error('tanggal_masuk') is-invalid @enderror"
                            value="{{ old('tanggal_masuk', $penyewa->tanggal_masuk->format('Y-m-d')) }}">
                        @error('tanggal_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Lama Sewa (Bulan) <span class="text-danger">*</span></label>
                        <input type="number" name="lama_sewa" id="lamaSewaEdit"
                            class="form-control @error('lama_sewa') is-invalid @enderror"
                            value="{{ old('lama_sewa', $penyewa->lama_sewa) }}" min="1" max="24">
                        @error('lama_sewa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Uang Deposit (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:9px 0 0 9px;">Rp</span>
                            <input type="number" name="uang_deposit"
                                class="form-control @error('uang_deposit') is-invalid @enderror"
                                style="border-radius:0 9px 9px 0;"
                                value="{{ old('uang_deposit', $penyewa->uang_deposit) }}" min="0" step="50000">
                            @error('uang_deposit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status Kontrak <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="aktif"   {{ old('status',$penyewa->status)=='aktif'  ?'selected':'' }}>🟢 Aktif</option>
                            <option value="selesai" {{ old('status',$penyewa->status)=='selesai'?'selected':'' }}>⚪ Selesai</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2">{{ old('catatan', $penyewa->catatan) }}</textarea>
                    </div>
                </div>

                {{-- FOOTER --}}
                <hr class="my-4" style="border-color:var(--border);">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    {{-- Hapus (hanya jika selesai) --}}
                    @if($penyewa->status === 'selesai')
                    <form action="{{ route('penyewa.destroy', $penyewa->id) }}" method="POST" class="m-0">
                        @csrf @method('DELETE')
                        <button type="button"
                                class="btn btn-outline-danger btn-swal-delete px-4"
                                data-nama="{{ $penyewa->nama_penyewa }}"
                                data-type="Penyewa">
                            <i class="bi bi-trash me-1"></i> Hapus Data
                        </button>
                    </form>
                    @else
                    <div></div>
                    @endif

                    <div class="d-flex gap-2">
                        <a href="{{ route('penyewa.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                        <button type="submit" class="btn px-5 fw-700" id="btnUpdate"
                                style="background:linear-gradient(135deg,#d97706,#f59e0b);color:#fff;border:none;border-radius:9px;box-shadow:0 4px 14px rgba(245,158,11,.35);">
                            <i class="bi bi-arrow-repeat me-1"></i> Update Penyewa
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
// Konfirmasi update
document.getElementById('formEditPenyewa').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    Swal.fire({
        title: 'Update Data Penyewa?',
        text: 'Perubahan kamar akan otomatis memperbarui status kamar.',
        icon: 'question',
        iconColor: '#f59e0b',
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-arrow-repeat me-1"></i> Ya, Update',
        cancelButtonText: 'Cek Lagi',
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#64748b',
        reverseButtons: true,
    }).then(r => { if (r.isConfirmed) form.submit(); });
});
</script>
@endpush