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


/**
 * KamarEloquentController
 * ═══════════════════════════════════════════════
 * METODE: ELOQUENT ORM (Model Kamar)
 * Versi Eloquent dari KamarController (QB)
 * ═══════════════════════════════════════════════
 */
class KamarEloquentController extends Controller
{
    /** READ — Eloquent: Kamar::query()->where()->paginate() */
    public function index(Request $request)
    {
        $query = Kamar::query();

        if ($request->filled('status'))  $query->where('status', $request->status);
        if ($request->filled('tipe'))    $query->where('tipe_kamar', $request->tipe);
        if ($request->filled('search')) {
            $query->where(fn($q) => $q
                ->where('nomor_kamar', 'LIKE', '%'.$request->search.'%')
                ->orWhere('tipe_kamar', 'LIKE', '%'.$request->search.'%'));
        }

        $kamars = $query->orderBy('nomor_kamar')->paginate(10);

        // Statistik via Eloquent
        $statistik = [
            'total'     => Kamar::count(),
            'tersedia'  => Kamar::where('status', 'tersedia')->count(),
            'terisi'    => Kamar::where('status', 'terisi')->count(),
            'perbaikan' => Kamar::where('status', 'perbaikan')->count(),
        ];

        return view('kamar-el.index', compact('kamars', 'statistik'));
    }

    public function create()
    {
        return view('kamar-el.create');
    }

    /** CREATE — Eloquent: Kamar::create([...]) */
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

        // ELOQUENT: mass assignment via create()
        $kamar = Kamar::create([
            'nomor_kamar' => strtoupper($request->nomor_kamar),
            'tipe_kamar'  => $request->tipe_kamar,
            'harga_bulan' => $request->harga_bulan,
            'luas_kamar'  => $request->luas_kamar,
            'fasilitas'   => $request->fasilitas,
            'status'      => $request->status,
        ]);

        return redirect()->route('kamar-el.index')
            ->with('success', "Kamar {$kamar->nomor_kamar} berhasil ditambahkan! (via Eloquent)");
    }

    /** READ ONE — Eloquent: Kamar::with('penyewas')->findOrFail($id) */
    public function show($id)
    {
        // Eager load relasi penyewas + karyawan
        $kamar   = Kamar::with(['penyewas.karyawan'])->findOrFail($id);
        $riwayat = $kamar->penyewas()->with('karyawan')->orderBy('tanggal_masuk', 'desc')->get();
        return view('kamar-el.show', compact('kamar', 'riwayat'));
    }

    public function edit($id)
    {
        $kamar = Kamar::findOrFail($id);
        return view('kamar-el.edit', compact('kamar'));
    }

    /** UPDATE — Eloquent: $kamar->fill([...])->save() */
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

        $kamar = Kamar::findOrFail($id);

        // ELOQUENT: fill() + save()
        $kamar->fill([
            'nomor_kamar' => strtoupper($request->nomor_kamar),
            'tipe_kamar'  => $request->tipe_kamar,
            'harga_bulan' => $request->harga_bulan,
            'luas_kamar'  => $request->luas_kamar,
            'fasilitas'   => $request->fasilitas,
            'status'      => $request->status,
        ])->save();

        return redirect()->route('kamar-el.index')
            ->with('success', 'Data kamar diperbarui! (via Eloquent fill()->save())');
    }

    /** DELETE — Eloquent: $kamar->delete() */
    public function destroy($id)
    {
        $kamar = Kamar::findOrFail($id);

        // ELOQUENT: cek relasi aktif via relationship
        if ($kamar->penyewas()->where('status', 'aktif')->exists()) {
            return redirect()->route('kamar-el.index')
                ->with('error', 'Kamar masih ada penyewa aktif! (dicek via $kamar->penyewas())');
        }

        $nomor = $kamar->nomor_kamar;
        $kamar->delete(); // ELOQUENT: soft or hard delete

        return redirect()->route('kamar-el.index')
            ->with('success', "Kamar {$nomor} dihapus! (via Eloquent \$kamar->delete())");
    }

    public function laporan()
    {
        // Eloquent: scope + aggregate
        $kamarTersedia = Kamar::tersedia()->orderBy('tipe_kamar')->get();

        $rekapTipe = Kamar::selectRaw('
            tipe_kamar,
            COUNT(*) as total,
            SUM(CASE WHEN status="tersedia" THEN 1 ELSE 0 END) as tersedia,
            SUM(CASE WHEN status="terisi"   THEN 1 ELSE 0 END) as terisi,
            AVG(harga_bulan) as rata_harga
        ')->groupBy('tipe_kamar')->get();

        return view('kamar-el.laporan', compact('kamarTersedia', 'rekapTipe'));
    }
}
