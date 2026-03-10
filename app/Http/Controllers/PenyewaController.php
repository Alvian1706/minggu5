<?php

namespace App\Http\Controllers;

use App\Models\Penyewa;
use App\Models\Kamar;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenyewaController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Penyewa::with(['kamar', 'karyawan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kamar_id')) {
            $query->where('kamar_id', $request->kamar_id);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_penyewa', 'LIKE', '%'.$request->search.'%')
                  ->orWhere('no_ktp', 'LIKE', '%'.$request->search.'%')
                  ->orWhere('no_telepon', 'LIKE', '%'.$request->search.'%');
            });
        }

        $penyewas  = $query->latest()->paginate(10);
        $kamars    = Kamar::orderBy('nomor_kamar')->get();
        $statistik = [
            'total'        => Penyewa::count(),
            'aktif'        => Penyewa::where('status', 'aktif')->count(),
            'selesai'      => Penyewa::where('status', 'selesai')->count(),
            'segera_habis' => Penyewa::where('status', 'aktif')
                                ->whereDate('tanggal_keluar', '<=', now()->addDays(7))
                                ->count(),
        ];

        return view('penyewa.index', compact('penyewas', 'kamars', 'statistik'));
    }

    
    public function create()
    {
        $kamars    = Kamar::where('status', 'tersedia')->orderBy('nomor_kamar')->get();
        $karyawans = Karyawan::where('status_kerja', 'aktif')->orderBy('nama')->get();

        return view('penyewa.create', compact('kamars', 'karyawans'));
    }

  
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

        // Eloquent findOrFail
        $kamar         = Kamar::findOrFail($request->kamar_id);
        $totalHarga    = $kamar->harga_bulan * $request->lama_sewa;
        $tanggalKeluar = date('Y-m-d', strtotime($request->tanggal_masuk.' +'.$request->lama_sewa.' months'));

        // Eloquent CREATE
        $penyewa = Penyewa::create([
            'nama_penyewa'   => $request->nama_penyewa,
            'no_ktp'         => $request->no_ktp,
            'no_telepon'     => $request->no_telepon,
            'email'          => $request->email,
            'kamar_id'       => $request->kamar_id,
            'karyawan_id'    => $request->karyawan_id,
            'tanggal_masuk'  => $request->tanggal_masuk,
            'tanggal_keluar' => $tanggalKeluar,
            'lama_sewa'      => $request->lama_sewa,
            'total_harga'    => $totalHarga,
            'uang_deposit'   => $request->uang_deposit ?? 0,
            'status'         => 'aktif',
            'catatan'        => $request->catatan,
        ]);

        // Update status kamar via Eloquent relasi
        $kamar->update(['status' => 'terisi']);

        return redirect()->route('penyewa.index')
            ->with('success', "{$penyewa->nama_penyewa} berhasil didaftarkan di Kamar {$kamar->nomor_kamar}!");
    }

    /**
     * READ DETAIL — Detail penyewa
     * Eloquent: Penyewa::with()->findOrFail($id)
     */
    public function show($id)
    {
        $penyewa  = Penyewa::with(['kamar', 'karyawan'])->findOrFail($id);
        $sisaHari = now()->diffInDays(\Carbon\Carbon::parse($penyewa->tanggal_keluar), false);

        return view('penyewa.show', compact('penyewa', 'sisaHari'));
    }

    /**
     * UPDATE FORM — Form edit penyewa
     * Eloquent: Penyewa::findOrFail($id)
     */
    public function edit($id)
    {
        $penyewa   = Penyewa::findOrFail($id);
        $kamars    = Kamar::whereIn('status', ['tersedia', 'terisi'])->orderBy('nomor_kamar')->get();
        $karyawans = Karyawan::where('status_kerja', 'aktif')->orderBy('nama')->get();

        return view('penyewa.edit', compact('penyewa', 'kamars', 'karyawans'));
    }

    /**
     * UPDATE STORE — Perbarui data penyewa
     * Eloquent: $penyewa->fill([...])->save()
     */
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

        $penyewa       = Penyewa::findOrFail($id);
        $kamarLama     = $penyewa->kamar_id;
        $kamar         = Kamar::findOrFail($request->kamar_id);
        $totalHarga    = $kamar->harga_bulan * $request->lama_sewa;
        $tanggalKeluar = date('Y-m-d', strtotime($request->tanggal_masuk.' +'.$request->lama_sewa.' months'));

        // Eloquent: fill + save
        $penyewa->fill([
            'nama_penyewa'   => $request->nama_penyewa,
            'no_ktp'         => $request->no_ktp,
            'no_telepon'     => $request->no_telepon,
            'email'          => $request->email,
            'kamar_id'       => $request->kamar_id,
            'karyawan_id'    => $request->karyawan_id,
            'tanggal_masuk'  => $request->tanggal_masuk,
            'tanggal_keluar' => $tanggalKeluar,
            'lama_sewa'      => $request->lama_sewa,
            'total_harga'    => $totalHarga,
            'uang_deposit'   => $request->uang_deposit ?? 0,
            'status'         => $request->status,
            'catatan'        => $request->catatan,
        ])->save();

        // Update status kamar jika pindah
        if ($kamarLama != $request->kamar_id) {
            Kamar::where('id', $kamarLama)->update(['status' => 'tersedia']);
            $kamar->update(['status' => 'terisi']);
        }
        // Bebaskan kamar jika selesai
        if ($request->status === 'selesai') {
            $kamar->update(['status' => 'tersedia']);
        }

        return redirect()->route('penyewa.index')
            ->with('success', 'Data penyewa berhasil diperbarui!');
    }

    /**
     * DELETE — Hapus penyewa
     * Eloquent: $penyewa->delete()
     */
    public function destroy($id)
    {
        $penyewa = Penyewa::findOrFail($id);

        if ($penyewa->status === 'aktif') {
            return redirect()->route('penyewa.index')
                ->with('error', 'Penyewa aktif tidak bisa dihapus! Lakukan checkout terlebih dahulu.');
        }

        $penyewa->delete();

        return redirect()->route('penyewa.index')
            ->with('success', 'Data penyewa berhasil dihapus!');
    }

    /**
     * CHECKOUT — Selesaikan kontrak
     * Eloquent: $penyewa->update() + $penyewa->kamar->update()
     */
    public function checkout($id)
    {
        $penyewa = Penyewa::with('kamar')->findOrFail($id);

        $penyewa->update([
            'status'         => 'selesai',
            'tanggal_keluar' => now()->toDateString(),
        ]);

        // Update status kamar via relasi Eloquent
        $penyewa->kamar->update(['status' => 'tersedia']);

        return redirect()->route('penyewa.index')
            ->with('success', "{$penyewa->nama_penyewa} telah checkout. Kamar {$penyewa->kamar->nomor_kamar} kini tersedia.");
    }

    /**
     * Laporan pendapatan
     * Eloquent: selectRaw + groupBy + whereYear
     */
    public function laporan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        $pendapatanBulanan = Penyewa::selectRaw(
            'MONTH(tanggal_masuk) as bulan, SUM(total_harga) as total, COUNT(*) as jumlah'
        )
            ->whereYear('tanggal_masuk', $tahun)
            ->groupBy(DB::raw('MONTH(tanggal_masuk)'))
            ->orderBy('bulan')
            ->get();

        $totalPendapatan = Penyewa::whereYear('tanggal_masuk', $tahun)->sum('total_harga');

        $segeraHabis = Penyewa::with('kamar')
            ->where('status', 'aktif')
            ->whereDate('tanggal_keluar', '<=', now()->addDays(7))
            ->whereDate('tanggal_keluar', '>=', now())
            ->orderBy('tanggal_keluar')
            ->get();

        return view('penyewa.laporan', compact('pendapatanBulanan', 'totalPendapatan', 'segeraHabis', 'tahun'));
    }
}