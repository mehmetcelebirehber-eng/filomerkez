@extends('layouts.app')

@section('title', 'Araç Takip Raporları')
@section('subtitle', 'Geçmişe Dönük Raporlamalar')

@section('content')
<div class="relative w-full space-y-6">

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 w-full">
        
        <!-- Sol Menü (Sidebar) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Tarih Seçici -->
            <div class="bg-white/70 backdrop-blur-xl p-6 rounded-[30px] border border-white shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <h3 class="text-lg font-black text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Tarih Seçimi
                </h3>
                
                <form action="{{ route('vehicle-tracking.reports.daily-work') }}" method="GET" class="space-y-4">
                    <div>
                        <input type="date" name="date" value="{{ $date }}" class="w-full bg-slate-50 border-2 border-slate-100 text-slate-900 text-sm rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 block p-4 outline-none font-bold transition-all hover:bg-white" required>
                    </div>
                    <button type="submit" class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-4 px-6 rounded-2xl shadow-lg shadow-indigo-500/30 transition-all active:scale-95 flex justify-center items-center gap-2">
                        <span>Raporu Getir</span>
                    </button>
                </form>
            </div>

            <!-- Rapor Menüsü -->
            <div class="bg-white/70 backdrop-blur-xl p-6 rounded-[30px] border border-white shadow-sm">
                <h3 class="text-lg font-black text-slate-800 mb-4">Rapor Türleri</h3>
                <nav class="space-y-2">
                    <a href="{{ route('vehicle-tracking.reports.daily-work', ['date' => $date]) }}" class="flex items-center gap-3 p-4 rounded-2xl bg-indigo-50 text-indigo-700 font-bold border border-indigo-100 transition-all">
                        <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-indigo-500 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        Araç Çalışma Raporu
                    </a>
                    
                    <!-- İleride eklenecek raporlar buraya gelecek -->
                    <div class="flex items-center gap-3 p-4 rounded-2xl bg-slate-50 text-slate-400 font-bold border border-slate-100 opacity-60 cursor-not-allowed">
                        <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-slate-400 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                        Yakıt Tüketim Raporu
                    </div>
                </nav>
            </div>
        </div>

        <!-- Sağ Taraf (Rapor Sonuçları) -->
        <div class="lg:col-span-3">
            <div class="bg-white/70 backdrop-blur-xl rounded-[30px] border border-white shadow-sm overflow-hidden h-full flex flex-col">
                <!-- Başlık -->
                <div class="p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-r from-white to-slate-50/50">
                    <div>
                        <h2 class="text-2xl font-black text-slate-800 tracking-tight">İlk Kontak Açılma Raporu</h2>
                        <p class="text-slate-500 font-medium mt-1">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y l') }} gününe ait araçların ilk hareket saatleri</p>
                    </div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        {{ count($reports) }} Araç
                    </div>
                </div>

                <!-- Tablo -->
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 text-slate-500 text-sm uppercase tracking-wider font-bold">
                                <th class="p-6 border-b border-slate-100 whitespace-nowrap">Araç Bilgisi</th>
                                <th class="p-6 border-b border-slate-100 whitespace-nowrap">İlk Kontak Saati</th>
                                <th class="p-6 border-b border-slate-100 whitespace-nowrap">Kontak Konumu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($reports as $node => $report)
                                <tr class="group hover:bg-indigo-50/30 transition-colors">
                                    <td class="p-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl {{ $report['DateTime'] === '-' ? 'bg-slate-100 text-slate-400' : 'bg-emerald-100 text-emerald-600' }} flex items-center justify-center font-black text-sm shadow-sm group-hover:scale-110 transition-transform">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <div>
                                                <div class="text-base font-black text-slate-800">{{ $report['LicensePlate'] }}</div>
                                                <div class="text-xs font-bold text-slate-400 mt-0.5">{{ $report['Driver'] ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="p-6">
                                        @if($report['DateTime'] === '-')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-500 text-sm font-bold">
                                                Veri Yok
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-700 text-sm font-bold shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ explode(' ', $report['DateTime'])[1] ?? $report['DateTime'] }}
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="p-6">
                                        @if($report['DateTime'] === '-')
                                            <span class="text-slate-400 font-medium text-sm">Kontak açılmamış veya cihaza ulaşılamadı.</span>
                                        @else
                                            <div class="flex flex-col gap-1">
                                                <span class="text-slate-700 font-medium text-sm line-clamp-2 max-w-md" title="{{ $report['Address'] }}">
                                                    {{ $report['Address'] ?: 'Konum çözümlenemedi' }}
                                                </span>
                                                <a href="https://maps.google.com/?q={{ $report['Latitude'] }},{{ $report['Longitude'] }}" target="_blank" class="text-xs font-bold text-indigo-500 hover:text-indigo-600 hover:underline flex items-center gap-1">
                                                    Haritada Gör
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-12 text-center text-slate-500">
                                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 mb-4">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                        </div>
                                        <p class="text-lg font-bold">Herhangi bir araç kaydı bulunamadı.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>

</div>
@endsection
