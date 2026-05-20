@extends('layouts.portal')

@section('title', 'Hoşgeldiniz - ' . $jv->company_name)

@section('content')

<div class="mb-8 rounded-3xl bg-white p-8 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
    <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
    <div class="relative">
        <h1 class="text-3xl font-black text-slate-800">Hoşgeldiniz, {{ $jv->company_name }}</h1>
        <p class="mt-2 text-slate-500 text-lg">Size atanan servis güzergahlarını aşağıda görebilir, gerekli araç ve şoför evraklarını bilgisayarınızdan sisteme yükleyebilirsiniz.</p>
    </div>
</div>

<div class="space-y-6">
    <h2 class="text-xl font-bold text-slate-800">Atanan Servis Güzergahlarınız</h2>

    @if($jv->serviceRoutes->count() > 0)
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($jv->serviceRoutes as $route)
                <div class="group relative overflow-hidden rounded-[24px] border border-slate-200/60 bg-white p-6 shadow-md transition-all hover:-translate-y-1 hover:shadow-xl hover:border-indigo-300">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 text-3xl text-indigo-600 shadow-sm">
                            🚌
                        </div>
                        <div>
                            <div class="text-xs font-bold uppercase tracking-widest text-indigo-400 mb-1">
                                GÜZERGAH
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 line-clamp-2" title="{{ $route->route_name }}">
                                {{ $route->route_name }}
                            </h3>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <!-- Duraklar Butonu -->
                        <button type="button" 
                           data-info="{{ json_encode(['routeName' => $route->route_name, 'stops' => $route->stops->map(function($s) { return ['name' => $s->stop_name, 'time' => \Carbon\Carbon::parse($s->stop_time)->format('H:i')]; })]) }}"
                           @click="$dispatch('open-stops-modal', JSON.parse($el.dataset.info))" 
                           class="group/btn relative overflow-hidden inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 px-4 py-3 text-sm font-black text-white shadow-md shadow-orange-500/20 hover:shadow-lg hover:from-amber-400 hover:to-orange-400 transition-all">
                            <!-- Animasyon Katmanı -->
                            <div class="absolute inset-0 flex h-full w-full justify-center [transform:skew(-12deg)_translateX(-150%)] group-hover/btn:duration-1000 group-hover/btn:[transform:skew(-12deg)_translateX(150%)]">
                                <div class="relative h-full w-10 bg-white/30"></div>
                            </div>
                            <span class="relative z-10">📍</span> 
                            <span class="relative z-10">SERVİS DURAKLARI</span>
                        </button>

                        <a href="{{ route('portal.jv.documents.index', ['token' => $jv->access_token, 'route' => $route->id, 'type' => 'vehicle']) }}" 
                           class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 ring-1 ring-inset ring-slate-200 hover:bg-indigo-50 hover:text-indigo-700 hover:ring-indigo-300 transition-all">
                            <span>📄</span> Araç Evrakları Yükle/Gör
                        </a>
                        
                        <a href="{{ route('portal.jv.documents.index', ['token' => $jv->access_token, 'route' => $route->id, 'type' => 'driver']) }}" 
                           class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 ring-1 ring-inset ring-slate-200 hover:bg-teal-50 hover:text-teal-700 hover:ring-teal-300 transition-all">
                            <span>👨‍✈️</span> Şoför Evrakları Yükle/Gör
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-3xl border border-dashed border-slate-300 bg-white py-20 text-center shadow-sm">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-50 text-4xl">📭</div>
            <h3 class="mt-5 text-xl font-bold text-slate-800">Atanmış Güzergah Yok</h3>
            <p class="mt-2 text-slate-500">Şu anda tarafınıza atanmış herhangi bir servis güzergahı bulunmuyor.</p>
        </div>
    @endif
</div>

<!-- Duraklar Modalı -->
<div x-data="{ 
        show: false, 
        routeName: '', 
        stops: [] 
     }"
     x-show="show"
     x-on:open-stops-modal.window="show = true; routeName = $event.detail.routeName; stops = $event.detail.stops;"
     x-on:keydown.escape.window="show = false"
     class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0"
     style="display: none;">
    
    <div x-show="show" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="show = false"></div>

    <div x-show="show" 
         x-transition.duration.300ms
         class="relative mx-auto w-full max-w-lg transform overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-slate-200 transition-all">
        
        <div class="border-b border-slate-100 bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-white backdrop-blur-sm">📍</div>
                <h3 class="text-xl font-black text-white" x-text="routeName + ' Durakları'"></h3>
            </div>
            <button @click="show = false" class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/40 transition-colors">
                ✖
            </button>
        </div>

        <div class="p-6 max-h-[60vh] overflow-y-auto custom-scrollbar">
            <template x-if="stops.length > 0">
                <div class="relative border-l-2 border-orange-200 ml-4 space-y-6 pb-2">
                    <template x-for="(stop, index) in stops" :key="index">
                        <div class="relative pl-6">
                            <div class="absolute -left-[9px] top-1 h-4 w-4 rounded-full border-4 border-white bg-orange-500 shadow-sm"></div>
                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 shadow-sm hover:border-orange-200 hover:shadow-md transition-all">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <div class="text-xs font-bold text-orange-500 mb-1" x-text="(index + 1) + '. DURAK'"></div>
                                        <div class="font-bold text-slate-800" x-text="stop.name"></div>
                                    </div>
                                    <div class="flex shrink-0 items-center gap-1.5 rounded-lg bg-white px-2.5 py-1.5 text-sm font-bold text-slate-600 shadow-sm ring-1 ring-inset ring-slate-200">
                                        ⏱️ <span x-text="stop.time || '--:--'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
            <template x-if="stops.length === 0">
                <div class="rounded-2xl border border-dashed border-slate-300 py-12 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-3xl mb-3">🚏</div>
                    <h4 class="text-lg font-bold text-slate-700">Durak Bulunamadı</h4>
                    <p class="mt-1 text-sm text-slate-500">Bu güzergaha henüz durak eklenmemiş.</p>
                </div>
            </template>
        </div>
        
        <div class="bg-slate-50 border-t border-slate-100 p-4 flex justify-end">
            <button @click="show = false" class="rounded-xl bg-white px-6 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-100 transition-colors">
                Kapat
            </button>
        </div>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

@endsection
