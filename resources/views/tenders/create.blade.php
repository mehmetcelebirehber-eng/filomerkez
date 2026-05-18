@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 mesh-gradient-light min-h-screen">
    <div class="mx-auto max-w-4xl">
        <!-- HEADER -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white shadow-xl shadow-blue-500/20 ring-1 ring-black/5">
                    <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/File%20Folder.png" alt="Folder" class="h-8 w-8 drop-shadow-md animate-bounce-slow" />
                </div>
                <div>
                    <h1 class="text-2xl font-black tracking-tight text-slate-900">İhale Dosyası Oluştur</h1>
                    <p class="text-sm font-medium text-slate-500 mt-1">Kurum bazında ihaleleri takip etmek için ana dosya oluşturun</p>
                </div>
            </div>
            <a href="{{ route('tenders.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-300 hover:bg-slate-50 hover:shadow-md hover:ring-slate-300 active:scale-95">
                <i class="fi fi-rr-arrow-small-left text-lg"></i>
                Listeye Dön
            </a>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-xl shadow-slate-200/40 ring-1 ring-slate-100 sm:p-8 relative overflow-hidden">
            <form action="{{ route('tenders.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-12 gap-6">
                    <!-- Kurum / İhale Adı -->
                    <div class="col-span-12">
                        <label class="mb-2 block text-sm font-bold text-slate-700">Kurum / İhale Adı <span class="text-rose-500">*</span></label>
                        <div class="group relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 transition-colors duration-300 group-focus-within:text-blue-500">
                                <i class="fi fi-rr-building text-lg text-slate-400 group-focus-within:text-blue-500"></i>
                            </div>
                            <input type="text" name="institution_name" value="{{ old('institution_name') }}" required
                                class="block w-full rounded-2xl border-slate-200 bg-slate-50/50 py-3 pl-12 pr-4 text-sm font-medium text-slate-700 shadow-sm transition-all duration-300 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300"
                                placeholder="Örn: Devlet Su İşleri">
                        </div>
                        @error('institution_name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Araç İhtiyacı Detayı -->
                    <div class="col-span-12">
                        <label class="mb-2 block text-sm font-bold text-slate-700">Araç İhtiyacı Detayı</label>
                        <div class="group relative">
                            <textarea name="vehicle_details" rows="3"
                                class="block w-full rounded-2xl border-slate-200 bg-slate-50/50 p-4 text-sm font-medium text-slate-700 shadow-sm transition-all duration-300 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300"
                                placeholder="Örn: 2 Adet Minibüs, 1 Adet Otobüs">{{ old('vehicle_details') }}</textarea>
                        </div>
                        @error('vehicle_details') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- SUBMIT -->
                <div class="mt-10 flex items-center justify-end border-t border-slate-100 pt-6">
                    <button type="submit" class="group relative inline-flex items-center justify-center gap-3 overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-blue-500/40 active:scale-95">
                        <div class="absolute inset-0 bg-white/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                        <span>İhale Dosyasını Oluştur</span>
                        <i class="fi fi-rr-arrow-right text-lg transition-transform duration-300 group-hover:translate-x-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
