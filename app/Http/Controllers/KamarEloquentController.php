<?php
namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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