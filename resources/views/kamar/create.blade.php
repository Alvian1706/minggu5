{{-- resources/views/kamar/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Kamar')
@section('breadcrumb', 'Kamar / Tambah')
@section('method_badge')
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8 col-lg-10">

    {{-- PAGE HEADER --}}
    <div class="page-header-row">
        <div class="page-title-block">
            <h1 class="title">➕ Tambah Kamar</h1>
        </div>
        <a href="{{ route('kamar.index') }}" class="btn btn-outline-secondary px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="form-card">
        {{-- FORM HEADER --}}
        <div class="form-card-header blue-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="fw-700 text-white" style="font-size:1rem;">Form Data Kamar Baru</div>
                    <div style="font-size:.75rem;color:rgba(255,255,255,.6);">Semua kolom bertanda * wajib diisi</div>
                </div>
            </div>
        </div>

        <div class="form-card-body">
            <form action="{{ route('kamar.store') }}" method="POST" id="formKamar">
                @csrf

                {{-- IDENTITAS --}}
                <div class="form-section-head">📋 Identitas Kamar</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nomor Kamar <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_kamar"
                            class="form-control @error('nomor_kamar') is-invalid @enderror"
                            value="{{ old('nomor_kamar') }}"
                            placeholder="Contoh: A-101">
                        @error('nomor_kamar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format: Huruf-Angka (contoh: A-101, B-202)</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipe Kamar <span class="text-danger">*</span></label>
                        <select name="tipe_kamar" class="form-select @error('tipe_kamar') is-invalid @enderror" id="tipeSelect">
                            <option value="">— Pilih Tipe —</option>
                            <option value="standar" {{ old('tipe_kamar')=='standar'?'selected':'' }}>Standar</option>
                            <option value="deluxe"  {{ old('tipe_kamar')=='deluxe' ?'selected':'' }}>Deluxe</option>
                            <option value="vip"     {{ old('tipe_kamar')=='vip'    ?'selected':'' }}>VIP</option>
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
                            <input type="number" name="harga_bulan" id="hargaInput"
                                class="form-control @error('harga_bulan') is-invalid @enderror"
                                style="border-radius:0 9px 9px 0;"
                                value="{{ old('harga_bulan') }}"
                                placeholder="500000" min="0" step="50000">
                            @error('harga_bulan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text" id="hargaPreview">—</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Luas Kamar <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="luas_kamar"
                                class="form-control @error('luas_kamar') is-invalid @enderror"
                                style="border-radius:9px 0 0 9px;"
                                value="{{ old('luas_kamar') }}"
                                placeholder="12" min="1" step="0.5">
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
                            rows="3"
                            placeholder="Contoh: AC, WiFi, Kamar Mandi Dalam, Lemari, Meja Belajar, TV...">{{ old('fasilitas') }}</textarea>
                        @error('fasilitas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status Awal <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="">— Pilih Status —</option>
                            <option value="tersedia"  {{ old('status','tersedia')=='tersedia' ?'selected':'' }}>✅ Tersedia</option>
                            <option value="perbaikan" {{ old('status')=='perbaikan'            ?'selected':'' }}>🔧 Perbaikan</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- FOOTER --}}
                <hr class="my-4" style="border-color:var(--border);">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('kamar.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary-kost px-5">
                        <i class="bi bi-save me-1"></i> Simpan Kamar
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
// Live preview harga
document.getElementById('hargaInput').addEventListener('input', function() {
    const v = parseInt(this.value) || 0;
    document.getElementById('hargaPreview').textContent =
        v > 0 ? '= Rp ' + v.toLocaleString('id-ID') + ' / bulan' : '—';
});

// Konfirmasi submit SweetAlert
document.getElementById('formKamar').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    Swal.fire({
        title: 'Simpan Kamar?',
        text: 'Pastikan semua data sudah benar.',
        icon: 'question',
        iconColor: '#3b82f6',
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-save me-1"></i> Ya, Simpan',
        cancelButtonText: 'Cek Lagi',
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#64748b',
        reverseButtons: true,
    }).then(result => { if (result.isConfirmed) form.submit(); });
});
</script>
@endpush