{{-- resources/views/karyawan/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Karyawan')
@section('breadcrumb', 'Karyawan / Edit')
@section('method_badge')
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8 col-lg-10">

    {{-- PAGE HEADER --}}
    <div class="page-header-row">
        <div class="page-title-block">
            <h1 class="title">
                ✏️ Edit Karyawan
                <span style="color:#10b981;">{{ $karyawan->nama }}</span>
            </h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('karyawan.show', $karyawan->id) }}" class="btn btn-outline-secondary btn-sm px-3">
                <i class="bi bi-eye me-1"></i>Detail
            </a>
            <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary px-3">
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
            <strong>#{{ $karyawan->id }}</strong>
        </div>
        <div class="px-3 py-2 rounded-3 d-flex align-items-center gap-2"
             style="background:#f8fafc;border:1px solid var(--border);font-size:.8rem;">
            <i class="bi bi-clock text-muted"></i>
            <span class="text-muted">Bergabung:</span>
            <strong>{{ $karyawan->tanggal_masuk->format('d M Y') }}</strong>
            <span class="text-muted">({{ $karyawan->tanggal_masuk->diffForHumans() }})</span>
        </div>
        @php
            $jabatanColors = [
                'admin'      => ['#d1fae5','#065f46'],
                'teknisi'    => ['#fef3c7','#92400e'],
                'kebersihan' => ['#ede9fe','#4c1d95'],
                'keamanan'   => ['#fee2e2','#991b1b'],
            ];
            $c = $jabatanColors[$karyawan->jabatan] ?? ['#f1f5f9','#64748b'];
        @endphp
        <div class="px-3 py-2 rounded-3 d-flex align-items-center gap-2"
             style="background:{{ $c[0] }};border:1px solid {{ $c[0] }};font-size:.8rem;color:{{ $c[1] }};">
            <strong>{{ ucfirst($karyawan->jabatan) }}</strong>
        </div>
        @if($karyawan->status_kerja == 'aktif')
            <span class="badge-status badge-aktif" style="font-size:.78rem;">● Aktif</span>
        @else
            <span class="badge-status badge-selesai" style="font-size:.78rem;">○ Nonaktif</span>
        @endif
    </div>

    <div class="row g-4">

        {{-- ── FORM UTAMA ── --}}
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-card-header" style="background:linear-gradient(135deg,#064e3b,#059669);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-700 text-white" style="font-size:1rem;">Edit Data Karyawan</div>
                            <div style="font-size:.75rem;color:rgba(255,255,255,.6);">Perubahan langsung disimpan ke database</div>
                        </div>
                        <span class="method-pill" style="background:rgba(255,255,255,.15);color:#d1fae5;">
                            <i class="bi bi-arrow-repeat me-1"></i> ->update()
                        </span>
                    </div>
                </div>

                <div class="form-card-body">
                    <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST" id="formEditKaryawan">
                        @csrf @method('PUT')

                        {{-- DATA PRIBADI --}}
                        <div class="form-section-head">👤 Data Pribadi</div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="namaEdit"
                                    class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama', $karyawan->nama) }}">
                                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                                <select name="jabatan" id="jabatanEdit"
                                    class="form-select @error('jabatan') is-invalid @enderror">
                                    <option value="admin"      {{ old('jabatan',$karyawan->jabatan)=='admin'      ?'selected':'' }}>🧑‍💼 Admin</option>
                                    <option value="teknisi"    {{ old('jabatan',$karyawan->jabatan)=='teknisi'    ?'selected':'' }}>🔧 Teknisi</option>
                                    <option value="kebersihan" {{ old('jabatan',$karyawan->jabatan)=='kebersihan' ?'selected':'' }}>🧹 Kebersihan</option>
                                    <option value="keamanan"   {{ old('jabatan',$karyawan->jabatan)=='keamanan'   ?'selected':'' }}>🛡️ Keamanan</option>
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
                                        value="{{ old('no_telepon', $karyawan->no_telepon) }}">
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
                                        value="{{ old('email', $karyawan->email) }}">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" rows="2"
                                    class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $karyawan->alamat) }}</textarea>
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
                                    <input type="number" name="gaji" id="gajiEdit"
                                        class="form-control @error('gaji') is-invalid @enderror"
                                        style="border-radius:0 9px 9px 0;"
                                        value="{{ old('gaji', $karyawan->gaji) }}"
                                        min="0" step="100000">
                                    @error('gaji')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-text" id="gajiEditPreview">
                                    = Rp {{ number_format($karyawan->gaji, 0, ',', '.') }} / bulan
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Masuk Kerja <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_masuk"
                                    class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                    value="{{ old('tanggal_masuk', $karyawan->tanggal_masuk->format('Y-m-d')) }}">
                                @error('tanggal_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status Kerja <span class="text-danger">*</span></label>
                                <select name="status_kerja" class="form-select @error('status_kerja') is-invalid @enderror">
                                    <option value="aktif"    {{ old('status_kerja',$karyawan->status_kerja)=='aktif'    ?'selected':'' }}>🟢 Aktif</option>
                                    <option value="nonaktif" {{ old('status_kerja',$karyawan->status_kerja)=='nonaktif' ?'selected':'' }}>⚪ Nonaktif</option>
                                </select>
                                @error('status_kerja')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- FOOTER --}}
                        <hr class="my-4" style="border-color:var(--border);">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            {{-- Tombol hapus — type="button", bukan nested form --}}
                            <button type="button"
                                    class="btn btn-outline-danger px-4"
                                    id="btnHapusKaryawan"
                                    data-nama="{{ $karyawan->nama }}">
                                <i class="bi bi-trash me-1"></i> Hapus
                            </button>
                            <div class="d-flex gap-2">
                                <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                                <button type="submit" class="btn px-5 fw-700"
                                        style="background:linear-gradient(135deg,#d97706,#f59e0b);color:#fff;
                                               border:none;border-radius:9px;box-shadow:0 4px 14px rgba(245,158,11,.35);">
                                    <i class="bi bi-arrow-repeat me-1"></i> Update
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Form hapus di LUAR form edit, tersembunyi --}}
                    <form id="formHapusKaryawan"
                          action="{{ route('karyawan.destroy', $karyawan->id) }}"
                          method="POST"
                          style="display:none;"
                          onsubmit="return false;">
                        @csrf @method('DELETE')
                    </form>
                </div>
            </div>
        </div>

        {{-- ── SIDE PANEL ── --}}
        <div class="col-lg-4">

            {{-- AVATAR CARD --}}
            <div class="form-card mb-3">
                <div class="form-card-body" style="text-align:center;padding:1.5rem;">
                    <div id="avatarPreviewEdit"
                         style="width:80px;height:80px;border-radius:20px;margin:0 auto .75rem;
                                display:flex;align-items:center;justify-content:center;
                                font-weight:800;font-size:2.2rem;transition:all .3s;
                                background:{{ $c[0] }};color:{{ $c[1] }};">
                        {{ strtoupper(substr($karyawan->nama, 0, 1)) }}
                    </div>
                    <div id="avatarName" class="fw-700" style="font-size:.95rem;">{{ $karyawan->nama }}</div>
                    <div id="avatarJabatan" style="font-size:.8rem;color:var(--text-muted);margin-top:.2rem;">
                        {{ ucfirst($karyawan->jabatan) }}
                    </div>
                </div>
            </div>

            {{-- TOGGLE STATUS --}}
            <div class="form-card mb-3">
                <div class="form-card-body" style="padding:1.25rem;">
                    <div class="form-section-head" style="margin-bottom:.9rem;">⚡ Toggle Status Cepat</div>
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <div>
                            <div style="font-size:.82rem;font-weight:600;">Status Sekarang</div>
                            <div style="font-size:.75rem;color:var(--text-muted);">
                                Klik tombol untuk mengubah status
                            </div>
                        </div>
                        <form action="{{ route('karyawan.toggle-status', $karyawan->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="button"
                                    class="btn btn-swal-toggle fw-700 px-3"
                                    data-nama="{{ $karyawan->nama }}"
                                    data-status="{{ $karyawan->status_kerja }}"
                                    style="border-radius:9px;font-size:.8rem;
                                           background:{{ $karyawan->status_kerja=='aktif' ? '#d1fae5' : '#f1f5f9' }};
                                           color:{{ $karyawan->status_kerja=='aktif' ? '#065f46' : '#475569' }};
                                           border:none;">
                                @if($karyawan->status_kerja == 'aktif')
                                    ● Aktif → Nonaktifkan
                                @else
                                    ○ Nonaktif → Aktifkan
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- INFO CARD --}}
            <div class="form-card">
                <div class="form-card-body" style="padding:1.25rem;">
                    <div class="form-section-head" style="margin-bottom:.9rem;">ℹ️ Info Record</div>
                    <div class="d-flex flex-column gap-2" style="font-size:.8rem;">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Dibuat</span>
                            <span class="fw-600">{{ $karyawan->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Diperbarui</span>
                            <span class="fw-600">{{ $karyawan->updated_at->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Masa Kerja</span>
                            <span class="fw-600">{{ $karyawan->tanggal_masuk->diffForHumans(null, true) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Gaji Saat Ini</span>
                            <span class="fw-700" style="color:#059669;">
                                Rp {{ number_format($karyawan->gaji, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- end col --}}
    </div>{{-- end row --}}

</div>
</div>
@endsection

@push('scripts')
<script>
const jabatanColors = {
    admin:      { bg: '#d1fae5', color: '#065f46' },
    teknisi:    { bg: '#fef3c7', color: '#92400e' },
    kebersihan: { bg: '#ede9fe', color: '#4c1d95' },
    keamanan:   { bg: '#fee2e2', color: '#991b1b' },
};

function updateAvatarEdit() {
    const nama    = document.getElementById('namaEdit').value.trim();
    const jabatan = document.getElementById('jabatanEdit').value;
    const avatar  = document.getElementById('avatarPreviewEdit');
    const c       = jabatanColors[jabatan] || { bg: '#f1f5f9', color: '#94a3b8' };
    avatar.textContent      = nama ? nama.charAt(0).toUpperCase() : '?';
    avatar.style.background = c.bg;
    avatar.style.color      = c.color;
    document.getElementById('avatarName').textContent    = nama || '—';
    document.getElementById('avatarJabatan').textContent =
        jabatan ? jabatan.charAt(0).toUpperCase() + jabatan.slice(1) : '—';
}

document.getElementById('namaEdit').addEventListener('input', updateAvatarEdit);
document.getElementById('jabatanEdit').addEventListener('change', updateAvatarEdit);

// Preview gaji
document.getElementById('gajiEdit').addEventListener('input', function () {
    const v = parseInt(this.value) || 0;
    document.getElementById('gajiEditPreview').textContent =
        v > 0 ? '= Rp ' + v.toLocaleString('id-ID') + ' / bulan' : '—';
});

// SweetAlert konfirmasi UPDATE
document.getElementById('formEditKaryawan').addEventListener('submit', function (e) {
    e.preventDefault();
    const form = this;
    Swal.fire({
        title: 'Update Data Karyawan?',
        text: 'Perubahan akan langsung tersimpan ke database.',
        icon: 'question',
        iconColor: '#f59e0b',
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-arrow-repeat me-1"></i> Ya, Update',
        cancelButtonText: 'Cek Lagi',
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#64748b',
        reverseButtons: true,
    }).then(function (r) {
        if (r.isConfirmed) {
            form.onsubmit = null;
            form.submit();
        }
    });
});

// SweetAlert konfirmasi HAPUS
document.getElementById('btnHapusKaryawan').addEventListener('click', function () {
    const nama      = this.getAttribute('data-nama');
    const formHapus = document.getElementById('formHapusKaryawan');

    Swal.fire({
        title: 'Hapus Karyawan?',
        html: 'Data karyawan <strong>' + nama + '</strong> akan dihapus permanen.<br>Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus',
        cancelButtonText: '<i class="bi bi-x me-1"></i> Batal',
        reverseButtons: true,
        focusCancel: true,
    }).then(function (result) {
        if (result.isConfirmed) {
            formHapus.onsubmit = null;
            formHapus.submit();
        }
    });
});
</script>
@endpush