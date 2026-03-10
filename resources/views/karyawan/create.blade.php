{{-- resources/views/karyawan/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Karyawan')
@section('breadcrumb', 'Karyawan / Tambah')
@section('method_badge')
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8 col-lg-10">

    {{-- PAGE HEADER --}}
    <div class="page-header-row">
        <div class="page-title-block">
            <h1 class="title">➕ Tambah Karyawan</h1>
        </div>
        <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="form-card">
        {{-- HEADER --}}
        <div class="form-card-header green-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="fw-700 text-white" style="font-size:1rem;">Form Data Karyawan Baru</div>
                    <div style="font-size:.75rem;color:rgba(255,255,255,.6);">Semua kolom bertanda * wajib diisi</div>
                </div>
            </div>
        </div>

        <div class="form-card-body">
            <form action="{{ route('karyawan.store') }}" method="POST" id="formKaryawan">
                @csrf

                {{-- PREVIEW AVATAR --}}
                <div class="d-flex justify-content-center mb-4">
                    <div id="avatarPreview"
                         style="width:72px;height:72px;border-radius:18px;display:flex;align-items:center;
                                justify-content:center;font-weight:800;font-size:2rem;letter-spacing:-1px;
                                background:#f1f5f9;color:#94a3b8;transition:all .3s;">
                        ?
                    </div>
                </div>

                {{-- DATA PRIBADI --}}
                <div class="form-section-head">👤 Data Pribadi</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="namaInput"
                            class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama') }}"
                            placeholder="Nama lengkap karyawan">
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <select name="jabatan" id="jabatanSelect"
                            class="form-select @error('jabatan') is-invalid @enderror">
                            <option value="">— Pilih Jabatan —</option>
                            <option value="admin"      {{ old('jabatan')=='admin'      ?'selected':'' }}>🧑‍💼 Admin</option>
                            <option value="teknisi"    {{ old('jabatan')=='teknisi'    ?'selected':'' }}>🔧 Teknisi</option>
                            <option value="kebersihan" {{ old('jabatan')=='kebersihan' ?'selected':'' }}>🧹 Kebersihan</option>
                            <option value="keamanan"   {{ old('jabatan')=='keamanan'   ?'selected':'' }}>🛡️ Keamanan</option>
                        </select>
                        @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:9px 0 0 9px;">
                                <i class="bi bi-telephone"></i>
                            </span>
                            <input type="text" name="no_telepon"
                                class="form-control @error('no_telepon') is-invalid @enderror"
                                style="border-radius:0 9px 9px 0;"
                                value="{{ old('no_telepon') }}"
                                placeholder="08xxxxxxxxxx">
                            @error('no_telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:9px 0 0 9px;">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                style="border-radius:0 9px 9px 0;"
                                value="{{ old('email') }}"
                                placeholder="email@contoh.com (opsional)">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" rows="2"
                            class="form-control @error('alamat') is-invalid @enderror"
                            placeholder="Alamat tempat tinggal karyawan...">{{ old('alamat') }}</textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- DATA KERJA --}}
                <div class="form-section-head">💼 Data Pekerjaan</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Gaji per Bulan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:9px 0 0 9px;">Rp</span>
                            <input type="number" name="gaji" id="gajiInput"
                                class="form-control @error('gaji') is-invalid @enderror"
                                style="border-radius:0 9px 9px 0;"
                                value="{{ old('gaji') }}"
                                placeholder="2000000" min="0" step="100000">
                            @error('gaji')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-text" id="gajiPreview">—</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Masuk Kerja <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_masuk"
                            class="form-control @error('tanggal_masuk') is-invalid @enderror"
                            value="{{ old('tanggal_masuk', date('Y-m-d')) }}">
                        @error('tanggal_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status Kerja <span class="text-danger">*</span></label>
                        <select name="status_kerja" class="form-select @error('status_kerja') is-invalid @enderror">
                            <option value="aktif"    {{ old('status_kerja','aktif')=='aktif'    ?'selected':'' }}>🟢 Aktif</option>
                            <option value="nonaktif" {{ old('status_kerja')=='nonaktif'          ?'selected':'' }}>⚪ Nonaktif</option>
                        </select>
                        @error('status_kerja')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- FOOTER --}}
                <hr class="my-4" style="border-color:var(--border);">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-success-kost px-5" id="btnSimpan">
                        <i class="bi bi-person-check-fill me-1"></i> Simpan Karyawan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
