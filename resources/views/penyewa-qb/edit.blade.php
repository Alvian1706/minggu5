@extends('layouts.app')

@section('title', 'Edit Penyewa (QB)')
@section('breadcrumb', 'Penyewa QB / Edit')
@section('method_badge')
    <span class="method-pill method-pill-qb"><i class="bi bi-database-fill me-1"></i>Query Builder</span>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-9 col-lg-11">

    <div class="page-header-row">
        <div class="page-title-block">
            <h1 class="title">✏️ Edit Penyewa <span style="color:#3b82f6;">{{ $penyewa->nama_penyewa }}</span></h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('penyewa.edit', $penyewa->id) }}" class="btn btn-outline-secondary btn-sm px-3">
                <i class="bi bi-arrow-left-right me-1"></i>Versi Eloquent
            </a>
            <a href="{{ route('penyewa-qb.index') }}" class="btn btn-outline-secondary px-3">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header blue-header">
            <div class="fw-700 text-white">Edit Penyewa</div>
        </div>

        <div class="form-card-body">
            <form action="{{ route('penyewa-qb.update', $penyewa->id) }}" method="POST" id="formEditPenyewaQB">
                @csrf @method('PUT')

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
                            value="{{ old('no_ktp', $penyewa->no_ktp) }}" maxlength="16"
                            style="font-family:'DM Mono',monospace;letter-spacing:1px;">
                        @error('no_ktp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:9px 0 0 9px;"><i class="bi bi-phone"></i></span>
                            <input type="text" name="no_telepon" class="form-control" style="border-radius:0 9px 9px 0;"
                                value="{{ old('no_telepon', $penyewa->no_telepon) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:9px 0 0 9px;"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" style="border-radius:0 9px 9px 0;"
                                value="{{ old('email', $penyewa->email) }}">
                        </div>
                    </div>
                </div>

                <div class="form-section-head">🏠 Data Sewa</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Kamar <span class="text-danger">*</span></label>
                        <select name="kamar_id" class="form-select @error('kamar_id') is-invalid @enderror">
                            @foreach($kamars as $k)
                                <option value="{{ $k->id }}" data-harga="{{ $k->harga_bulan }}"
                                    {{ old('kamar_id',$penyewa->kamar_id)==$k->id?'selected':'' }}>
                                    Kamar {{ $k->nomor_kamar }} — {{ ucfirst($k->tipe_kamar) }}
                                    (Rp {{ number_format($k->harga_bulan,0,',','.') }}/bln)
                                    @if($k->id==$penyewa->kamar_id) ← saat ini @endif
                                </option>
                            @endforeach
                        </select>
                        @error('kamar_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">PIC Karyawan <span class="text-danger">*</span></label>
                        <select name="karyawan_id" class="form-select">
                            @foreach($karyawans as $k)
                                <option value="{{ $k->id }}" {{ old('karyawan_id',$penyewa->karyawan_id)==$k->id?'selected':'' }}>
                                    {{ $k->nama }} — {{ ucfirst($k->jabatan) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_masuk" class="form-control"
                            value="{{ old('tanggal_masuk', $penyewa->tanggal_masuk) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Lama Sewa (Bulan) <span class="text-danger">*</span></label>
                        <input type="number" name="lama_sewa" class="form-control"
                            value="{{ old('lama_sewa', $penyewa->lama_sewa) }}" min="1" max="24">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Uang Deposit (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:9px 0 0 9px;">Rp</span>
                            <input type="number" name="uang_deposit" class="form-control"
                                style="border-radius:0 9px 9px 0;"
                                value="{{ old('uang_deposit', $penyewa->uang_deposit) }}" min="0" step="50000">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select">
                            <option value="aktif"   {{ old('status',$penyewa->status)=='aktif'  ?'selected':'' }}>🟢 Aktif</option>
                            <option value="selesai" {{ old('status',$penyewa->status)=='selesai'?'selected':'' }}>⚪ Selesai</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2">{{ old('catatan', $penyewa->catatan) }}</textarea>
                    </div>
                </div>

                <hr class="my-4" style="border-color:var(--border);">
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    @if($penyewa->status=='selesai')
                    <form action="{{ route('penyewa-qb.destroy', $penyewa->id) }}" method="POST" class="m-0">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-swal-delete px-4"
                            data-nama="{{ $penyewa->nama_penyewa }}" data-type="Penyewa">
                            <i class="bi bi-trash me-1"></i> Hapus Data
                        </button>
                    </form>
                    @else<div></div>@endif
                    <div class="d-flex gap-2">
                        <a href="{{ route('penyewa-qb.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-primary-kost px-5">
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
document.getElementById('formEditPenyewaQB').addEventListener('submit',function(e){
    e.preventDefault();const form=this;
    Swal.fire({
        title:'Simpan perubahan?',
        icon:'question',iconColor:'#3b82f6',showCancelButton:true,
        confirmButtonText:'Ya, Update',
        cancelButtonText:'Batal',confirmButtonColor:'#2563eb',cancelButtonColor:'#64748b',reverseButtons:true,
    }).then(r=>{if(r.isConfirmed)form.submit();});
});
</script>
@endpush