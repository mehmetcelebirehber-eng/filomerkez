@extends('layouts.app')

@php
    $isVehicle = $type === 'vehicle';
    $title = $isVehicle ? 'Araç Evrakları' : 'Şoför Evrakları';
    $subtitle = $route->route_name . ' - ' . $title;
    $icon = $isVehicle ? '🚐' : '👨‍✈️';
@endphp

@section('title', $title)
@section('subtitle', $subtitle)

@section('content')

@if(session('success'))
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 shadow-sm mb-6">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 shadow-sm mb-6 flex flex-col gap-2">
        <div class="flex items-center gap-3 font-semibold">
            <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Symbols/Cross%20Mark.png" class="w-6 h-6">
            Lütfen aşağıdaki hataları düzeltin:
        </div>
        <ul class="list-disc space-y-1 pl-10">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <a href="{{ route('customers.show', [$customer, 'tab' => 'documents']) }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all">
        <span>⬅️</span> Geri Dön
    </a>

    <div class="flex gap-3">
        <!-- Aktar Butonu -->
        <button type="button" x-data @click="$dispatch('open-modal', 'import-document-modal')" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-blue-500/20 hover:shadow-lg transition-all active:scale-95">
            <span>🔄</span> Sistemden Evrak Seç
        </button>

        <!-- Yeni Evrak Yükle Butonu -->
        <button type="button" x-data @click="$dispatch('open-modal', 'upload-document-modal')" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-500/20 hover:shadow-lg transition-all active:scale-95">
            <span>📤</span> Yeni Evrak Yükle
        </button>
    </div>
</div>

<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/40">
    <div class="mb-6 flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-2xl shadow-sm">
            {{ $icon }}
        </div>
        <div>
            <h3 class="text-xl font-bold text-slate-800">{{ $route->route_name }} - {{ $title }}</h3>
            <p class="text-sm text-slate-500 mt-1">Bu güzergaha ait {{ strtolower($title) }}ni buradan yönetebilirsiniz.</p>
        </div>
    </div>

    @if($documents->count() > 0)
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($documents as $doc)
                <div class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-slate-200 bg-slate-50/50 p-5 transition-all hover:border-blue-300 hover:bg-blue-50/30 hover:shadow-md">
                    <div>
                        <div class="mb-3 flex items-start justify-between gap-2">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-2xl text-blue-600">
                                📄
                            </div>
                            <form action="{{ route('customers.service-routes.documents.destroy', [$customer, $route, $doc]) }}" method="POST" onsubmit="return confirm('Bu evrağı silmek istediğinize emin misiniz? (Fiziksel dosya da silinecektir)')">
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
                            <a href="{{ url('storage/' . $doc->file_path) }}" target="_blank" class="flex w-full items-center justify-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all">
                                <span>👁️</span> Görüntüle / İndir
                            </a>
                        @else
                            <div class="text-center text-sm italic text-slate-400">Dosya yüklenmemiş</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-slate-300 py-12 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-3xl">📭</div>
            <h4 class="mt-4 text-lg font-bold text-slate-800">Kayıtlı Evrak Yok</h4>
            <p class="mt-1 text-sm text-slate-500">Bu güzergaha henüz {{ strtolower($title) }} tanımlanmamış.</p>
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

        <form action="{{ route('customers.service-routes.documents.store', ['customer' => $customer->id, 'route' => $route->id, 'type' => $type]) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Evrak Adı (örn: Ruhsat, SRC vs.)</label>
                    <input type="text" name="document_name" required class="block w-full rounded-xl border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Evrak Türü <span class="font-normal text-slate-400">(Opsiyonel)</span></label>
                    <input type="text" name="document_type" class="block w-full rounded-xl border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Geçerlilik Bitiş Tarihi <span class="font-normal text-slate-400">(Opsiyonel)</span></label>
                    <input type="date" name="end_date" class="block w-full rounded-xl border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Dosya Seç (Max 20MB)</label>
                    <input type="file" name="file" required class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3">
                <button type="button" @click="show = false" class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50">
                    İptal
                </button>
                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                    Yükle ve Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Sistemden Aktar Modal -->
