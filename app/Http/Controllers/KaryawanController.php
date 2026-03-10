<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::query();

        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'LIKE', '%'.$request->search.'%')
                  ->orWhere('no_telepon', 'LIKE', '%'.$request->search.'%');
            });
        }

        $karyawans   = $query->orderBy('nama')->paginate(10);
        $jabatanList = DB::table('karyawans')
            ->select('jabatan', DB::raw('COUNT(*) as total'))
            ->groupBy('jabatan')->get();

        return view('karyawan.index', compact('karyawans', 'jabatanList'));
    }

    public function create()
    {
        return view('karyawan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:100',
            'jabatan'       => 'required|in:admin,teknisi,kebersihan,keamanan',
            'no_telepon'    => 'required|string|max:15',
            'email'         => 'nullable|email|unique:karyawans,email',
            'alamat'        => 'nullable|string',
            'gaji'          => 'required|numeric|min:0',
            'tanggal_masuk' => 'required|date',
            'status_kerja'  => 'required|in:aktif,nonaktif',
        ]);

        // Eloquent CREATE
        Karyawan::create($request->only([
            'nama','jabatan','no_telepon','email','alamat','gaji','tanggal_masuk','status_kerja'
        ]));

        return redirect()->route('karyawan.index')
            ->with('success', 'Karyawan '.$request->nama.' berhasil ditambahkan!');
    }

    public function show($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $riwayat = DB::table('penyewas')
            ->join('kamars', 'penyewas.kamar_id', '=', 'kamars.id')
            ->where('penyewas.karyawan_id', $id)
            ->select('penyewas.*', 'kamars.nomor_kamar', 'kamars.tipe_kamar')
            ->orderBy('penyewas.tanggal_masuk', 'desc')
            ->limit(10)->get();

        return view('karyawan.show', compact('karyawan', 'riwayat'));
    }

    public function edit($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        return view('karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'          => 'required|string|max:100',
            'jabatan'       => 'required|in:admin,teknisi,kebersihan,keamanan',
            'no_telepon'    => 'required|string|max:15',
            'email'         => 'nullable|email|unique:karyawans,email,'.$id,
            'alamat'        => 'nullable|string',
            'gaji'          => 'required|numeric|min:0',
            'tanggal_masuk' => 'required|date',
            'status_kerja'  => 'required|in:aktif,nonaktif',
        ]);

        // Eloquent UPDATE
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->update($request->only([
            'nama','jabatan','no_telepon','email','alamat','gaji','tanggal_masuk','status_kerja'
        ]));

        return redirect()->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tanggungan = DB::table('penyewas')
            ->where('karyawan_id', $id)->where('status', 'aktif')->count();

        if ($tanggungan > 0) {
            return redirect()->route('karyawan.index')
                ->with('error', 'Karyawan masih memiliki penyewa aktif!');
        }

        // Eloquent DELETE
        Karyawan::findOrFail($id)->delete();

        return redirect()->route('karyawan.index')
            ->with('success', 'Karyawan berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->update([
            'status_kerja' => $karyawan->status_kerja === 'aktif' ? 'nonaktif' : 'aktif'
        ]);

        return redirect()->route('karyawan.index')
            ->with('success', 'Status karyawan diperbarui!');
    }
}