</div>
@endsection

@push('styles')
<style>
.jabatan-colors {
    admin:      { bg: #d1fae5; color: #065f46; }
    teknisi:    { bg: #fef3c7; color: #92400e; }
    kebersihan: { bg: #ede9fe; color: #4c1d95; }
    keamanan:   { bg: #fee2e2; color: #991b1b; }
}
</style>
@endpush

@push('scripts')
<script>
const jabatanColors = {
    admin:      { bg: '#d1fae5', color: '#065f46' },
    teknisi:    { bg: '#fef3c7', color: '#92400e' },
    kebersihan: { bg: '#ede9fe', color: '#4c1d95' },
    keamanan:   { bg: '#fee2e2', color: '#991b1b' },
};

// Update avatar preview
function updateAvatar() {
    const nama    = document.getElementById('namaInput').value.trim();
    const jabatan = document.getElementById('jabatanSelect').value;
    const avatar  = document.getElementById('avatarPreview');
    const inisial = nama ? nama.charAt(0).toUpperCase() : '?';
    const c = jabatanColors[jabatan] || { bg: '#f1f5f9', color: '#94a3b8' };
    avatar.textContent      = inisial;
    avatar.style.background = c.bg;
    avatar.style.color      = c.color;
}

document.getElementById('namaInput').addEventListener('input', updateAvatar);
document.getElementById('jabatanSelect').addEventListener('change', updateAvatar);

// Preview gaji
document.getElementById('gajiInput').addEventListener('input', function() {
    const v = parseInt(this.value) || 0;
    document.getElementById('gajiPreview').textContent =
        v > 0 ? '= Rp ' + v.toLocaleString('id-ID') + ' / bulan' : '—';
});

// SweetAlert konfirmasi submit
document.getElementById('formKaryawan').addEventListener('submit', function(e) {
    e.preventDefault();
    const form    = this;
    const nama    = document.getElementById('namaInput').value || '...';
    const sel     = document.getElementById('jabatanSelect');
    const jabatan = sel.options[sel.selectedIndex]?.text || '—';
    const gaji    = parseInt(document.getElementById('gajiInput').value) || 0;
    const c       = jabatanColors[sel.value] || { bg: '#f1f5f9', color: '#065f46' };

    Swal.fire({
        title: 'Simpan Karyawan?',
        html: `
            <div style="display:flex;flex-direction:column;align-items:center;gap:.75rem;padding:.5rem 0;">
                <div style="width:60px;height:60px;border-radius:15px;background:${c.bg};color:${c.color};
                            display:flex;align-items:center;justify-content:center;
                            font-weight:800;font-size:1.8rem;">
                    ${nama.charAt(0).toUpperCase()}
                </div>
                <div style="text-align:left;font-size:.9rem;line-height:2;width:100%;">
                    <div><b>Nama:</b> ${nama}</div>
                    <div><b>Jabatan:</b> ${jabatan}</div>
                    <div><b>Gaji:</b> <span style="color:#059669;font-weight:700;">
                        Rp ${gaji.toLocaleString('id-ID')}
                    </span>/bulan</div>
                </div>
            </div>
        `,
        icon: 'question',
        iconColor: '#10b981',
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-person-check-fill me-1"></i> Ya, Simpan',
        cancelButtonText: 'Cek Lagi',
        confirmButtonColor: '#059669',
        cancelButtonColor: '#64748b',
        reverseButtons: true,
    }).then(r => { if (r.isConfirmed) form.submit(); });
});
</script>
@endpush