@extends('layouts.app')

@section('title', 'Daftar Penyewa (QB)')
@section('breadcrumb', 'Penyewa QB / Daftar Baru')
@section('method_badge')
    <span class="method-pill method-pill-qb"><i class="bi bi-database-fill me-1"></i>Query Builder</span>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-9 col-lg-11">

    <div class="page-header-row">
        <div class="page-title-block">
            <h1 class="title">👤 Daftar Penyewa <span style="color:#3b82f6;">(QB)</span></h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('penyewa.create') }}" class="btn btn-outline-secondary btn-sm px-3">
                <i class="bi bi-arrow-left-right me-1"></i>Versi Eloquent
            </a>
            <a href="{{ route('penyewa-qb.index') }}" class="btn btn-outline-secondary px-3">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header blue-header">
            <div class="fw-700 text-white">Form Pendaftaran Penyewa</div>
        </div>

        <div class="form-card-body">
            <form action="{{ route('penyewa-qb.store') }}" method="POST" id="formPenyewaQB">
                @csrf

                <div class="form-section-head">👤 Data Pribadi Penyewa</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_penyewa"
                            class="form-control @error('nama_penyewa') is-invalid @enderror"
                            value="{{ old('nama_penyewa') }}" placeholder="Nama sesuai KTP">
                        @error('nama_penyewa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. KTP / NIK <span class="text-danger">*</span></label>
                        <input type="text" name="no_ktp"
                            class="form-control @error('no_ktp') is-invalid @enderror"
                            value="{{ old('no_ktp') }}" placeholder="16 digit NIK" maxlength="16"
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
                </div>

                <div class="form-section-head">🏠 Data Sewa</div>
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
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">PIC Karyawan <span class="text-danger">*</span></label>
                        <select name="karyawan_id" class="form-select @error('karyawan_id') is-invalid @enderror">
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
                            class="form-control" value="{{ old('tanggal_masuk', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Lama Sewa (Bulan) <span class="text-danger">*</span></label>
                        <input type="number" name="lama_sewa" id="lamaSewa"
                            class="form-control" value="{{ old('lama_sewa',1) }}" min="1" max="24">
                        <div class="form-text" id="tglKeluarInfo">—</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Uang Deposit (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:9px 0 0 9px;">Rp</span>
                            <input type="number" name="uang_deposit" class="form-control"
                                style="border-radius:0 9px 9px 0;" value="{{ old('uang_deposit',0) }}" min="0" step="50000">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="total-box">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width:44px;height:44px;background:linear-gradient(135deg,#1d4ed8,#3b82f6);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem;">
                                        💰
                                    </div>
                                    <div>
                                        <div style="font-size:.75rem;color:#1e40af;font-weight:600;">Estimasi Total</div>
                                        <div id="totalHarga" style="font-size:1.4rem;font-weight:800;color:#1d4ed8;letter-spacing:-.5px;">—</div>
                                    </div>
                                </div>
                                <div id="keluarInfo" style="font-size:.8rem;color:#1e40af;font-weight:500;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2">{{ old('catatan') }}</textarea>
                    </div>
                </div>

                <hr class="my-4" style="border-color:var(--border);">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('penyewa-qb.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
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

@push('styles')
<style>
.total-box{background:linear-gradient(135deg,#eff6ff,#dbeafe);border:1.5px solid #bfdbfe;border-radius:12px;padding:1rem 1.25rem;}
</style>
@endpush

@push('scripts')
<script>
function hitungTotal(){
    const sel=document.getElementById('kamarSelect');
    const lama=parseInt(document.getElementById('lamaSewa').value)||0;
    const opt=sel.options[sel.selectedIndex];
    const harga=parseFloat(opt?.dataset?.harga)||0;
    const total=harga*lama;
    document.getElementById('totalHarga').textContent=total>0?'Rp '+total.toLocaleString('id-ID'):'—';
    const tgl=document.getElementById('tglMasuk').value;
    if(tgl&&lama>0){
        const d=new Date(tgl);d.setMonth(d.getMonth()+lama);
        const opts={day:'numeric',month:'long',year:'numeric'};
        document.getElementById('tglKeluarInfo').textContent='Keluar: '+d.toLocaleDateString('id-ID',opts);
        document.getElementById('keluarInfo').innerHTML='<i class="bi bi-calendar-check me-1"></i>Keluar: <strong>'+d.toLocaleDateString('id-ID',opts)+'</strong>';
    }
}
['kamarSelect','lamaSewa','tglMasuk'].forEach(id=>{
    const el=document.getElementById(id);
    el.addEventListener('change',hitungTotal);el.addEventListener('input',hitungTotal);
});
hitungTotal();
document.getElementById('formPenyewaQB').addEventListener('submit',function(e){
    e.preventDefault();const form=this;
    Swal.fire({
        title:'Konfirmasi Pendaftaran?',
        icon:'question',iconColor:'#3b82f6',
        showCancelButton:true,
        confirmButtonText:'Ya, Simpan',
        cancelButtonText:'Cek Lagi',confirmButtonColor:'#2563eb',cancelButtonColor:'#64748b',reverseButtons:true,
    }).then(r=>{if(r.isConfirmed)form.submit();});
});
</script>
@endpush