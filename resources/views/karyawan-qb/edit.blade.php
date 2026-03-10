@extends('layouts.app')

@section('title', 'Edit Karyawan (QB)')
@section('breadcrumb', 'Karyawan QB / Edit')
@section('method_badge')
    <span class="method-pill method-pill-qb"><i class="bi bi-database-fill me-1"></i>Query Builder</span>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8 col-lg-10">

    <div class="page-header-row">
        <div class="page-title-block">
            <h1 class="title">✏️ Edit Karyawan <span style="color:#3b82f6;">{{ $karyawan->nama }}</span></h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('karyawan.edit', $karyawan->id) }}" class="btn btn-outline-secondary btn-sm px-3">
                <i class="bi bi-arrow-left-right me-1"></i>Versi Eloquent
            </a>
            <a href="{{ route('karyawan-qb.index') }}" class="btn btn-outline-secondary px-3">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    @php
        $jabatanColors = ['admin'=>['#d1fae5','#065f46'],'teknisi'=>['#fef3c7','#92400e'],'kebersihan'=>['#ede9fe','#4c1d95'],'keamanan'=>['#fee2e2','#991b1b']];
        $c = $jabatanColors[$karyawan->jabatan] ?? ['#f1f5f9','#64748b'];
    @endphp

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-card-header blue-header">
                    <div class="fw-700 text-white">Edit Karyawan</div>
                </div>
                <div class="form-card-body">
                    <form action="{{ route('karyawan-qb.update', $karyawan->id) }}" method="POST" id="formEditKaryawanQB">
                        @csrf @method('PUT')

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
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" style="border-radius:9px 0 0 9px;"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="no_telepon" class="form-control" style="border-radius:0 9px 9px 0;"
                                        value="{{ old('no_telepon', $karyawan->no_telepon) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="border-radius:9px 0 0 9px;"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" style="border-radius:0 9px 9px 0;"
                                        value="{{ old('email', $karyawan->email) }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" rows="2" class="form-control">{{ old('alamat', $karyawan->alamat) }}</textarea>
                            </div>
                        </div>

                        <div class="form-section-head">💼 Data Pekerjaan</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Gaji per Bulan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" style="border-radius:9px 0 0 9px;">Rp</span>
                                    <input type="number" name="gaji" id="gajiEdit"
                                        class="form-control" style="border-radius:0 9px 9px 0;"
                                        value="{{ old('gaji', $karyawan->gaji) }}" min="0" step="100000">
                                </div>
                                <div class="form-text" id="gajiPreview">= Rp {{ number_format($karyawan->gaji,0,',','.') }} / bulan</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_masuk" class="form-control"
                                    value="{{ old('tanggal_masuk', $karyawan->tanggal_masuk) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status Kerja <span class="text-danger">*</span></label>
                                <select name="status_kerja" class="form-select">
                                    <option value="aktif"    {{ old('status_kerja',$karyawan->status_kerja)=='aktif'   ?'selected':'' }}>🟢 Aktif</option>
                                    <option value="nonaktif" {{ old('status_kerja',$karyawan->status_kerja)=='nonaktif'?'selected':'' }}>⚪ Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4" style="border-color:var(--border);">
                        <div class="d-flex justify-content-between flex-wrap gap-2">
                            <form action="{{ route('karyawan-qb.destroy', $karyawan->id) }}" method="POST" class="m-0">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-outline-danger btn-swal-delete px-4"
                                    data-nama="{{ $karyawan->nama }}" data-type="Karyawan">
                                    <i class="bi bi-trash me-1"></i> Hapus
                                </button>
                            </form>
                            <div class="d-flex gap-2">
                                <a href="{{ route('karyawan-qb.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                                <button type="submit" class="btn btn-primary-kost px-5">
                                    <i class="bi bi-arrow-repeat me-1"></i> Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-card mb-3">
                <div class="form-card-body" style="text-align:center;padding:1.5rem;">
                    <div id="avatarPreviewEdit"
                         style="width:80px;height:80px;border-radius:20px;margin:0 auto .75rem;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:2.2rem;transition:all .3s;background:{{ $c[0] }};color:{{ $c[1] }};">
                        {{ strtoupper(substr($karyawan->nama,0,1)) }}
                    </div>
                    <div id="avatarName" class="fw-700" style="font-size:.95rem;">{{ $karyawan->nama }}</div>
                    <div id="avatarJabatan" style="font-size:.8rem;color:var(--text-muted);">{{ ucfirst($karyawan->jabatan) }}</div>
                </div>
            </div>
            <div class="form-card mb-3">
                <div class="form-card-body" style="padding:1.25rem;">
                    <div class="form-section-head" style="margin-bottom:.9rem;">⚡ Toggle Status Cepat</div>
                    <form action="{{ route('karyawan-qb.toggle-status', $karyawan->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="button" class="btn btn-swal-toggle w-100 fw-700"
                                data-nama="{{ $karyawan->nama }}" data-status="{{ $karyawan->status_kerja }}"
                                style="border-radius:9px;font-size:.8rem;background:{{ $karyawan->status_kerja=='aktif'?'#d1fae5':'#f1f5f9' }};color:{{ $karyawan->status_kerja=='aktif'?'#065f46':'#475569' }};border:none;padding:.6rem 1rem;">
                            {{ $karyawan->status_kerja=='aktif' ? '● Aktif → Nonaktifkan' : '○ Nonaktif → Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>
            <div class="form-card">
                <div class="form-card-body" style="padding:1.25rem;">
                    <div class="form-section-head" style="margin-bottom:.9rem;">ℹ️ Info Record</div>
                    <div class="d-flex flex-column gap-2" style="font-size:.8rem;">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">ID</span><strong>#{{ $karyawan->id }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Dibuat</span>
                            <span>{{ \Carbon\Carbon::parse($karyawan->created_at)->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Tgl Masuk</span>
                            <span>{{ \Carbon\Carbon::parse($karyawan->tanggal_masuk)->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Gaji</span>
                            <strong style="color:#059669;">Rp {{ number_format($karyawan->gaji,0,',','.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
const jabatanColors={admin:{bg:'#d1fae5',color:'#065f46'},teknisi:{bg:'#fef3c7',color:'#92400e'},kebersihan:{bg:'#ede9fe',color:'#4c1d95'},keamanan:{bg:'#fee2e2',color:'#991b1b'}};
function updateAvatar(){
    const nama=document.getElementById('namaEdit').value.trim();
    const jabatan=document.getElementById('jabatanEdit').value;
    const avatar=document.getElementById('avatarPreviewEdit');
    const c=jabatanColors[jabatan]||{bg:'#f1f5f9',color:'#94a3b8'};
    avatar.textContent=nama?nama.charAt(0).toUpperCase():'?';
    avatar.style.background=c.bg;avatar.style.color=c.color;
    document.getElementById('avatarName').textContent=nama||'—';
    document.getElementById('avatarJabatan').textContent=jabatan?jabatan.charAt(0).toUpperCase()+jabatan.slice(1):'—';
}
document.getElementById('namaEdit').addEventListener('input',updateAvatar);
document.getElementById('jabatanEdit').addEventListener('change',updateAvatar);
document.getElementById('gajiEdit').addEventListener('input',function(){
    const v=parseInt(this.value)||0;
    document.getElementById('gajiPreview').textContent=v>0?'= Rp '+v.toLocaleString('id-ID')+' / bulan':'—';
});
document.getElementById('formEditKaryawanQB').addEventListener('submit',function(e){
    e.preventDefault();const form=this;
    Swal.fire({title:'Simpan perubahan?',
        icon:'question',iconColor:'#3b82f6',showCancelButton:true,
        confirmButtonText:'Ya, Update',
        cancelButtonText:'Batal',confirmButtonColor:'#2563eb',cancelButtonColor:'#64748b',reverseButtons:true,
    }).then(r=>{if(r.isConfirmed)form.submit();});
});
</script>
@endpush