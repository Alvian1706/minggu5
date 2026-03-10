@extends('layouts.app')
@section('title', 'Tambah Kamar (Eloquent)')
@section('breadcrumb', 'Kamar Eloquent / Tambah')
@section('method_badge')
    <span class="method-pill method-pill-el"><i class="bi bi-layers-fill me-1"></i>Eloquent ORM</span>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8 col-lg-10">

    <div class="page-header-row">
        <div class="page-title-block">
            <h1 class="title">➕ Tambah Kamar <span style="color:#10b981;">(Eloquent)</span></h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('kamar.create') }}" class="btn btn-outline-secondary btn-sm px-3">
                <i class="bi bi-arrow-left-right me-1"></i>Versi QB
            </a>
            <a href="{{ route('kamar-el.index') }}" class="btn btn-outline-secondary px-3">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header green-header">
            <div class="fw-700 text-white">Form Kamar Baru</div>
        </div>

        <div class="form-card-body">
            <form action="{{ route('kamar-el.store') }}" method="POST" id="formKamarEl">
                @csrf

                <div class="form-section-head">📋 Identitas Kamar</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nomor Kamar <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_kamar"
                            class="form-control @error('nomor_kamar') is-invalid @enderror"
                            value="{{ old('nomor_kamar') }}" placeholder="Contoh: A-101">
                        @error('nomor_kamar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipe Kamar <span class="text-danger">*</span></label>
                        <select name="tipe_kamar" class="form-select @error('tipe_kamar') is-invalid @enderror">
                            <option value="">— Pilih Tipe —</option>
                            <option value="standar" {{ old('tipe_kamar')=='standar'?'selected':'' }}>Standar</option>
                            <option value="deluxe"  {{ old('tipe_kamar')=='deluxe' ?'selected':'' }}>Deluxe</option>
                            <option value="vip"     {{ old('tipe_kamar')=='vip'    ?'selected':'' }}>VIP</option>
                        </select>
                        @error('tipe_kamar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-section-head">💰 Harga & Ukuran</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Harga per Bulan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:9px 0 0 9px;">Rp</span>
                            <input type="number" name="harga_bulan" id="hargaInput"
                                class="form-control @error('harga_bulan') is-invalid @enderror"
                                style="border-radius:0 9px 9px 0;"
                                value="{{ old('harga_bulan') }}" min="0" step="50000">
                        </div>
                        <div class="form-text" id="hargaPreview">—</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Luas Kamar <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="luas_kamar"
                                class="form-control @error('luas_kamar') is-invalid @enderror"
                                style="border-radius:9px 0 0 9px;"
                                value="{{ old('luas_kamar') }}" min="1" step="0.5">
                            <span class="input-group-text" style="border-radius:0 9px 9px 0;">m²</span>
                        </div>
                    </div>
                </div>

                <div class="form-section-head">🛋️ Fasilitas & Status</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Fasilitas</label>
                        <textarea name="fasilitas" class="form-control" rows="3"
                            placeholder="AC, WiFi, KM Dalam, Lemari...">{{ old('fasilitas') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="tersedia" {{ old('status','tersedia')=='tersedia'?'selected':'' }}>✅ Tersedia</option>
                            <option value="perbaikan" {{ old('status')=='perbaikan'?'selected':'' }}>🔧 Perbaikan</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4" style="border-color:var(--border);">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('kamar-el.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-success-kost px-5">
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
document.getElementById('hargaInput').addEventListener('input', function() {
    const v = parseInt(this.value) || 0;
    document.getElementById('hargaPreview').textContent = v > 0 ? '= Rp ' + v.toLocaleString('id-ID') + ' / bulan' : '—';
});
document.getElementById('formKamarEl').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    Swal.fire({
        title: 'Simpan kamar baru?',
        icon: 'question', iconColor: '#10b981',
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#059669', cancelButtonColor: '#64748b',
        reverseButtons: true,
    }).then(r => { if (r.isConfirmed) form.submit(); });
});
</script>
@endpush