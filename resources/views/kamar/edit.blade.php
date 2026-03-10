{{-- resources/views/kamar/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Kamar ' . $kamar->nomor_kamar)
@section('breadcrumb', 'Kamar / Edit')
@section('method_badge')
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8 col-lg-10">

    {{-- PAGE HEADER --}}
    <div class="page-header-row">
        <div class="page-title-block">
            <h1 class="title">
                ✏️ Edit Kamar
                <span style="color:#3b82f6;">{{ $kamar->nomor_kamar }}</span>
            </h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('kamar.show', $kamar->id) }}" class="btn btn-outline-secondary btn-sm px-3">
                <i class="bi bi-eye me-1"></i>Detail
            </a>
            <a href="{{ route('kamar.index') }}" class="btn btn-outline-secondary px-3">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    {{-- META INFO --}}
    <div class="d-flex gap-3 mb-4 flex-wrap">
        <div class="px-3 py-2 rounded-3 d-flex align-items-center gap-2"
             style="background:#f8fafc;border:1px solid var(--border);font-size:.8rem;">
            <i class="bi bi-hash text-muted"></i>
            <span class="text-muted">ID:</span>
            <strong>#{{ $kamar->id }}</strong>
        </div>
        <div class="px-3 py-2 rounded-3 d-flex align-items-center gap-2"
             style="background:#f8fafc;border:1px solid var(--border);font-size:.8rem;">
            <i class="bi bi-clock text-muted"></i>
            <span class="text-muted">Dibuat:</span>
            <strong>{{ \Carbon\Carbon::parse($kamar->created_at)->format('d M Y, H:i') }}</strong>
        </div>
        <div class="px-3 py-2 rounded-3 d-flex align-items-center gap-2"
             style="background:#fffbeb;border:1px solid #fde68a;font-size:.8rem;">
            <i class="bi bi-pencil-square" style="color:#d97706;"></i>
            <span style="color:#92400e;">Update:</span>
            <strong style="color:#92400e;">{{ \Carbon\Carbon::parse($kamar->updated_at)->format('d M Y, H:i') }}</strong>
        </div>
    </div>

  

    <div class="form-card">
        {{-- HEADER --}}
        <div class="form-card-header" style="background:linear-gradient(135deg,#1e3a8a,#1d4ed8);">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="fw-700 text-white" style="font-size:1rem;">Edit Data Kamar</div>
                    <div style="font-size:.75rem;color:rgba(255,255,255,.6);">Perubahan akan langsung tersimpan ke database</div>
                </div>
            </div>
        </div>

        <div class="form-card-body">
            {{-- FORM EDIT (satu-satunya form di sini) --}}
            <form action="{{ route('kamar.update', $kamar->id) }}" method="POST" id="formEditKamar">
                @csrf @method('PUT')

                {{-- IDENTITAS --}}
                <div class="form-section-head">📋 Identitas Kamar</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nomor Kamar <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_kamar"
                            class="form-control @error('nomor_kamar') is-invalid @enderror"
                            value="{{ old('nomor_kamar', $kamar->nomor_kamar) }}">
                        @error('nomor_kamar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipe Kamar <span class="text-danger">*</span></label>
                        <select name="tipe_kamar" class="form-select @error('tipe_kamar') is-invalid @enderror">
                            <option value="standar" {{ old('tipe_kamar',$kamar->tipe_kamar)=='standar'?'selected':'' }}>Standar</option>
                            <option value="deluxe"  {{ old('tipe_kamar',$kamar->tipe_kamar)=='deluxe' ?'selected':'' }}>Deluxe</option>
                            <option value="vip"     {{ old('tipe_kamar',$kamar->tipe_kamar)=='vip'    ?'selected':'' }}>VIP</option>
                        </select>
                        @error('tipe_kamar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- HARGA & LUAS --}}
                <div class="form-section-head">💰 Harga & Ukuran</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Harga per Bulan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:9px 0 0 9px;">Rp</span>
                            <input type="number" name="harga_bulan" id="hargaEdit"
                                class="form-control @error('harga_bulan') is-invalid @enderror"
                                style="border-radius:0 9px 9px 0;"
                                value="{{ old('harga_bulan', $kamar->harga_bulan) }}"
                                min="0" step="50000">
                            @error('harga_bulan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text" id="hargaEditPreview">
                            = Rp {{ number_format($kamar->harga_bulan, 0, ',', '.') }} / bulan
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Luas Kamar <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="luas_kamar"
                                class="form-control @error('luas_kamar') is-invalid @enderror"
                                style="border-radius:9px 0 0 9px;"
                                value="{{ old('luas_kamar', $kamar->luas_kamar) }}"
                                min="1" step="0.5">
                            <span class="input-group-text" style="border-radius:0 9px 9px 0;">m²</span>
                            @error('luas_kamar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- FASILITAS & STATUS --}}
                <div class="form-section-head">🛋️ Fasilitas & Status</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Fasilitas</label>
                        <textarea name="fasilitas"
                            class="form-control @error('fasilitas') is-invalid @enderror"
                            rows="3">{{ old('fasilitas', $kamar->fasilitas) }}</textarea>
                        @error('fasilitas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="tersedia"  {{ old('status',$kamar->status)=='tersedia'  ?'selected':'' }}>✅ Tersedia</option>
                            <option value="terisi"    {{ old('status',$kamar->status)=='terisi'    ?'selected':'' }}>🔴 Terisi</option>
                            <option value="perbaikan" {{ old('status',$kamar->status)=='perbaikan' ?'selected':'' }}>🔧 Perbaikan</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- FOOTER --}}
                <hr class="my-4" style="border-color:var(--border);">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    {{-- Tombol Hapus — BUKAN nested form, pakai JS submit form hapus terpisah --}}
                    <button type="button"
                            class="btn btn-outline-danger px-4"
                            id="btnHapusKamar"
                            data-nama="{{ $kamar->nomor_kamar }}">
                        <i class="bi bi-trash me-1"></i> Hapus Kamar
                    </button>

                    <div class="d-flex gap-2">
                        <a href="{{ route('kamar.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-warning px-5 fw-700" id="btnUpdate"
                                style="border-radius:9px;color:#fff;background:linear-gradient(135deg,#d97706,#f59e0b);border:none;box-shadow:0 4px 14px rgba(245,158,11,.35);">
                            <i class="bi bi-arrow-repeat me-1"></i> Update Kamar
                        </button>
                    </div>
                </div>
            </form>

            {{-- Form hapus diletakkan DI LUAR form edit, tersembunyi --}}
            <form id="formHapusKamar"
                  action="{{ route('kamar.destroy', $kamar->id) }}"
                  method="POST"
                  style="display:none;"
                  onsubmit="return false;">
                @csrf @method('DELETE')
            </form>
        </div>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
// Live preview harga
document.getElementById('hargaEdit').addEventListener('input', function () {
    const v = parseInt(this.value) || 0;
    document.getElementById('hargaEditPreview').textContent =
        v > 0 ? '= Rp ' + v.toLocaleString('id-ID') + ' / bulan' : '—';
});

// SweetAlert konfirmasi UPDATE
document.getElementById('formEditKamar').addEventListener('submit', function (e) {
    e.preventDefault();
    const form = this;
    Swal.fire({
        title: 'Update Data Kamar?',
        text: 'Perubahan akan langsung disimpan ke database.',
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
document.getElementById('btnHapusKamar').addEventListener('click', function () {
    const nama      = this.getAttribute('data-nama');
    const formHapus = document.getElementById('formHapusKamar');

    Swal.fire({
        title: 'Hapus Kamar?',
        html: 'Data kamar <strong>' + nama + '</strong> akan dihapus permanen.<br>Tindakan ini tidak dapat dibatalkan.',
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