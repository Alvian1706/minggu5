{{--
    resources/views/setting/index.blade.php
    Halaman pengaturan sistem kost
--}}
@extends('layouts.app')
@section('title', 'Setting Sistem')
@section('breadcrumb', 'Pengaturan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="page-header">
            <div>
                <h1 class="page-title">⚙️ Setting Sistem</h1>
                <p class="page-subtitle">Konfigurasi umum aplikasi manajemen kost</p>
            </div>
        </div>

        <form action="{{ route('setting.update') }}" method="POST">
            @csrf

            {{-- INFO KOST --}}
            <div class="card table-card mb-4">
                <div class="card-header px-4 py-3 bg-white border-bottom">
                    <span class="fw-bold" style="font-family:'Syne',sans-serif;">🏠 Informasi Kost</span>
                </div>
                <div class="card-body p-4">
                    <div class="form-section-title">Identitas</div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Nama Kost</label>
                            <input type="text" name="nama_kost" class="form-control" value="KOST AG" placeholder="Nama kost">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Telepon Kost</label>
                            <input type="text" name="telepon_kost" class="form-control" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat_kost" class="form-control" rows="2" placeholder="Jl. ..."></textarea>
                        </div>
                    </div>

                    <div class="form-section-title">Peraturan Sewa</div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Minimal Sewa (Bulan)</label>
                            <input type="number" name="min_sewa" class="form-control" value="1" min="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Notifikasi Habis (Hari)</label>
                            <input type="number" name="notif_hari" class="form-control" value="7" min="1">
                            <div class="form-text">Peringatan sebelum kontrak habis</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Deposit Default (x Harga)</label>
                            <input type="number" name="deposit_kali" class="form-control" value="1" min="0" step="0.5">
                        </div>
                    </div>
                </div>
            </div>

            {{-- METHOD INFO --}}
            <div class="card table-card mb-4" style="border:1px solid #e5e7eb;">
                <div class="card-header px-4 py-3 bg-white border-bottom">
                    <span class="fw-bold" style="font-family:'Syne',sans-serif;">📖 Referensi Method CRUD</span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background:#eff6ff;border:1px solid #bfdbfe;">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="method-label-qb">QB</span>
                                    <strong style="font-family:'Syne',sans-serif;">Query Builder</strong>
                                </div>
                                <div class="small text-muted">Digunakan di: <strong>KamarController</strong></div>
                                <hr class="my-2">
                                <ul class="small mb-0" style="color:#1d4ed8;">
                                    <li><code>DB::table('kamars')->get()</code></li>
                                    <li><code>DB::table('kamars')->insertGetId([...])</code></li>
                                    <li><code>DB::table('kamars')->where()->update([...])</code></li>
                                    <li><code>DB::table('kamars')->where()->delete()</code></li>
                                    <li><code>DB::table()->join()->select()->get()</code></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="method-label-el">EL</span>
                                    <strong style="font-family:'Syne',sans-serif;">Eloquent ORM</strong>
                                </div>
                                <div class="small text-muted">Digunakan di: <strong>Penyewa & KaryawanController</strong></div>
                                <hr class="my-2">
                                <ul class="small mb-0" style="color:#15803d;">
                                    <li><code>Model::with(['relasi'])->paginate()</code></li>
                                    <li><code>Model::create([...])</code></li>
                                    <li><code>$model->fill([...])->save()</code></li>
                                    <li><code>Model::findOrFail($id)</code></li>
                                    <li><code>$model->delete()</code></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ROUTES SUMMARY --}}
            <div class="card table-card mb-4">
                <div class="card-header px-4 py-3 bg-white border-bottom">
                    <span class="fw-bold" style="font-family:'Syne',sans-serif;">🗺️ Routes Summary</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Method</th>
                                    <th>URI</th>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>ORM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $routes = [
                                    ['GET','kamar','index','kamar.index','QB'],
                                    ['GET','kamar/create','create','kamar.create','QB'],
                                    ['POST','kamar','store','kamar.store','QB'],
                                    ['GET','kamar/{id}','show','kamar.show','QB'],
                                    ['GET','kamar/{id}/edit','edit','kamar.edit','QB'],
                                    ['PUT','kamar/{id}','update','kamar.update','QB'],
                                    ['DELETE','kamar/{id}','destroy','kamar.destroy','QB'],
                                    ['GET','kamar-laporan','laporan','kamar.laporan','QB'],
                                    ['GET','karyawan','index','karyawan.index','EL'],
                                    ['GET','karyawan/create','create','karyawan.create','EL'],
                                    ['POST','karyawan','store','karyawan.store','EL'],
                                    ['GET','karyawan/{id}/edit','edit','karyawan.edit','EL'],
                                    ['PUT','karyawan/{id}','update','karyawan.update','EL'],
                                    ['DELETE','karyawan/{id}','destroy','karyawan.destroy','EL'],
                                    ['GET','penyewa','index','penyewa.index','EL'],
                                    ['GET','penyewa/create','create','penyewa.create','EL'],
                                    ['POST','penyewa','store','penyewa.store','EL'],
                                    ['GET','penyewa/{id}','show','penyewa.show','EL'],
                                    ['GET','penyewa/{id}/edit','edit','penyewa.edit','EL'],
                                    ['PUT','penyewa/{id}','update','penyewa.update','EL'],
                                    ['DELETE','penyewa/{id}','destroy','penyewa.destroy','EL'],
                                    ['POST','penyewa/{id}/checkout','checkout','penyewa.checkout','EL'],
                                    ['GET','penyewa-laporan','laporan','penyewa.laporan','EL'],
                                    ['GET','setting','index','setting.index','—'],
                                ];
                                @endphp
                                @foreach($routes as $r)
                                <tr>
                                    <td class="ps-4">
                                        @php
                                            $mBg = ['GET'=>'#dbeafe','POST'=>'#dcfce7','PUT'=>'#fef3c7','DELETE'=>'#fee2e2','PATCH'=>'#f3e8ff'];
                                            $mClr = ['GET'=>'#1d4ed8','POST'=>'#15803d','PUT'=>'#b45309','DELETE'=>'#dc2626','PATCH'=>'#7c3aed'];
                                        @endphp
                                        <span class="badge" style="background:{{ $mBg[$r[0]]??'#f3f4f6' }};color:{{ $mClr[$r[0]]??'#374151' }};">{{ $r[0] }}</span>
                                    </td>
                                    <td><code class="small">/{{ $r[1] }}</code></td>
                                    <td><small>{{ $r[2] }}</small></td>
                                    <td><small class="text-muted">{{ $r[3] }}</small></td>
                                    <td>
                                        @if($r[4]=='QB')
                                            <span class="method-label-qb" style="font-size:.6rem;">QB</span>
                                        @elseif($r[4]=='EL')
                                            <span class="method-label-el" style="font-size:.6rem;">EL</span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-qb px-4">
                    <i class="bi bi-save me-1"></i> Simpan Setting
                </button>
            </div>
        </form>

    </div>
</div>
@endsection