@extends('layouts.portal')

@php
    $title = $isVehicle ? 'Araç Evrakları' : 'Şoför Evrakları';
    $icon = $isVehicle ? '🚐' : '👨‍✈️';
@endphp

@section('title', $route->route_name . ' - ' . $title)

@section('content')

@if(session('success'))
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 shadow-sm mb-6">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 shadow-sm mb-6 flex flex-col gap-2">
        <div class="flex items-center gap-3 font-semibold">
            <span class="text-xl">⚠️</span> Lütfen aşağıdaki hataları düzeltin:
        </div>
        <ul class="list-disc space-y-1 pl-10">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <a href="{{ route('portal.jv.index', $jv->access_token) }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all">
        <span>⬅️</span> Portala Dön
    </a>

    <!-- Yeni Evrak Yükle Butonu -->
    <button type="button" x-data @click="$dispatch('open-modal', 'upload-document-modal')" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-500/20 hover:shadow-lg transition-all active:scale-95">
        <span>📤</span> Yeni Evrak Yükle
    </button>
</div>

<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/40">
    <div class="mb-8 flex items-center gap-4 border-b border-slate-100 pb-6">
        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-3xl shadow-inner">
            {{ $icon }}
        </div>
        <div>
            <h3 class="text-2xl font-black text-slate-800">{{ $route->route_name }} - {{ $title }}</h3>
            <p class="text-sm text-slate-500 mt-1">Sadece bu güzergaha ait {{ strtolower($title) }}ni buradan yükleyebilir ve takip edebilirsiniz.</p>
        </div>
    </div>

    @if($documents->count() > 0)
        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach($documents as $doc)
                <div class="group flex flex-col justify-between overflow-hidden rounded-2xl border border-slate-200 bg-slate-50/50 p-5 transition-all hover:border-indigo-300 hover:bg-indigo-50/30 hover:shadow-md">
                    <div>
                        <div class="mb-3 flex items-start justify-between gap-2">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-2xl text-indigo-600">
                                📄
                            </div>
                            <form action="{{ route('portal.jv.documents.destroy', ['token' => $jv->access_token, 'route' => $route->id, 'document' => $doc->id]) }}" method="POST" onsubmit="return confirm('Bu evrağı silmek istediğinize emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg p-2 text-slate-400 hover:bg-rose-100 hover:text-rose-600 transition-colors" title="Sil">
                                    🗑️
                                </button>
                            </form>
                        </div>
                        <h4 class="font-bold text-slate-800 line-clamp-2" title="{{ $doc->document_name }}">{{ $doc->document_name }}</h4>
                        @if($doc->document_type)
                            <div class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $doc->document_type }}</div>
                        @endif
                        
                        @if($doc->end_date)
                            <div class="mt-3 flex items-center gap-2 text-sm">
                                <span class="text-slate-400">Bitiş:</span>
                                <span class="{{ $doc->end_date->isPast() ? 'font-bold text-rose-600' : 'text-slate-700' }}">
                                    {{ $doc->end_date->format('d.m.Y') }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-5 pt-4 border-t border-slate-200/60">
                        @if($doc->file_path)
                            <a href="{{ url('storage/' . $doc->file_path) }}" target="_blank" class="flex w-full items-center justify-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all">
                                <span>👁️</span> Görüntüle / İndir
                            </a>
                        @else
                            <div class="text-center text-sm italic text-slate-400">Dosya bulunamadı</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-slate-300 py-16 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-3xl shadow-sm">📭</div>
            <h4 class="mt-4 text-lg font-bold text-slate-800">Henüz Evrak Yüklemediniz</h4>
            <p class="mt-1 text-sm text-slate-500">Bu güzergah için sisteme yüklenmiş bir evrak bulunmuyor. Sağ üstteki "Yeni Evrak Yükle" butonunu kullanabilirsiniz.</p>
        </div>
    @endif
</div>

<!-- Yeni Evrak Yükle Modal -->
<div x-data="{ show: false }"
     x-show="show"
     x-on:open-modal.window="if ($event.detail === 'upload-document-modal') show = true"
     x-on:keydown.escape.window="show = false"
     class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0"
     style="display: none;">
    
    <div x-show="show" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="show = false"></div>

    <div x-show="show" 
         x-transition.duration.300ms
         class="relative mx-auto w-full max-w-lg transform overflow-visible rounded-3xl bg-white shadow-2xl ring-1 ring-slate-200 transition-all">
        
        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-between rounded-t-3xl">
            <h3 class="text-lg font-bold text-slate-800">Yeni Evrak Yükle</h3>
            <button @click="show = false" class="text-slate-400 hover:text-slate-600">
                ✖️
            </button>
        </div>

        <form action="{{ route('portal.jv.documents.store', ['token' => $jv->access_token, 'route' => $route->id]) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            
            <div class="space-y-4">
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Evrak Adı (örn: Araç Ruhsatı, SRC vs.) <span class="text-rose-500">*</span></label>
                    <input type="text" name="document_name" required class="block w-full rounded-xl border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Evrak Türü <span class="font-normal text-slate-400">(Opsiyonel)</span></label>
                    <input type="text" name="document_type" class="block w-full rounded-xl border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Geçerlilik Bitiş Tarihi <span class="font-normal text-slate-400">(Opsiyonel)</span></label>
                    <input type="date" name="end_date" class="block w-full rounded-xl border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                </div>

                <div class="rounded-xl border border-dashed border-slate-300 p-4 bg-slate-50">
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Bilgisayardan Dosya Seç (Max 20MB) <span class="text-rose-500">*</span></label>
                    <input type="file" name="file" required class="mt-2 block w-full text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-indigo-500 file:cursor-pointer file:transition-colors">
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                <button type="button" @click="show = false" class="rounded-xl bg-white px-5 py-2 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50">
                    İptal
                </button>
                <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-2 text-sm font-bold text-white shadow-sm hover:bg-indigo-500">
                    Yükle ve Gönder
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