<div x-data="{ show: false }"
     x-show="show"
     x-on:open-modal.window="if ($event.detail === 'import-document-modal') show = true"
     x-on:keydown.escape.window="show = false"
     class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0"
     style="display: none;">
    
    <div x-show="show" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="show = false"></div>

    <div x-show="show" 
         x-transition.duration.300ms
         class="relative mx-auto w-full max-w-lg transform overflow-visible rounded-3xl bg-white shadow-2xl ring-1 ring-slate-200 transition-all">
        
        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-between rounded-t-3xl">
            <h3 class="text-lg font-bold text-slate-800">Sistemden {{ $title }} Aktar</h3>
            <button @click="show = false" class="text-slate-400 hover:text-slate-600">
                ✖️
            </button>
        </div>

        <form action="{{ route('customers.service-routes.documents.import', ['customer' => $customer->id, 'route' => $route->id, 'type' => $type]) }}" method="POST" class="p-6">
            @csrf
            
            @if($sourceDocuments->count() > 0)
                <div class="space-y-4">
                    <p class="text-sm text-slate-500">Sistemdeki kayıtlı {{ strtolower($title) }} listesinden dilediğiniz belgeyi bu güzergah dosyasına kopyalayabilirsiniz.</p>
                    
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Evrak Seçin</label>
                        
                        <div class="relative" x-data="{
                            search: '',
                            open: false,
                            selectedId: '',
                            selectedLabel: '-- Evrak Arayın veya Seçin --',
                            options: [
                                @foreach($sourceDocuments as $cDoc)
                                    @php
                                        if (!$cDoc->documentable) { continue; }
                                        $prefix = $isVehicle ? ($cDoc->documentable->plate ?? 'Bilinmeyen Araç') : ($cDoc->documentable->first_name . ' ' . $cDoc->documentable->last_name);
                                        $labelText = '[' . $prefix . '] - ' . $cDoc->document_name . ($cDoc->end_date ? ' (Bitiş: '.$cDoc->end_date->format('d.m.Y').')' : '');
                                    @endphp
                                    { id: '{{ $cDoc->id }}', label: '{{ addslashes($labelText) }}' },
                                @endforeach
                            ],
                            get filteredOptions() {
                                if (this.search.trim() === '') return this.options;
                                return this.options.filter(opt => opt.label.toLowerCase().includes(this.search.toLowerCase()));
                            },
                            selectOption(opt) {
                                this.selectedId = opt.id;
                                this.selectedLabel = opt.label;
                                this.open = false;
                                this.search = '';
                            }
                        }">
                            <!-- Form için gizli input -->
                            <input type="hidden" name="document_id" x-model="selectedId" required>

                            <!-- Custom Select Butonu -->
                            <button type="button" @click="open = !open" 
                                class="flex w-full items-center justify-between rounded-xl border border-slate-300 bg-white py-2.5 px-4 text-left text-sm text-slate-900 shadow-sm hover:bg-slate-50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-600/20">
                                <span x-text="selectedLabel" class="block truncate font-medium"></span>
                                <span class="pointer-events-none ml-2 flex items-center text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </span>
                            </button>

                            <!-- Açılır Menü -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute z-50 mt-1 w-full rounded-xl bg-white shadow-2xl ring-1 ring-black ring-opacity-5"
                                 style="display: none;">
                                
                                <div class="p-2 border-b border-slate-100 sticky top-0 bg-white rounded-t-xl z-10">
                                    <div class="relative">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        </div>
                                        <input type="text" x-model="search" placeholder="Plaka, isim veya evrak ara..." 
                                            class="block w-full rounded-lg border-0 py-2 pl-9 pr-3 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                    </div>
                                </div>
                                
                                <ul class="max-h-60 overflow-auto py-1 text-sm text-slate-700">
                                    <template x-for="option in filteredOptions" :key="option.id">
                                        <li @click="selectOption(option)" 
                                            class="relative cursor-pointer select-none py-2.5 pl-4 pr-9 hover:bg-indigo-50 hover:text-indigo-900 transition-colors"
                                            :class="{'bg-indigo-50 text-indigo-900 font-bold': selectedId === option.id}">
                                            <span x-text="option.label" class="block truncate"></span>
                                            
                                            <span x-show="selectedId === option.id" class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </span>
                                        </li>
                                    </template>
                                    <li x-show="filteredOptions.length === 0" class="relative cursor-default select-none py-3 px-4 text-slate-500 text-center italic">
                                        Aramanızla eşleşen sonuç bulunamadı.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-3">
                    <button type="button" @click="show = false" class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50">
                        İptal
                    </button>
                    <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                        Seçili Evrağı Kopyala
                    </button>
                </div>
            @else
                <div class="text-center py-6">
                    <div class="text-4xl mb-3">📁</div>
                    <p class="text-slate-500 font-medium">Sistemde kayıtlı genel bir {{ strtolower($title) }} bulunmuyor.</p>
                    <p class="text-sm text-slate-400 mt-2">Araç/Şoför modülüne gidip evrak eklemelisiniz veya bu ekrandan doğrudan yeni yükleme yapmalısınız.</p>
                    
                    <div class="mt-6">
                        <button type="button" @click="show = false" class="rounded-xl bg-slate-100 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">
                            Kapat
                        </button>
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>

@endsection
