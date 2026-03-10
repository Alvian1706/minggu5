@extends('layouts.app')

@section('title', 'Tambah Karyawan (QB)')
@section('breadcrumb', 'Karyawan QB / Tambah')
@section('method_badge')
    <span class="method-pill method-pill-qb"><i class="bi bi-database-fill me-1"></i>Query Builder</span>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8 col-lg-10">

    <div class="page-header-row">
        <div class="page-title-block">
            <h1 class="title">➕ Tambah Karyawan <span style="color:#3b82f6;">(QB)</span></h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('karyawan.create') }}" class="btn btn-outline-secondary btn-sm px-3">
                <i class="bi bi-arrow-left-right me-1"></i>Versi Eloquent
            </a>
            <a href="{{ route('karyawan-qb.index') }}" class="btn btn-outline-secondary px-3">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header blue-header">
            <div class="fw-700 text-white">Form Karyawan Baru</div>
        </div>

        <div class="form-card-body">
            <form action="{{ route('karyawan-qb.store') }}" method="POST" id="formKaryawanQB">
                @csrf

                <div class="d-flex justify-content-center mb-4">
                    <div id="avatarPreview"
                         style="width:72px;height:72px;border-radius:18px;display:flex;align-items:center;justify-content:center;
                                font-weight:800;font-size:2rem;background:#f1f5f9;color:#94a3b8;transition:all .3s;">?</div>
                </div>

                <div class="form-section-head">👤 Data Pribadi</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="namaInput"
                            class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama') }}" placeholder="Nama lengkap karyawan">
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
                            <span class="input-group-text" style="border-radius:9px 0 0 9px;"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="no_telepon"
                                class="form-control @error('no_telepon') is-invalid @enderror"
                                style="border-radius:0 9px 9px 0;"
                                value="{{ old('no_telepon') }}" placeholder="08xxxxxxxxxx">
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
                                value="{{ old('email') }}" placeholder="opsional">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" rows="2" class="form-control"
                            placeholder="Alamat karyawan...">{{ old('alamat') }}</textarea>
                    </div>
                </div>

                <div class="form-section-head">💼 Data Pekerjaan</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Gaji per Bulan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:9px 0 0 9px;">Rp</span>
                            <input type="number" name="gaji" id="gajiInput"
                                class="form-control @error('gaji') is-invalid @enderror"
                                style="border-radius:0 9px 9px 0;"
                                value="{{ old('gaji') }}" min="0" step="100000">
                        </div>
                        <div class="form-text" id="gajiPreview">—</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_masuk"
                            class="form-control @error('tanggal_masuk') is-invalid @enderror"
                            value="{{ old('tanggal_masuk', date('Y-m-d')) }}">
                        @error('tanggal_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status Kerja <span class="text-danger">*</span></label>
                        <select name="status_kerja" class="form-select">
                            <option value="aktif"    {{ old('status_kerja','aktif')=='aktif'   ?'selected':'' }}>🟢 Aktif</option>
                            <option value="nonaktif" {{ old('status_kerja')=='nonaktif'         ?'selected':'' }}>⚪ Nonaktif</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4" style="border-color:var(--border);">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('karyawan-qb.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-primary-kost px-5">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
const jabatanColors = {
    admin:{bg:'#d1fae5',color:'#065f46'},teknisi:{bg:'#fef3c7',color:'#92400e'},
    kebersihan:{bg:'#ede9fe',color:'#4c1d95'},keamanan:{bg:'#fee2e2',color:'#991b1b'},
};
function updateAvatar() {
    const nama = document.getElementById('namaInput').value.trim();
    const jabatan = document.getElementById('jabatanSelect').value;
    const avatar = document.getElementById('avatarPreview');
    const c = jabatanColors[jabatan] || {bg:'#f1f5f9',color:'#94a3b8'};
    avatar.textContent = nama ? nama.charAt(0).toUpperCase() : '?';
    avatar.style.background = c.bg; avatar.style.color = c.color;
}
document.getElementById('namaInput').addEventListener('input', updateAvatar);
document.getElementById('jabatanSelect').addEventListener('change', updateAvatar);
document.getElementById('gajiInput').addEventListener('input', function() {
    const v = parseInt(this.value)||0;
    document.getElementById('gajiPreview').textContent = v>0 ? '= Rp '+v.toLocaleString('id-ID')+' / bulan' : '—';
});
document.getElementById('formKaryawanQB').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    Swal.fire({
        title: 'Simpan karyawan baru?',
        icon:'question', iconColor:'#3b82f6',
        showCancelButton:true,
        confirmButtonText:'Ya, Simpan',
        cancelButtonText:'Batal',
        confirmButtonColor:'#2563eb', cancelButtonColor:'#64748b',
        reverseButtons:true,
    }).then(r=>{ if(r.isConfirmed) form.submit(); });
});
</script>
@endpush