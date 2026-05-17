<?php
namespace App\Http\Controllers;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = Carbon::now()->year;
        // Ambil tahun dari request, default ke saat ini
        $selectedYear = $request->input('year', $currentYear);
        $availableYears = range($currentYear - 5, $currentYear + 5);

        // Ambil settings Lapangan & Pengolahan untuk tahun yang dipilih
        $settingLapangan = Setting::where('tahun', $selectedYear)->where('posisi_kode', 1)->first();
        $settingPengolahan = Setting::where('tahun', $selectedYear)->where('posisi_kode', 2)->first();

        return view('admin.settings.index', compact( // Pastikan arah folder view-nya benar (pengaturan atau settings)
            'selectedYear', 'availableYears', 'currentYear',
            'settingLapangan', 'settingPengolahan'
        ));
    }

    public function update(Request $request)
    {
        // 1. Bersihkan titik dari input Rupiah sebelum divalidasi (misal: 6.000.000 jadi 6000000)
        $request->merge([
            'batas_honor_lapangan' => str_replace('.', '', $request->batas_honor_lapangan),
            'batas_honor_pengolahan' => str_replace('.', '', $request->batas_honor_pengolahan),
        ]);

        $validated = $request->validate([
            'tahun' => 'required|integer|digits:4',
            'batas_honor_lapangan' => 'required|numeric|min:0',
            'batas_honor_pengolahan' => 'required|numeric|min:0',
            'dasar_aturan' => 'nullable|string|max:255', // Validasi Nomor SK
        ]);
        
        $tahun = $validated['tahun'];
        $namaAdmin = Auth::user()->nama ?? 'Administrator'; // Ambil nama admin yang sedang login

        // 2. Update atau buat setting Lapangan
        Setting::updateOrCreate(
            ['tahun' => $tahun, 'posisi_kode' => 1], 
            [
                'batas_honor' => $validated['batas_honor_lapangan'],
                'dasar_aturan' => $validated['dasar_aturan'],
                'updated_by' => $namaAdmin
            ]
        );
        
        // 3. Update atau buat setting Pengolahan
        Setting::updateOrCreate(
            ['tahun' => $tahun, 'posisi_kode' => 2], 
            [
                'batas_honor' => $validated['batas_honor_pengolahan'],
                'dasar_aturan' => $validated['dasar_aturan'],
                'updated_by' => $namaAdmin
            ]
        );

        // Sesuaikan nama rute 'settings.index' atau 'admin.settings.index' dengan milik Anda
        return redirect()->route('settings.index', ['year' => $tahun])
                         ->with('success', "Pengaturan batas honor tahun $tahun berhasil diperbarui.");
    }
}