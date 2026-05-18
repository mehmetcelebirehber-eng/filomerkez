@extends('layouts.app')

@section('title', 'İhale Düzenle')
@section('subtitle', 'Mevcut ihale kaydını güncelleyin')

@section('content')

<div class="mx-auto max-w-4xl space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('tenders.index') }}" class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-700">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <h2 class="text-2xl font-extrabold text-slate-900">İhale Güncelle</h2>
    </div>

    <form action="{{ route('tenders.update', $tender->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="overflow-hidden rounded-[30px] border border-slate-200/60 bg-white/90 p-8 shadow-xl backdrop-blur">
            <h3 class="mb-6 flex items-center gap-2 text-lg font-bold text-slate-800">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Briefcase.png" class="w-6 h-6"> 
                Genel İhale Bilgileri
            </h3>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Kurum / İhale Adı <span class="text-rose-500">*</span></label>
                    <input type="text" name="institution_name" required value="{{ old('institution_name', $tender->institution_name) }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    @error('institution_name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">İhale Tarihi <span class="text-rose-500">*</span></label>
                    <input type="date" name="tender_date" required value="{{ old('tender_date', $tender->tender_date ? $tender->tender_date->format('Y-m-d') : '') }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    @error('tender_date') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">İhale Kayıt No (İKN)</label>
                    <input type="text" name="tender_registration_number" value="{{ old('tender_registration_number', $tender->tender_registration_number) }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Araç İhtiyacı Detayı</label>
                    <input type="text" name="vehicle_details" value="{{ old('vehicle_details', $tender->vehicle_details) }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">İşin Süresi (Gün)</label>
                    <input type="number" name="duration_days" value="{{ old('duration_days', $tender->duration_days) }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Durum <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        <option value="Değerlendirmede" {{ $tender->status == 'Değerlendirmede' ? 'selected' : '' }}>Değerlendirmede</option>
                        <option value="Kazanıldı" {{ $tender->status == 'Kazanıldı' ? 'selected' : '' }}>Kazanıldı</option>
                        <option value="Kaybedildi" {{ $tender->status == 'Kaybedildi' ? 'selected' : '' }}>Kaybedildi</option>
                        <option value="İptal" {{ $tender->status == 'İptal' ? 'selected' : '' }}>İptal Edildi</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-[30px] border border-slate-200/60 bg-white/90 p-8 shadow-xl backdrop-blur">
            <h3 class="mb-6 flex items-center gap-2 text-lg font-bold text-slate-800">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Money%20Bag.png" class="w-6 h-6"> 
                Maliyet ve Sonuçlar
            </h3>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Yaklaşık Maliyet (₺)</label>
                    <input type="number" step="0.01" name="approximate_cost" value="{{ old('approximate_cost', $tender->approximate_cost) }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Bizim Teklifimiz (₺)</label>
                    <input type="number" step="0.01" name="our_bid" value="{{ old('our_bid', $tender->our_bid) }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-blue-700 font-bold bg-blue-50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <div class="md:col-span-2 h-px bg-slate-100 my-2"></div>

                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">İhaleyi Kazanan Firma</label>
                    <input type="text" name="winning_company" value="{{ old('winning_company', $tender->winning_company) }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Kazanan Tutar (₺)</label>
                    <input type="number" step="0.01" name="winning_amount" value="{{ old('winning_amount', $tender->winning_amount) }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-amber-700 font-bold bg-amber-50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-[30px] border border-slate-200/60 bg-white/90 p-8 shadow-xl backdrop-blur">
            <h3 class="mb-6 flex items-center gap-2 text-lg font-bold text-slate-800">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Open%20File%20Folder.png" class="w-6 h-6"> 
                Ekstra Bilgiler ve Evrak
            </h3>

            <div class="space-y-6">
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">İhale Dokümanı (PDF)</label>
                    @if($tender->document_path)
                        <div class="mb-3">
                            <a href="{{ url('storage/' . $tender->document_path) }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                Mevcut Evrağı Görüntüle
                            </a>
                        </div>
                    @endif
                    <input type="file" name="document" accept=".pdf"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 bg-slate-50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    <p class="mt-1 text-[10px] text-slate-400">Yeni bir dosya seçerseniz mevcut dosya silinip yenisi yüklenir.</p>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Özel Notlar ve Şartname Detayları</label>
                    <textarea name="notes" rows="4"
                              class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">{{ old('notes', $tender->notes) }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('tenders.index') }}" class="rounded-2xl px-6 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">İptal</a>
            <button type="submit" class="rounded-2xl bg-slate-900 px-8 py-3 text-sm font-semibold text-white shadow-xl shadow-slate-900/20 transition hover:scale-[1.02] hover:bg-slate-800">
                Değişiklikleri Kaydet
            </button>
        </div>
    </form>
</div>
@endsection
