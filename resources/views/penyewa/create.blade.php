@extends('layouts.app')

@section('title', 'Daftar Penyewa Baru')
@section('breadcrumb', 'Penyewa / Daftar Baru')
@section('method_badge')
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-9 col-lg-11">

    {{-- PAGE HEADER --}}
    <div class="page-header-row">
        <div class="page-title-block">
            <h1 class="title">👤 Daftar Penyewa Baru</h1>
        </div>
        <a href="{{ route('penyewa.index') }}" class="btn btn-outline-secondary px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="form-card">
        {{-- HEADER --}}
        <div class="form-card-header green-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="fw-700 text-white" style="font-size:1rem;">Form Pendaftaran Penyewa</div>
                    <div style="font-size:.75rem;color:rgba(255,255,255,.6);">Status kamar otomatis berubah menjadi "Terisi"</div>
                </div>
            </div>
        </div>

        <div class="form-card-body">
            <form action="{{ route('penyewa.store') }}" method="POST" id="formPenyewa">
                @csrf

                {{-- DATA PRIBADI --}}
                <div class="form-section-head">👤 Data Pribadi Penyewa</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_penyewa"
                            class="form-control @error('nama_penyewa') is-invalid @enderror"
                            value="{{ old('nama_penyewa') }}"
                            placeholder="Nama sesuai KTP">
                        @error('nama_penyewa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. KTP / NIK <span class="text-danger">*</span></label>
                        <input type="text" name="no_ktp"
                            class="form-control @error('no_ktp') is-invalid @enderror"
                            value="{{ old('no_ktp') }}"
                            placeholder="16 digit NIK" maxlength="16"
                            style="font-family:'DM Mono',monospace;letter-spacing:1px;">
                        @error('no_ktp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:9px 0 0 9px;">
                                <i class="bi bi-phone"></i>
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
                                placeholder="email@contoh.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- DATA SEWA --}}
                <div class="form-section-head">🏠 Data Sewa Kamar</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Pilih Kamar <span class="text-danger">*</span></label>
                        <select name="kamar_id" id="kamarSelect"
                            class="form-select @error('kamar_id') is-invalid @enderror">
                            <option value="">— Kamar Tersedia —</option>
                            @foreach($kamars as $kamar)
                                <option value="{{ $kamar->id }}"
                                    data-harga="{{ $kamar->harga_bulan }}"
                                    data-tipe="{{ $kamar->tipe_kamar }}"
                                    {{ old('kamar_id')==$kamar->id?'selected':'' }}>
                                    Kamar {{ $kamar->nomor_kamar }} — {{ ucfirst($kamar->tipe_kamar) }}
                                    (Rp {{ number_format($kamar->harga_bulan,0,',','.') }}/bln)
                                </option>
                            @endforeach
                        </select>
                        @error('kamar_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @if($kamars->isEmpty())
                            <div class="form-text text-danger">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                Tidak ada kamar tersedia saat ini.
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">PIC Karyawan <span class="text-danger">*</span></label>
                        <select name="karyawan_id"
                            class="form-select @error('karyawan_id') is-invalid @enderror">
                            <option value="">— Pilih Karyawan —</option>
                            @foreach($karyawans as $k)
                                <option value="{{ $k->id }}" {{ old('karyawan_id')==$k->id?'selected':'' }}>
                                    {{ $k->nama }} — {{ ucfirst($k->jabatan) }}
                                </option>
                            @endforeach
                        </select>
                        @error('karyawan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_masuk" id="tglMasuk"
                            class="form-control @error('tanggal_masuk') is-invalid @enderror"
                            value="{{ old('tanggal_masuk', date('Y-m-d')) }}">
                        @error('tanggal_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Lama Sewa (Bulan) <span class="text-danger">*</span></label>
                        <input type="number" name="lama_sewa" id="lamaSewa"
                            class="form-control @error('lama_sewa') is-invalid @enderror"
                            value="{{ old('lama_sewa', 1) }}" min="1" max="24">
                        @error('lama_sewa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text" id="tglKeluarInfo">—</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Uang Deposit (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:9px 0 0 9px;">Rp</span>
                            <input type="number" name="uang_deposit"
                                class="form-control @error('uang_deposit') is-invalid @enderror"
                                style="border-radius:0 9px 9px 0;"
                                value="{{ old('uang_deposit', 0) }}" min="0" step="50000">
                            @error('uang_deposit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- TOTAL ESTIMASI --}}
                    <div class="col-12">
                        <div class="total-box" id="totalBox">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width:44px;height:44px;background:linear-gradient(135deg,#059669,#10b981);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem;">
                                        💰
                                    </div>
                                    <div>
                                        <div style="font-size:.75rem;color:#065f46;font-weight:600;">Estimasi Total Biaya Sewa</div>
                                        <div class="total-box-value" id="totalHarga">—</div>
                                    </div>
                                </div>
                                <div id="keluarInfo" style="font-size:.8rem;color:#065f46;font-weight:500;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2"
                            placeholder="Catatan tambahan (opsional)...">{{ old('catatan') }}</textarea>
                    </div>
                </div>

                {{-- FOOTER --}}
                <hr class="my-4" style="border-color:var(--border);">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('penyewa.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-success-kost px-5" id="btnSimpan">
                        <i class="bi bi-person-check-fill me-1"></i> Simpan & Daftarkan
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
function hitungTotal() {
    const sel   = document.getElementById('kamarSelect');
    const lama  = parseInt(document.getElementById('lamaSewa').value) || 0;
    const opt   = sel.options[sel.selectedIndex];
    const harga = parseFloat(opt?.dataset?.harga) || 0;
    const total = harga * lama;

    document.getElementById('totalHarga').textContent =
        total > 0 ? 'Rp ' + total.toLocaleString('id-ID') : '—';

    // Hitung tanggal keluar
    const tgl = document.getElementById('tglMasuk').value;
    if (tgl && lama > 0) {
        const d = new Date(tgl);
        d.setMonth(d.getMonth() + lama);
        const opts = { day: 'numeric', month: 'long', year: 'numeric' };
        document.getElementById('tglKeluarInfo').textContent =
            'Kontrak berakhir: ' + d.toLocaleDateString('id-ID', opts);
        document.getElementById('keluarInfo').innerHTML =
            '<i class="bi bi-calendar-check me-1"></i>Keluar: <strong>' +
            d.toLocaleDateString('id-ID', opts) + '</strong>';
    } else {
        document.getElementById('tglKeluarInfo').textContent = '—';
        document.getElementById('keluarInfo').textContent = '';
    }
}

['kamarSelect','lamaSewa','tglMasuk'].forEach(id => {
    document.getElementById(id).addEventListener('change', hitungTotal);
    document.getElementById(id).addEventListener('input', hitungTotal);
});
hitungTotal();

// Konfirmasi SweetAlert sebelum simpan
document.getElementById('formPenyewa').addEventListener('submit', function(e) {
    e.preventDefault();
    const form  = this;
    const nama  = document.querySelector('[name="nama_penyewa"]').value || '...';
    const kamar = document.getElementById('kamarSelect');
    const noKamar = kamar.options[kamar.selectedIndex]?.text?.split(' — ')[0] || '';
    const total = document.getElementById('totalHarga').textContent;

    Swal.fire({
        title: 'Konfirmasi Pendaftaran',
        html: `
            <div style="text-align:left;font-size:.9rem;line-height:1.8;">
                <div><b>Penyewa:</b> ${nama}</div>
                <div><b>Kamar:</b> ${noKamar}</div>
                <div><b>Total:</b> <span style="color:#059669;font-weight:700;">${total}</span></div>
            </div>
        `,
        icon: 'question',
        iconColor: '#10b981',
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-person-check-fill me-1"></i> Daftarkan',
        cancelButtonText: 'Cek Lagi',
        confirmButtonColor: '#059669',
        cancelButtonColor: '#64748b',
        reverseButtons: true,
    }).then(r => { if (r.isConfirmed) form.submit(); });
});
</script>
@endpush