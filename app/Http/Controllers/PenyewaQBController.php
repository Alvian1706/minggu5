<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * PenyewaQBController
 * ═══════════════════════════════════════════════
 * METODE: QUERY BUILDER (DB::table + JOIN manual)
 * Versi QB dari PenyewaController (Eloquent)
 * ═══════════════════════════════════════════════
 */
class PenyewaQBController extends Controller
{
    /** READ — QB: DB::table()->join()->paginate() — NO eager loading */
    public function index(Request $request)
    {
        $query = DB::table('penyewas')
            ->join('kamars',    'penyewas.kamar_id',    '=', 'kamars.id')
            ->join('karyawans', 'penyewas.karyawan_id', '=', 'karyawans.id')
            ->select(
                'penyewas.*',
                'kamars.nomor_kamar',
                'kamars.tipe_kamar',
                'karyawans.nama as nama_karyawan'
            );

        if ($request->filled('status'))   $query->where('penyewas.status', $request->status);
        if ($request->filled('kamar_id')) $query->where('penyewas.kamar_id', $request->kamar_id);
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('penyewas.nama_penyewa', 'LIKE', '%'.$request->search.'%')
                  ->orWhere('penyewas.no_ktp',     'LIKE', '%'.$request->search.'%')
                  ->orWhere('penyewas.no_telepon',  'LIKE', '%'.$request->search.'%');
            });
        }

        $penyewas = $query->orderBy('penyewas.created_at', 'desc')->paginate(10);
        $kamars   = DB::table('kamars')->orderBy('nomor_kamar')->get();

        $statistik = [
            'total'        => DB::table('penyewas')->count(),
            'aktif'        => DB::table('penyewas')->where('status', 'aktif')->count(),
            'selesai'      => DB::table('penyewas')->where('status', 'selesai')->count(),
            'segera_habis' => DB::table('penyewas')
                ->where('status', 'aktif')
                ->whereDate('tanggal_keluar', '<=', now()->addDays(7))
                ->count(),
        ];

        return view('penyewa-qb.index', compact('penyewas', 'kamars', 'statistik'));
    }

    public function create()
    {
        $kamars    = DB::table('kamars')->where('status', 'tersedia')->orderBy('nomor_kamar')->get();
        $karyawans = DB::table('karyawans')->where('status_kerja', 'aktif')->orderBy('nama')->get();
        return view('penyewa-qb.create', compact('kamars', 'karyawans'));
    }

    /** CREATE — QB: DB::table('penyewas')->insertGetId([...]) */
    public function store(Request $request)
    {
        $request->validate([
            'nama_penyewa'  => 'required|string|max:100',
            'no_ktp'        => 'required|string|size:16|unique:penyewas,no_ktp',
            'no_telepon'    => 'required|string|max:15',
            'email'         => 'nullable|email|max:100',
            'kamar_id'      => 'required|exists:kamars,id',
            'karyawan_id'   => 'required|exists:karyawans,id',
            'tanggal_masuk' => 'required|date',
            'lama_sewa'     => 'required|integer|min:1',
            'uang_deposit'  => 'nullable|numeric|min:0',
            'catatan'       => 'nullable|string',
        ]);

        // QB: ambil harga kamar
        $kamar         = DB::table('kamars')->where('id', $request->kamar_id)->first();
        $tanggalKeluar = date('Y-m-d', strtotime($request->tanggal_masuk . ' +' . $request->lama_sewa . ' months'));
        $totalHarga    = $kamar->harga_bulan * $request->lama_sewa;

        $id = DB::table('penyewas')->insertGetId([
            'nama_penyewa'  => $request->nama_penyewa,
            'no_ktp'        => $request->no_ktp,
            'no_telepon'    => $request->no_telepon,
            'email'         => $request->email,
            'kamar_id'      => $request->kamar_id,
            'karyawan_id'   => $request->karyawan_id,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_keluar'=> $tanggalKeluar,
            'lama_sewa'     => $request->lama_sewa,
            'total_harga'   => $totalHarga,
            'uang_deposit'  => $request->uang_deposit ?? 0,
            'status'        => 'aktif',
            'catatan'       => $request->catatan,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // Update status kamar via QB
        DB::table('kamars')->where('id', $request->kamar_id)->update([
            'status'     => 'terisi',
            'updated_at' => now(),
        ]);

        return redirect()->route('penyewa-qb.index')
            ->with('success', "{$request->nama_penyewa} didaftarkan! (insertGetId → ID #{$id})");
    }

    /** READ ONE — QB: DB::table()->join()->where()->first() */
    public function show($id)
    {
        $penyewa = DB::table('penyewas')
            ->join('kamars',    'penyewas.kamar_id',    '=', 'kamars.id')
            ->join('karyawans', 'penyewas.karyawan_id', '=', 'karyawans.id')
            ->select(
                'penyewas.*',
                'kamars.nomor_kamar', 'kamars.tipe_kamar', 'kamars.harga_bulan',
                'karyawans.nama as nama_karyawan', 'karyawans.jabatan as jabatan_karyawan'
            )
            ->where('penyewas.id', $id)
            ->first();

        abort_if(!$penyewa, 404);

        $sisaHari = max(0, now()->diffInDays(\Carbon\Carbon::parse($penyewa->tanggal_keluar), false));
        return view('penyewa-qb.show', compact('penyewa', 'sisaHari'));
    }

    public function edit($id)
    {
        $penyewa   = DB::table('penyewas')->where('id', $id)->first();
        abort_if(!$penyewa, 404);
        $kamars    = DB::table('kamars')->whereIn('status', ['tersedia','terisi'])->orderBy('nomor_kamar')->get();
        $karyawans = DB::table('karyawans')->where('status_kerja', 'aktif')->orderBy('nama')->get();
        return view('penyewa-qb.edit', compact('penyewa', 'kamars', 'karyawans'));
    }

    /** UPDATE — QB: DB::table('penyewas')->where()->update([...]) */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_penyewa'  => 'required|string|max:100',
            'no_ktp'        => 'required|string|size:16|unique:penyewas,no_ktp,'.$id,
            'no_telepon'    => 'required|string|max:15',
            'email'         => 'nullable|email|max:100',
            'kamar_id'      => 'required|exists:kamars,id',
            'karyawan_id'   => 'required|exists:karyawans,id',
            'tanggal_masuk' => 'required|date',
            'lama_sewa'     => 'required|integer|min:1',
            'uang_deposit'  => 'nullable|numeric|min:0',
            'status'        => 'required|in:aktif,selesai',
            'catatan'       => 'nullable|string',
        ]);

        $penyewaLama   = DB::table('penyewas')->where('id', $id)->first();
        $kamar         = DB::table('kamars')->where('id', $request->kamar_id)->first();
        $tanggalKeluar = date('Y-m-d', strtotime($request->tanggal_masuk . ' +' . $request->lama_sewa . ' months'));

        DB::table('penyewas')->where('id', $id)->update([
            'nama_penyewa'  => $request->nama_penyewa,
            'no_ktp'        => $request->no_ktp,
            'no_telepon'    => $request->no_telepon,
            'email'         => $request->email,
            'kamar_id'      => $request->kamar_id,
            'karyawan_id'   => $request->karyawan_id,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_keluar'=> $tanggalKeluar,
            'lama_sewa'     => $request->lama_sewa,
            'total_harga'   => $kamar->harga_bulan * $request->lama_sewa,
            'uang_deposit'  => $request->uang_deposit ?? 0,
            'status'        => $request->status,
            'catatan'       => $request->catatan,
            'updated_at'    => now(),
        ]);

        // Update status kamar
        if ($penyewaLama->kamar_id != $request->kamar_id) {
            DB::table('kamars')->where('id', $penyewaLama->kamar_id)->update(['status' => 'tersedia', 'updated_at' => now()]);
            DB::table('kamars')->where('id', $request->kamar_id)->update(['status' => 'terisi', 'updated_at' => now()]);
        }
        if ($request->status === 'selesai') {
            DB::table('kamars')->where('id', $request->kamar_id)->update(['status' => 'tersedia', 'updated_at' => now()]);
        }

        return redirect()->route('penyewa-qb.index')
            ->with('success', 'Data penyewa diperbarui! (via DB::table()->update())');
    }

    /** DELETE — QB: DB::table('penyewas')->where()->delete() */
    public function destroy($id)
    {
        $penyewa = DB::table('penyewas')->where('id', $id)->first();
        abort_if(!$penyewa, 404);

        if ($penyewa->status === 'aktif') {
            return redirect()->route('penyewa-qb.index')
                ->with('error', 'Penyewa aktif tidak bisa dihapus!');
        }

        DB::table('penyewas')->where('id', $id)->delete();

        return redirect()->route('penyewa-qb.index')
            ->with('success', "{$penyewa->nama_penyewa} dihapus! (via DB::table()->delete())");
    }

    public function checkout($id)
    {
        $penyewa = DB::table('penyewas')->where('id', $id)->first();
        abort_if(!$penyewa, 404);

        DB::table('penyewas')->where('id', $id)->update([
            'status'         => 'selesai',
            'tanggal_keluar' => now()->toDateString(),
            'updated_at'     => now(),
        ]);
        DB::table('kamars')->where('id', $penyewa->kamar_id)->update([
            'status'     => 'tersedia',
            'updated_at' => now(),
        ]);

        $kamar = DB::table('kamars')->where('id', $penyewa->kamar_id)->first();
        return redirect()->route('penyewa-qb.index')
            ->with('success', "{$penyewa->nama_penyewa} checkout. Kamar {$kamar->nomor_kamar} tersedia.");
    }

    public function laporan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        $pendapatanBulanan = DB::table('penyewas')
            ->selectRaw('MONTH(tanggal_masuk) as bulan, SUM(total_harga) as total, COUNT(*) as jumlah')
            ->whereYear('tanggal_masuk', $tahun)
            ->groupBy(DB::raw('MONTH(tanggal_masuk)'))
            ->orderBy('bulan')
            ->get();

        $totalPendapatan = DB::table('penyewas')->whereYear('tanggal_masuk', $tahun)->sum('total_harga');

        $segeraHabis = DB::table('penyewas')
            ->join('kamars', 'penyewas.kamar_id', '=', 'kamars.id')
            ->select('penyewas.*', 'kamars.nomor_kamar')
            ->where('penyewas.status', 'aktif')
            ->whereDate('penyewas.tanggal_keluar', '<=', now()->addDays(7))
            ->orderBy('penyewas.tanggal_keluar')
            ->get();

        return view('penyewa-qb.laporan', compact('pendapatanBulanan', 'totalPendapatan', 'segeraHabis', 'tahun'));
    }
}