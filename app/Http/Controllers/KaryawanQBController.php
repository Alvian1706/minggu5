<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * KaryawanQBController
 * ═══════════════════════════════════════════════
 * METODE: QUERY BUILDER (DB::table)
 * Versi QB dari KaryawanController (Eloquent)
 * ═══════════════════════════════════════════════
 */
class KaryawanQBController extends Controller
{
    /** READ — QB: DB::table('karyawans')->where()->paginate() */
    public function index(Request $request)
    {
        $query = DB::table('karyawans');

        if ($request->filled('jabatan')) $query->where('jabatan', $request->jabatan);
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'LIKE', '%'.$request->search.'%')
                  ->orWhere('no_telepon', 'LIKE', '%'.$request->search.'%');
            });
        }

        $karyawans   = $query->orderBy('nama')->paginate(10);
        $jabatanList = DB::table('karyawans')
            ->select('jabatan', DB::raw('COUNT(*) as total'))
            ->groupBy('jabatan')
            ->get();

        return view('karyawan-qb.index', compact('karyawans', 'jabatanList'));
    }

    public function create()
    {
        return view('karyawan-qb.create');
    }

    /** CREATE — QB: DB::table('karyawans')->insertGetId([...]) */
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

        $id = DB::table('karyawans')->insertGetId([
            'nama'          => $request->nama,
            'jabatan'       => $request->jabatan,
            'no_telepon'    => $request->no_telepon,
            'email'         => $request->email,
            'alamat'        => $request->alamat,
            'gaji'          => $request->gaji,
            'tanggal_masuk' => $request->tanggal_masuk,
            'status_kerja'  => $request->status_kerja,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return redirect()->route('karyawan-qb.index')
            ->with('success', "Karyawan {$request->nama} ditambahkan! (insertGetId → ID #{$id})");
    }

    /** READ ONE — QB: DB::table()->where('id', $id)->first() */
    public function show($id)
    {
        $karyawan = DB::table('karyawans')->where('id', $id)->first();
        abort_if(!$karyawan, 404);

        $riwayat = DB::table('penyewas')
            ->join('kamars', 'penyewas.kamar_id', '=', 'kamars.id')
            ->where('penyewas.karyawan_id', $id)
            ->select('penyewas.*', 'kamars.nomor_kamar', 'kamars.tipe_kamar')
            ->orderBy('penyewas.tanggal_masuk', 'desc')
            ->limit(10)
            ->get();

        return view('karyawan-qb.show', compact('karyawan', 'riwayat'));
    }

    public function edit($id)
    {
        $karyawan = DB::table('karyawans')->where('id', $id)->first();
        abort_if(!$karyawan, 404);
        return view('karyawan-qb.edit', compact('karyawan'));
    }

    /** UPDATE — QB: DB::table('karyawans')->where('id', $id)->update([...]) */
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

        DB::table('karyawans')->where('id', $id)->update([
            'nama'          => $request->nama,
            'jabatan'       => $request->jabatan,
            'no_telepon'    => $request->no_telepon,
            'email'         => $request->email,
            'alamat'        => $request->alamat,
            'gaji'          => $request->gaji,
            'tanggal_masuk' => $request->tanggal_masuk,
            'status_kerja'  => $request->status_kerja,
            'updated_at'    => now(),
        ]);

        return redirect()->route('karyawan-qb.index')
            ->with('success', 'Data karyawan diperbarui! (via DB::table()->update())');
    }

    /** DELETE — QB: DB::table('karyawans')->where('id', $id)->delete() */
    public function destroy($id)
    {
        $cek = DB::table('penyewas')
            ->where('karyawan_id', $id)
            ->where('status', 'aktif')
            ->count();

        if ($cek > 0) {
            return redirect()->route('karyawan-qb.index')
                ->with('error', 'Karyawan masih punya penyewa aktif!');
        }

        $karyawan = DB::table('karyawans')->where('id', $id)->first();
        DB::table('karyawans')->where('id', $id)->delete();

        return redirect()->route('karyawan-qb.index')
            ->with('success', "{$karyawan->nama} dihapus! (via DB::table()->delete())");
    }

    public function toggleStatus($id)
    {
        $karyawan = DB::table('karyawans')->where('id', $id)->first();
        abort_if(!$karyawan, 404);

        $statusBaru = $karyawan->status_kerja === 'aktif' ? 'nonaktif' : 'aktif';
        DB::table('karyawans')->where('id', $id)->update([
            'status_kerja' => $statusBaru,
            'updated_at'   => now(),
        ]);

        return redirect()->route('karyawan-qb.index')
            ->with('success', "Status {$karyawan->nama} → {$statusBaru}");
    }
}