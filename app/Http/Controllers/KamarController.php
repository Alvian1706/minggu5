<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KamarController extends Controller
{
    /**
     * READ — Tampilkan daftar kamar
     * QB: DB::table('kamars')->where()->paginate()
     */
    public function index(Request $request)
    {
        $query = DB::table('kamars');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tipe')) {
            $query->where('tipe_kamar', $request->tipe);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_kamar', 'LIKE', '%'.$request->search.'%')
                  ->orWhere('tipe_kamar', 'LIKE', '%'.$request->search.'%');
            });
        }

        $kamars = $query->orderBy('nomor_kamar')->paginate(10);

        // Statistik QB
        $statistik = [
            'total'     => DB::table('kamars')->count(),
            'tersedia'  => DB::table('kamars')->where('status', 'tersedia')->count(),
            'terisi'    => DB::table('kamars')->where('status', 'terisi')->count(),
            'perbaikan' => DB::table('kamars')->where('status', 'perbaikan')->count(),
        ];

        return view('kamar.index', compact('kamars', 'statistik'));
    }

    /**
     * CREATE FORM — Form tambah kamar
     */
    public function create()
    {
        return view('kamar.create');
    }

    /**
     * CREATE STORE — Simpan kamar baru
     * QB: DB::table('kamars')->insertGetId([...])
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_kamar' => 'required|string|max:10|unique:kamars,nomor_kamar',
            'tipe_kamar'  => 'required|in:standar,deluxe,vip',
            'harga_bulan' => 'required|numeric|min:0',
            'luas_kamar'  => 'required|numeric|min:1',
            'fasilitas'   => 'nullable|string',
            'status'      => 'required|in:tersedia,terisi,perbaikan',
        ]);

        // QB INSERT
        DB::table('kamars')->insertGetId([
            'nomor_kamar' => strtoupper($request->nomor_kamar),
            'tipe_kamar'  => $request->tipe_kamar,
            'harga_bulan' => $request->harga_bulan,
            'luas_kamar'  => $request->luas_kamar,
            'fasilitas'   => $request->fasilitas,
            'status'      => $request->status,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect()->route('kamar.index')
            ->with('success', "Kamar {$request->nomor_kamar} berhasil ditambahkan!");
    }

    /**
     * READ DETAIL — Detail kamar + riwayat penyewa
     * QB: DB::table()->join()->where()->get()
     */
    public function show($id)
    {
        $kamar = DB::table('kamars')->where('id', $id)->first();
        abort_if(!$kamar, 404, 'Kamar tidak ditemukan');

        // JOIN riwayat penyewa
        $riwayat = DB::table('penyewas')
            ->join('karyawans', 'penyewas.karyawan_id', '=', 'karyawans.id')
            ->where('penyewas.kamar_id', $id)
            ->select('penyewas.*', 'karyawans.nama as nama_karyawan')
            ->orderBy('penyewas.tanggal_masuk', 'desc')
            ->get();

        return view('kamar.show', compact('kamar', 'riwayat'));
    }

    /**
     * UPDATE FORM — Form edit kamar
     * QB: DB::table('kamars')->where()->first()
     */
    public function edit($id)
    {
        $kamar = DB::table('kamars')->where('id', $id)->first();
        abort_if(!$kamar, 404, 'Kamar tidak ditemukan');

        return view('kamar.edit', compact('kamar'));
    }

    /**
     * UPDATE STORE — Simpan perubahan kamar
     * QB: DB::table('kamars')->where()->update([...])
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_kamar' => 'required|string|max:10|unique:kamars,nomor_kamar,'.$id,
            'tipe_kamar'  => 'required|in:standar,deluxe,vip',
            'harga_bulan' => 'required|numeric|min:0',
            'luas_kamar'  => 'required|numeric|min:1',
            'fasilitas'   => 'nullable|string',
            'status'      => 'required|in:tersedia,terisi,perbaikan',
        ]);

        // UPDATE
        DB::table('kamars')->where('id', $id)->update([
            'nomor_kamar' => strtoupper($request->nomor_kamar),
            'tipe_kamar'  => $request->tipe_kamar,
            'harga_bulan' => $request->harga_bulan,
            'luas_kamar'  => $request->luas_kamar,
            'fasilitas'   => $request->fasilitas,
            'status'      => $request->status,
            'updated_at'  => now(),
        ]);

        return redirect()->route('kamar.index')
            ->with('success', 'Data kamar berhasil diperbarui!');
    }

    /**
     * DELETE — Hapus kamar
     * QB: DB::table('kamars')->where()->delete()
     */
    public function destroy($id)
    {
        // Cek penyewa aktif (QB)
        $aktif = DB::table('penyewas')
            ->where('kamar_id', $id)
            ->where('status', 'aktif')
            ->count();

        if ($aktif > 0) {
            return redirect()->route('kamar.index')
                ->with('error', 'Kamar tidak bisa dihapus, masih ada penyewa aktif!');
        }

        // QB DELETE
        DB::table('kamars')->where('id', $id)->delete();

        return redirect()->route('kamar.index')
            ->with('success', 'Kamar berhasil dihapus!');
    }

    /**
     * Update status kamar cepat (AJAX/PATCH)
     * QB: DB::table()->where()->update()
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:tersedia,terisi,perbaikan']);

        DB::table('kamars')->where('id', $id)->update([
            'status'     => $request->status,
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'status' => $request->status]);
    }

    /**
     * Laporan kamar
     * QB: DB::table()->groupBy()->select(DB::raw())
     */
    public function laporan()
    {
        $kamarTersedia = DB::table('kamars')
            ->where('status', 'tersedia')
            ->orderBy('tipe_kamar')->orderBy('harga_bulan')
            ->get();

        $rekapTipe = DB::table('kamars')
            ->select(
                'tipe_kamar',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status="tersedia" THEN 1 ELSE 0 END) as tersedia'),
                DB::raw('SUM(CASE WHEN status="terisi" THEN 1 ELSE 0 END) as terisi'),
                DB::raw('AVG(harga_bulan) as rata_harga')
            )
            ->groupBy('tipe_kamar')
            ->get();

        return view('kamar.laporan', compact('kamarTersedia', 'rekapTipe'));
    }
}