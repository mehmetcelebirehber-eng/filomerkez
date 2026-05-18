@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 mesh-gradient-light min-h-screen">
    
    <!-- HEADER -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white shadow-xl shadow-blue-500/20 ring-1 ring-black/5">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/File%20Folder.png" alt="Folder" class="h-8 w-8 drop-shadow-md animate-bounce-slow" />
            </div>
            <div>
                <h1 class="text-2xl font-black tracking-tight text-slate-900">{{ $tender->institution_name }}</h1>
                <p class="text-sm font-medium text-slate-500 mt-1">İhale Arşivi ve Geçmiş Teklifler</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('tenders.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-300 hover:bg-slate-50 hover:shadow-md hover:ring-slate-300 active:scale-95">
                <i class="fi fi-rr-arrow-small-left text-lg"></i>
                Listeye Dön
            </a>
            @if(auth()->user()->hasPermission('tenders.create'))
                <button onclick="document.getElementById('recordModal').classList.remove('hidden')" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-blue-500/40 active:scale-95">
                    <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Briefcase.png" alt="Add" class="h-5 w-5 drop-shadow-sm" />
                    <span>Yeni Kayıt Ekle</span>
                </button>
            @endif
        </div>
    </div>

    <!-- DETAILS CARD -->
    <div class="mb-6 rounded-3xl bg-white p-6 shadow-xl shadow-slate-200/40 ring-1 ring-slate-100">
        <h2 class="text-lg font-bold text-slate-800 mb-2">Araç İhtiyacı Detayı</h2>
        <p class="text-slate-600">{{ $tender->vehicle_details ?: 'Belirtilmemiş' }}</p>
    </div>

    <!-- RECORDS TABLE -->
    <div class="rounded-3xl bg-white p-2 shadow-xl shadow-slate-200/40 ring-1 ring-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50/50 text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-black uppercase tracking-wider text-[11px] rounded-tl-2xl">Tarih & İKN</th>
                        <th class="px-6 py-4 font-black uppercase tracking-wider text-[11px]">Araç Detayları</th>
                        <th class="px-6 py-4 font-black uppercase tracking-wider text-[11px]">Durum</th>
                        <th class="px-6 py-4 font-black uppercase tracking-wider text-[11px] text-right">Bizim Teklifimiz</th>
                        <th class="px-6 py-4 font-black uppercase tracking-wider text-[11px] text-right">Kazanan & Tutar</th>
                        <th class="px-6 py-4 font-black uppercase tracking-wider text-[11px] text-center">Dosya</th>
                        <th class="px-6 py-4 font-black uppercase tracking-wider text-[11px] text-right rounded-tr-2xl">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/80 font-medium text-slate-700">
                    @forelse($records as $record)
                        <tr class="group hover:bg-slate-50/50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $record->tender_date ? $record->tender_date->format('d.m.Y') : '-' }}</div>
                                <div class="text-[10px] text-slate-500 mt-0.5">İKN: {{ $record->tender_registration_number ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2 mb-1">
                                    <span class="inline-flex items-center gap-1 rounded bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-600" title="Toplam Araç">
                                        <i class="fi fi-rr-cars"></i> {{ $record->total_vehicles ?? 0 }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 rounded bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-600" title="Minibüs">
                                        MİN: {{ $record->minibus_count ?? 0 }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 rounded bg-indigo-50 px-2 py-0.5 text-[10px] font-bold text-indigo-600" title="Midibüs">
                                        MİD: {{ $record->midibus_count ?? 0 }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 rounded bg-violet-50 px-2 py-0.5 text-[10px] font-bold text-violet-600" title="Otobüs">
                                        OTO: {{ $record->bus_count ?? 0 }}
                                    </span>
                                </div>
                                <div class="text-[10px] text-slate-500">Model Şartı: <span class="font-bold text-slate-700">{{ $record->vehicle_model_requirement ?? 'Yok' }}</span></div>
                            </td>
                            <td class="px-6 py-4">
                                @if($record->status === 'Kazanıldı')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-black text-emerald-600 ring-1 ring-emerald-500/20">
                                        <div class="h-1.5 w-1.5 rounded-full bg-emerald-500 shadow-[0_0_5px_rgba(16,185,129,0.5)]"></div>
                                        KAZANILDI
                                    </span>
                                @elseif($record->status === 'Kaybedildi')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1 text-[11px] font-black text-rose-600 ring-1 ring-rose-500/20">
                                        <div class="h-1.5 w-1.5 rounded-full bg-rose-500 shadow-[0_0_5px_rgba(244,63,94,0.5)]"></div>
                                        KAYBEDİLDİ
                                    </span>
                                @elseif($record->status === 'İptal')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-[11px] font-black text-slate-500 ring-1 ring-slate-500/20">
                                        <div class="h-1.5 w-1.5 rounded-full bg-slate-400"></div>
                                        İPTAL
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-[11px] font-black text-amber-600 ring-1 ring-amber-500/20">
                                        <div class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></div>
                                        DEĞERLENDİRMEDE
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold {{ $record->our_bid ? 'text-slate-900' : 'text-slate-400' }}">
                                    {{ $record->our_bid ? number_format($record->our_bid, 2, ',', '.') . ' ₺' : '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold {{ $record->winning_amount ? 'text-emerald-600' : 'text-slate-400' }}">
                                    {{ $record->winning_amount ? number_format($record->winning_amount, 2, ',', '.') . ' ₺' : '-' }}
                                </span>
                                @if($record->winning_company)
                                    <div class="text-[10px] font-bold text-slate-500 mt-1 truncate max-w-[150px]" title="{{ $record->winning_company }}">{{ $record->winning_company }}</div>
                                    @if($record->winning_unit_price)
                                    <div class="text-[10px] text-slate-400">Birim Fiyat: {{ number_format($record->winning_unit_price, 2, ',', '.') }} ₺</div>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($record->document_path)
                                    <a href="{{ Storage::url($record->document_path) }}" target="_blank" class="inline-flex items-center justify-center h-8 w-8 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="PDF Görüntüle">
                                        <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Page%20with%20Curl.png" class="w-4 h-4" />
                                    </a>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" onclick="viewBids({{ json_encode($record->bids ?? []) }})" class="group/btn flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition-all hover:bg-amber-100 hover:scale-110" title="Teklif Veren Firmalar">
                                        <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Handshake.png" alt="Bids" class="h-5 w-5 drop-shadow-sm transition-transform group-hover/btn:scale-110" />
                                    </button>
                                    @if(auth()->user()->hasPermission('tenders.delete'))
                                        <form action="{{ route('tenders.records.destroy', [$tender->id, $record->id]) }}" method="POST" onsubmit="return confirm('Bu geçmiş ihale kaydını silmek istediğinize emin misiniz?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="group/btn flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition-all hover:bg-rose-100 hover:scale-110" title="Sil">
                                                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Wastebasket.png" alt="Delete" class="h-5 w-5 drop-shadow-sm transition-transform group-hover/btn:scale-110" />
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Magnifying%20Glass%20Tilted%20Right.png" alt="Empty" class="h-16 w-16 opacity-80" />
                                    <p class="mt-4 text-sm font-bold text-slate-500">Kayıt Bulunamadı</p>
                                    <p class="mt-1 text-xs text-slate-400">Bu kurum için henüz ihale kaydı eklenmemiş.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- YENİ KAYIT MODALI -->
<div id="recordModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('recordModal').classList.add('hidden')"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="w-full max-w-4xl rounded-3xl bg-white shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between border-b border-slate-100 p-6 shrink-0 bg-slate-50/50">
                <h3 class="text-xl font-black text-slate-900 flex items-center gap-3 tracking-tight">
                    <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Briefcase.png" class="w-7 h-7 drop-shadow-sm"/>
                    Kapsamlı İhale Kaydı Oluştur
                </h3>
                <button onclick="document.getElementById('recordModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors h-8 w-8 flex items-center justify-center rounded-full hover:bg-slate-200">
                    <i class="fi fi-rr-cross"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto">
                <form action="{{ route('tenders.records.store', $tender->id) }}" method="POST" enctype="multipart/form-data" id="tenderForm">
                    @csrf
                    
                    <div class="grid grid-cols-12 gap-x-6 gap-y-8">
                        
                        <!-- 1. BÖLÜM: TEMEL BİLGİLER -->
                        <div class="col-span-12">
                            <h4 class="text-xs font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 pb-2 mb-4">1. Temel Bilgiler</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="mb-1.5 block text-xs font-bold text-slate-700">İhale Tarihi <span class="text-rose-500">*</span></label>
                                    <input type="date" name="tender_date" required class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm font-medium focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-xs font-bold text-slate-700">İKN Numarası</label>
                                    <input type="text" name="tender_registration_number" placeholder="Örn: 2024/12345" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm font-medium focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-xs font-bold text-slate-700">Süre (Gün)</label>
                                    <input type="number" name="duration_days" placeholder="Örn: 365" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm font-medium focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                                </div>
                            </div>
                        </div>

                        <!-- 2. BÖLÜM: ARAÇ DETAYLARI -->
                        <div class="col-span-12">
                            <h4 class="text-xs font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 pb-2 mb-4">2. Araç İhtiyaç Detayları</h4>
                            <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                                <div class="col-span-2 md:col-span-2">
                                    <label class="mb-1.5 block text-xs font-bold text-slate-700">Toplam Araç Adedi</label>
                                    <input type="number" name="total_vehicles" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm font-medium focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10" placeholder="0">
                                </div>
                                <div class="col-span-2 md:col-span-4">
                                    <label class="mb-1.5 block text-xs font-bold text-slate-700">Araç Model Şartı</label>
                                    <input type="text" name="vehicle_model_requirement" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm font-medium focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10" placeholder="Örn: 2020 ve Üzeri">
                                </div>
                                
                                <div>
                                    <label class="mb-1.5 block text-[10px] font-bold text-slate-500 uppercase">Minibüs</label>
                                    <input type="number" name="minibus_count" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2 px-3 text-sm focus:border-blue-500" placeholder="0">
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-[10px] font-bold text-slate-500 uppercase">Midibüs</label>
                                    <input type="number" name="midibus_count" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2 px-3 text-sm focus:border-blue-500" placeholder="0">
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-[10px] font-bold text-slate-500 uppercase">Otobüs</label>
                                    <input type="number" name="bus_count" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2 px-3 text-sm focus:border-blue-500" placeholder="0">
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-[10px] font-bold text-slate-500 uppercase">Taksi / Oto.</label>
                                    <input type="number" name="taxi_count" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2 px-3 text-sm focus:border-blue-500" placeholder="0">
                                </div>
                            </div>
                        </div>

                        <!-- 3. BÖLÜM: MALİYET VE TEKLİFLER -->
                        <div class="col-span-12">
                            <h4 class="text-xs font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 pb-2 mb-4">3. Maliyet ve Teklifler</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="mb-1.5 block text-xs font-bold text-slate-700">Yaklaşık Maliyet (₺)</label>
                                    <input type="number" step="0.01" name="approximate_cost" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm font-medium focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-xs font-bold text-slate-700">Bizim Teklifimiz (₺)</label>
                                    <input type="number" step="0.01" name="our_bid" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm font-bold text-blue-700 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                                </div>
                            </div>

                            <div class="rounded-2xl bg-slate-50 ring-1 ring-slate-200 p-4 mb-6">
                                <div class="flex items-center justify-between mb-3">
                                    <h5 class="text-sm font-bold text-slate-700">İhaleye Giren Firmalar</h5>
                                    <button type="button" onclick="addBidRow()" class="px-3 py-1.5 bg-white rounded-lg text-xs font-bold text-blue-600 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50">+ Firma Ekle</button>
                                </div>
                                <div id="bidsContainer" class="space-y-3">
                                    <!-- Dynamic Bids Will Go Here -->
                                </div>
                            </div>
                        </div>

                        <!-- 4. BÖLÜM: SONUÇ VE BELGELER -->
                        <div class="col-span-12">
                            <h4 class="text-xs font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 pb-2 mb-4">4. İhale Sonucu & Belgeler</h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="col-span-1">
                                    <label class="mb-1.5 block text-xs font-bold text-slate-700">Sonuç <span class="text-rose-500">*</span></label>
                                    <select name="status" required class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm font-medium focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                                        <option value="Değerlendirmede">Değerlendirmede</option>
                                        <option value="Kazanıldı">Kazanıldı</option>
                                        <option value="Kaybedildi">Kaybedildi</option>
                                        <option value="İptal">İptal</option>
                                    </select>
                                </div>
                                <div class="col-span-3">
                                    <label class="mb-1.5 block text-xs font-bold text-slate-700">Kazanan Firma (Yukarıdan seçebilirsiniz)</label>
                                    <div class="flex gap-2">
                                        <select id="winnerSelect" onchange="applyWinner()" class="block w-1/3 rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm font-medium focus:border-blue-500 focus:bg-white">
                                            <option value="">Firma Seçiniz...</option>
                                        </select>
                                        <input type="text" id="winning_company" name="winning_company" placeholder="Firma Adı" class="block w-1/3 rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm font-medium focus:border-blue-500">
                                        <input type="number" step="0.01" id="winning_amount" name="winning_amount" placeholder="Toplam Tutar (₺)" class="block w-1/3 rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm font-medium focus:border-blue-500">
                                        <input type="number" step="0.01" id="winning_unit_price" name="winning_unit_price" placeholder="Birim Fiyat (₺)" class="block w-1/3 rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm font-medium focus:border-blue-500">
                                    </div>
                                </div>

                                <div class="col-span-4 mt-2">
                                    <label class="mb-1.5 block text-xs font-bold text-slate-700">Şartname / İhale Dosyası (PDF)</label>
                                    <input type="file" name="document" accept=".pdf" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors cursor-pointer">
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100 bg-slate-50/50 -mx-6 -mb-6 px-6 pb-6 rounded-b-3xl">
                        <button type="button" onclick="document.getElementById('recordModal').classList.add('hidden')" class="px-6 py-3 text-sm font-bold text-slate-600 bg-white shadow-sm ring-1 ring-slate-200 rounded-xl hover:bg-slate-50 active:scale-95 transition-all">İptal Et</button>
                        <button type="submit" class="px-8 py-3 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:shadow-lg hover:shadow-blue-500/30 active:scale-95 transition-all">Dosyayı Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- BİDS GÖRÜNTÜLEME MODALI -->
<div id="bidsViewModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('bidsViewModal').classList.add('hidden')"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="w-full max-w-lg rounded-3xl bg-white shadow-2xl overflow-hidden flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 p-6 shrink-0 bg-slate-50/50">
                <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                    <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Handshake.png" class="w-6 h-6"/>
                    İhaleye Giren Firmalar
                </h3>
                <button onclick="document.getElementById('bidsViewModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors h-8 w-8 flex items-center justify-center rounded-full hover:bg-slate-200">
                    <i class="fi fi-rr-cross"></i>
                </button>
            </div>
            <div class="p-6">
                <div id="bidsViewContainer" class="space-y-3"></div>
            </div>
        </div>
    </div>
</div>

<script>
    let bidIndex = 0;
    
    function addBidRow() {
        const container = document.getElementById('bidsContainer');
        const row = document.createElement('div');
        row.className = 'flex gap-2 items-center bid-row';
        row.innerHTML = `
            <input type="text" name="bids[${bidIndex}][company_name]" placeholder="Firma Adı" required class="bid-company block w-full rounded-xl border-slate-200 bg-white py-2 px-3 text-sm focus:border-blue-500" oninput="updateWinnerSelect()">
            <input type="number" step="0.01" name="bids[${bidIndex}][bid_amount]" placeholder="Teklif Tutarı (₺)" required class="bid-amount block w-full rounded-xl border-slate-200 bg-white py-2 px-3 text-sm focus:border-blue-500" oninput="updateWinnerSelect()">
            <button type="button" onclick="this.parentElement.remove(); updateWinnerSelect();" class="shrink-0 h-9 w-9 flex items-center justify-center rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-100 transition-colors">
                <i class="fi fi-rr-trash"></i>
            </button>
        `;
        container.appendChild(row);
        bidIndex++;
        updateWinnerSelect();
    }

    function updateWinnerSelect() {
        const select = document.getElementById('winnerSelect');
        const companies = document.querySelectorAll('.bid-company');
        const amounts = document.querySelectorAll('.bid-amount');
        
        select.innerHTML = '<option value="">Firma Seçiniz...</option>';
        
        companies.forEach((input, index) => {
            if(input.value.trim() !== '') {
                const opt = document.createElement('option');
                const amt = amounts[index] ? amounts[index].value : '';
                opt.value = index;
                opt.dataset.company = input.value;
                opt.dataset.amount = amt;
                opt.textContent = input.value + (amt ? ' - ' + parseFloat(amt).toLocaleString('tr-TR') + ' ₺' : '');
                select.appendChild(opt);
            }
        });
    }

    function applyWinner() {
        const select = document.getElementById('winnerSelect');
        const selected = select.options[select.selectedIndex];
        
        if(selected && selected.value !== "") {
            document.getElementById('winning_company').value = selected.dataset.company;
            document.getElementById('winning_amount').value = selected.dataset.amount;
        }
    }

    function viewBids(bids) {
        const container = document.getElementById('bidsViewContainer');
        container.innerHTML = '';
        
        if(!bids || bids.length === 0) {
            container.innerHTML = '<p class="text-sm text-slate-500 text-center py-4">Sisteme kayıtlı firma teklifi bulunmuyor.</p>';
        } else {
            // Sort bids by amount
            bids.sort((a, b) => parseFloat(a.bid_amount) - parseFloat(b.bid_amount));
            
            bids.forEach((bid, index) => {
                const isLowest = index === 0;
                container.innerHTML += `
                    <div class="flex justify-between items-center p-3 rounded-xl ${isLowest ? 'bg-emerald-50 ring-1 ring-emerald-200' : 'bg-slate-50 ring-1 ring-slate-100'}">
                        <div>
                            <span class="block font-bold text-slate-900">${bid.company_name}</span>
                            ${isLowest ? '<span class="text-[10px] font-black text-emerald-600">EN DÜŞÜK TEKLİF</span>' : ''}
                        </div>
                        <div class="font-black ${isLowest ? 'text-emerald-700' : 'text-slate-600'}">
                            ${parseFloat(bid.bid_amount).toLocaleString('tr-TR', {minimumFractionDigits: 2})} ₺
                        </div>
                    </div>
                `;
            });
        }
        
        document.getElementById('bidsViewModal').classList.remove('hidden');
    }

    // Add 2 empty rows by default when opening modal
    document.addEventListener('DOMContentLoaded', () => {
        addBidRow();
        addBidRow();
    });
</script>
@endsection
