@extends('layouts.app')

@section('title', 'İhaleler & Sözleşmeler')
@section('subtitle', 'Geçmiş EKAP ihalelerini ve sonuçlarını analiz edin')

@section('content')

@php
    $totalTenders = $tenders->total();
    $wonTenders = $tenders->where('status', 'Kazanıldı')->count();
    $lostTenders = $tenders->where('status', 'Kaybedildi')->count();
    $evaluating = $tenders->where('status', 'Değerlendirmede')->count();
@endphp

<div class="space-y-6">

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 shadow-sm flex items-center gap-3">
            <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Symbols/Check%20Mark%20Button.png" class="w-6 h-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
        <div>
            <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">İhale Arşivi</h2>
            <p class="mt-2 text-sm font-medium text-slate-500">
                Önceki yıllarda girilen ihalelerin maliyetlerini, araç sayılarını ve kazanan teklifleri inceleyerek stratejinizi belirleyin.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if(auth()->user()->hasPermission('tenders.create'))
            <a href="{{ route('tenders.create') }}"
               class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 hover:scale-[1.02] transition">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Briefcase.png" class="w-5 h-5">
                <span>Yeni İhale Ekle</span>
            </a>
            @endif
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
        <div class="group relative overflow-hidden rounded-[32px] bg-white p-6 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:-translate-y-1">
            <div class="absolute -right-2 -bottom-2 opacity-100 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-700 drop-shadow-2xl z-0">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Open%20Book.png" alt="Toplam" class="w-24 h-24 drop-shadow-2xl" />
            </div>
            <div class="relative flex flex-col justify-between h-full z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-3 py-1.5 rounded-xl">Kayıtlı İhale</span>
                </div>
                <div class="mt-6">
                    <div class="text-3xl font-black text-slate-900">{{ $totalTenders }}</div>
                    <div class="mt-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Toplam Kayıt</div>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-[32px] bg-white p-6 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:-translate-y-1">
            <div class="absolute -right-2 -bottom-2 opacity-100 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-700 drop-shadow-2xl z-0">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Trophy.png" alt="Kazanılan" class="w-24 h-24 drop-shadow-2xl" />
            </div>
            <div class="relative flex flex-col justify-between h-full z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest bg-emerald-50 px-3 py-1.5 rounded-xl">Kazanılan İhale</span>
                </div>
                <div class="mt-6">
                    <div class="text-3xl font-black text-slate-900">{{ $wonTenders }}</div>
                    <div class="mt-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Başarılı Sonuç</div>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-[32px] bg-white p-6 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:-translate-y-1">
            <div class="absolute -right-2 -bottom-2 opacity-100 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-700 drop-shadow-2xl z-0">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Cross%20Mark.png" alt="Kaybedilen" class="w-24 h-24 drop-shadow-2xl" />
            </div>
            <div class="relative flex flex-col justify-between h-full z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black text-rose-600 uppercase tracking-widest bg-rose-50 px-3 py-1.5 rounded-xl">Kaybedilen İhale</span>
                </div>
                <div class="mt-6">
                    <div class="text-3xl font-black text-slate-900">{{ $lostTenders }}</div>
                    <div class="mt-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Geçmiş Kayıplar</div>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-[32px] bg-white p-6 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:-translate-y-1">
            <div class="absolute -right-2 -bottom-2 opacity-100 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-700 drop-shadow-2xl z-0">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Hourglass%20Done.png" alt="Değerlendirme" class="w-24 h-24 drop-shadow-2xl" />
            </div>
            <div class="relative flex flex-col justify-between h-full z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest bg-amber-50 px-3 py-1.5 rounded-xl">Değerlendirmede</span>
                </div>
                <div class="mt-6">
                    <div class="text-3xl font-black text-slate-900">{{ $evaluating }}</div>
                    <div class="mt-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Sonuç Bekleyen</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="rounded-[30px] border border-slate-200/60 bg-white/90 p-5 shadow-xl backdrop-blur">
        <form method="GET" action="{{ route('tenders.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="md:col-span-2">
                <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">İhale Ara</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Kurum adı, İKN no, Kazanan Firma..."
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 shadow-sm outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">İhale Yılı</label>
                <select name="year" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 shadow-sm outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Tümü</option>
                    @for($y = date('Y') + 1; $y >= 2010; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 shadow-lg">
                    Ara / Filtrele
                </button>
            </div>
        </form>
    </div>

    <!-- List -->
    <div class="space-y-4">
        @forelse($tenders as $tender)
            <div class="overflow-hidden rounded-[24px] border border-slate-200/60 bg-white shadow-xl hover:shadow-2xl transition-all duration-300">
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        
                        <!-- Left Info -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                @if($tender->status === 'Kazanıldı')
                                    <span class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-600"></div> KAZANILDI
                                    </span>
                                @elseif($tender->status === 'Kaybedildi')
                                    <span class="inline-flex items-center gap-1.5 rounded-xl bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                        <div class="w-1.5 h-1.5 rounded-full bg-rose-600"></div> KAYBEDİLDİ
                                    </span>
                                @elseif($tender->status === 'Değerlendirmede')
                                    <span class="inline-flex items-center gap-1.5 rounded-xl bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                        <div class="w-1.5 h-1.5 rounded-full bg-amber-600"></div> DEĞERLENDİRMEDE
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-xl bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 ring-1 ring-inset ring-slate-500/20">
                                        <div class="w-1.5 h-1.5 rounded-full bg-slate-600"></div> {{ mb_strtoupper($tender->status, 'UTF-8') }}
                                    </span>
                                @endif
                                <span class="text-xs font-semibold text-slate-400">İKN: {{ $tender->tender_registration_number ?: 'Yok' }}</span>
                            </div>
                            
                            <h3 class="text-xl font-extrabold text-slate-900">{{ $tender->institution_name }} İhalesi</h3>
                            
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8">
                                <div>
                                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Araç İhtiyacı</div>
                                    <div class="text-sm font-semibold text-slate-800">{{ $tender->vehicle_details ?: 'Belirtilmedi' }}</div>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">İşin Süresi</div>
                                    <div class="text-sm font-semibold text-slate-800">{{ $tender->duration_days ? $tender->duration_days . ' Gün' : 'Belirtilmedi' }}</div>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Yaklaşık Maliyet</div>
                                    <div class="text-sm font-semibold text-slate-800">{{ $tender->approximate_cost ? number_format($tender->approximate_cost, 2, ',', '.') . ' ₺' : 'Gizli' }}</div>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">İhale Tarihi</div>
                                    <div class="text-sm font-semibold text-slate-800">{{ $tender->tender_date ? $tender->tender_date->format('d.m.Y') : '-' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Comparison -->
                        <div class="w-full lg:w-96 rounded-2xl bg-slate-50 border border-slate-100 p-5">
                            <h4 class="text-xs font-black tracking-widest text-slate-400 uppercase mb-4 text-center">Fiyat Karşılaştırması</h4>
                            
                            <div class="space-y-4">
                                <!-- Our Bid -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                        </div>
                                        <span class="text-sm font-bold text-slate-700">Bizim Teklifimiz</span>
                                    </div>
                                    <span class="text-base font-black text-blue-600">{{ $tender->our_bid ? number_format($tender->our_bid, 2, ',', '.') . ' ₺' : 'Verilmedi' }}</span>
                                </div>

                                <div class="h-px w-full bg-slate-200/60"></div>

                                <!-- Winning Bid -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                                        </div>
                                        <div>
                                            <span class="text-sm font-bold text-slate-700 block">Kazanan Teklif</span>
                                            <span class="text-[10px] font-semibold text-slate-400 block max-w-[120px] truncate" title="{{ $tender->winning_company }}">{{ $tender->winning_company ?: 'Bilinmiyor' }}</span>
                                        </div>
                                    </div>
                                    <span class="text-base font-black text-amber-600">{{ $tender->winning_amount ? number_format($tender->winning_amount, 2, ',', '.') . ' ₺' : 'Bilinmiyor' }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                
                <!-- Actions Footer -->
                <div class="bg-slate-50 border-t border-slate-100 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @if($tender->document_path)
                            <a href="{{ url('storage/' . $tender->document_path) }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                İhale Evrakı (PDF)
                            </a>
                        @else
                            <span class="text-sm font-medium text-slate-400">PDF Yok</span>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-2">
                        @if(auth()->user()->hasPermission('tenders.edit'))
                            <a href="{{ route('tenders.edit', $tender->id) }}" class="rounded-xl bg-blue-100 px-4 py-2 text-xs font-bold text-blue-700 hover:bg-blue-200 transition">
                                Düzenle
                            </a>
                        @endif
                        @if(auth()->user()->hasPermission('tenders.delete'))
                            <form action="{{ route('tenders.destroy', $tender->id) }}" method="POST" onsubmit="return confirm('Bu ihaleyi silmek istediğinize emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-xl bg-rose-100 px-4 py-2 text-xs font-bold text-rose-700 hover:bg-rose-200 transition">
                                    Sil
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-[30px] border border-slate-200/60 bg-white shadow-sm p-14 text-center">
                <div class="mx-auto max-w-md">
                    <div class="mb-6 flex justify-center">
                        <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Magnifying%20Glass%20Tilted%20Right.png" alt="Bulunamadı" class="w-24 h-24 drop-shadow-xl" />
                    </div>
                    <div class="text-base font-semibold text-slate-700">Kayıtlı İhale Bulunamadı</div>
                    <div class="mt-1 text-sm text-slate-500">
                        Henüz sisteme eklenmiş bir ihale kaydı bulunmuyor. Yeni ihale ekleyerek arşivi oluşturmaya başlayın.
                    </div>
                    <div class="mt-5">
                        @if(auth()->user()->hasPermission('tenders.create'))
                        <a href="{{ route('tenders.create') }}" class="inline-flex items-center rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:scale-[1.02]">
                            Yeni İhale Ekle
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforelse

        <div class="mt-6">
            {{ $tenders->links() }}
        </div>
    </div>
</div>

@endsection
