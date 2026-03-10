@extends('layouts.app')

@section('title', 'Edit Kamar (Eloquent)')
@section('breadcrumb', 'Kamar Eloquent / Edit')
@section('method_badge')
    <span class="method-pill method-pill-el"><i class="bi bi-layers-fill me-1"></i>Eloquent ORM</span>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8 col-lg-10">

    <div class="page-header-row">
        <div class="page-title-block">
            <h1 class="title">✏️ Edit Kamar <span style="color:#10b981;">{{ $kamar->nomor_kamar }}</span></h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('kamar.edit', $kamar->id) }}" class="btn btn-outline-secondary btn-sm px-3">
                <i class="bi bi-arrow-left-right me-1"></i>Versi QB
            </a>
            <a href="{{ route('kamar-el.index') }}" class="btn btn-outline-secondary px-3">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header" style="background:linear-gradient(135deg,#064e3b,#059669);">
            <div class="fw-700 text-white">Edit Kamar</div>
        </div>

        <div class="form-card-body">
            <form action="{{ route('kamar-el.update', $kamar->id) }}" method="POST" id="formEditKamarEl">
                @csrf @method('PUT')

                <div class="form-section-head">📋 Identitas Kamar</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nomor Kamar <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_kamar"
                            class="form-control @error('nomor_kamar') is-invalid @enderror"
                            value="{{ old('nomor_kamar', $kamar->nomor_kamar) }}">
                        @error('nomor_kamar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipe Kamar <span class="text-danger">*</span></label>
                        <select name="tipe_kamar" class="form-select @error('tipe_kamar') is-invalid @enderror">
                            <option value="standar" {{ old('tipe_kamar',$kamar->tipe_kamar)=='standar'?'selected':'' }}>Standar</option>
                            <option value="deluxe"  {{ old('tipe_kamar',$kamar->tipe_kamar)=='deluxe' ?'selected':'' }}>Deluxe</option>
                            <option value="vip"     {{ old('tipe_kamar',$kamar->tipe_kamar)=='vip'    ?'selected':'' }}>VIP</option>
                        </select>
                    </div>
                </div>

                <div class="form-section-head">💰 Harga & Ukuran</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Harga per Bulan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:9px 0 0 9px;">Rp</span>
                            <input type="number" name="harga_bulan" id="hargaEdit"
                                class="form-control" style="border-radius:0 9px 9px 0;"
                                value="{{ old('harga_bulan', $kamar->harga_bulan) }}" min="0" step="50000">
                        </div>
                        <div class="form-text" id="hargaEditPreview">= {{ $kamar->harga_format }} / bulan</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Luas Kamar <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="luas_kamar"
                                class="form-control" style="border-radius:9px 0 0 9px;"
                                value="{{ old('luas_kamar', $kamar->luas_kamar) }}" min="1" step="0.5">
                            <span class="input-group-text" style="border-radius:0 9px 9px 0;">m²</span>
                        </div>
                    </div>
                </div>

                <div class="form-section-head">🛋️ Fasilitas & Status</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Fasilitas</label>
                        <textarea name="fasilitas" class="form-control" rows="3">{{ old('fasilitas', $kamar->fasilitas) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select">
                            <option value="tersedia"  {{ old('status',$kamar->status)=='tersedia'  ?'selected':'' }}>✅ Tersedia</option>
                            <option value="terisi"    {{ old('status',$kamar->status)=='terisi'    ?'selected':'' }}>🔴 Terisi</option>
                            <option value="perbaikan" {{ old('status',$kamar->status)=='perbaikan' ?'selected':'' }}>🔧 Perbaikan</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4" style="border-color:var(--border);">
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <form action="{{ route('kamar-el.destroy', $kamar->id) }}" method="POST" class="m-0">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-swal-delete px-4"
                            data-nama="{{ $kamar->nomor_kamar }}" data-type="Kamar">
                            <i class="bi bi-trash me-1"></i> Hapus
                        </button>
                    </form>
                    <div class="d-flex gap-2">
                        <a href="{{ route('kamar-el.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-success-kost px-5">
                            <i class="bi bi-arrow-repeat me-1"></i> Update
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
document.getElementById('hargaEdit').addEventListener('input', function() {
    const v = parseInt(this.value) || 0;
    document.getElementById('hargaEditPreview').textContent = v > 0 ? '= Rp ' + v.toLocaleString('id-ID') + ' / bulan' : '—';
});
document.getElementById('formEditKamarEl').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    Swal.fire({
        title: 'Simpan perubahan?',
        icon: 'question', iconColor: '#f59e0b',
        showCancelButton: true,
        confirmButtonText: 'Ya, Update',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#f59e0b', cancelButtonColor: '#64748b',
        reverseButtons: true,
    }).then(r => { if (r.isConfirmed) form.submit(); });
});
</script>
@endpush