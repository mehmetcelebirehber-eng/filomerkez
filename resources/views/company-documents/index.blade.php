@extends('layouts.app')

@section('title', 'Şirket Evrakları')
@section('subtitle', 'Kurumsal belgelerinizi güvenle saklayın ve yönetin')

@section('content')

@php
    $totalDocs = $documents->total();
    $vergiLevhasi = $documents->where('document_type', 'Vergi Levhası')->count();
    $sicilGazetesi = $documents->where('document_type', 'Sicil Gazetesi')->count();
    $imzaSirkusu = $documents->where('document_type', 'İmza Sirküsü')->count();
    $faaliyetBelgesi = $documents->where('document_type', 'Faaliyet Belgesi')->count();
    $digerBelgeler = $totalDocs - ($vergiLevhasi + $sicilGazetesi + $imzaSirkusu + $faaliyetBelgesi);
@endphp

<div x-data="documentManager()" class="space-y-6">

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 shadow-sm flex items-center gap-3">
            <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Symbols/Check%20Mark%20Button.png" class="w-6 h-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700 shadow-sm flex items-center gap-3">
            <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Symbols/Cross%20Mark.png" class="w-6 h-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
        <div>
            <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Şirket Evrakları</h2>
            <p class="mt-2 text-sm font-medium text-slate-500">
                Şirketinize ait Vergi Levhası, Sicil Gazetesi gibi önemli tüm belgeleri tek merkezden yönetin.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button x-show="selectedIds.length > 0" @click="downloadSelected"
               class="inline-flex items-center gap-2 rounded-2xl bg-slate-800 px-5 py-3 text-sm font-semibold text-white shadow-lg hover:scale-[1.02] transition">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Inbox%20Tray.png" class="w-5 h-5">
                <span x-text="`Seçilenleri İndir (${selectedIds.length})`"></span>
            </button>

            <button x-show="selectedIds.length > 0" @click="deleteSelected"
               class="inline-flex items-center gap-2 rounded-2xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white shadow-lg hover:scale-[1.02] transition">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Wastebasket.png" class="w-5 h-5">
                <span x-text="`Seçilenleri Sil (${selectedIds.length})`"></span>
            </button>

            @if(auth()->user()->hasPermission('company_documents.create'))
            <button @click="showUploadModal = true"
               class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 hover:scale-[1.02] transition">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Outbox%20Tray.png" class="w-5 h-5">
                <span>Yeni Evrak Yükle</span>
            </button>
            @endif
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
        <div class="group relative overflow-hidden rounded-[32px] bg-white p-6 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:-translate-y-1">
            <div class="absolute -right-2 -bottom-2 opacity-100 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-700 drop-shadow-2xl z-0">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/File%20Cabinet.png" alt="Toplam" class="w-24 h-24 drop-shadow-2xl" />
            </div>
            <div class="relative flex flex-col justify-between h-full z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-3 py-1.5 rounded-xl">Toplam Evrak</span>
                </div>
                <div class="mt-6">
                    <div class="text-3xl font-black text-slate-900">{{ $totalDocs }}</div>
                    <div class="mt-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Arşivdeki Belgeler</div>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-[32px] bg-white p-6 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:-translate-y-1">
            <div class="absolute -right-2 -bottom-2 opacity-100 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-700 drop-shadow-2xl z-0">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Chart%20Increasing%20with%20Yen.png" alt="Vergi" class="w-24 h-24 drop-shadow-2xl" />
            </div>
            <div class="relative flex flex-col justify-between h-full z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest bg-emerald-50 px-3 py-1.5 rounded-xl">Vergi Levhası</span>
                </div>
                <div class="mt-6">
                    <div class="text-3xl font-black text-slate-900">{{ $vergiLevhasi }}</div>
                    <div class="mt-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kayıtlı Belge</div>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-[32px] bg-white p-6 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:-translate-y-1">
            <div class="absolute -right-2 -bottom-2 opacity-100 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-700 drop-shadow-2xl z-0">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Rolled-Up%20Newspaper.png" alt="Sicil" class="w-24 h-24 drop-shadow-2xl" />
            </div>
            <div class="relative flex flex-col justify-between h-full z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest bg-amber-50 px-3 py-1.5 rounded-xl">Sicil Gazetesi</span>
                </div>
                <div class="mt-6">
                    <div class="text-3xl font-black text-slate-900">{{ $sicilGazetesi }}</div>
                    <div class="mt-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kayıtlı Belge</div>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-[32px] bg-white p-6 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:-translate-y-1">
            <div class="absolute -right-2 -bottom-2 opacity-100 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-700 drop-shadow-2xl z-0">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Fountain%20Pen.png" alt="İmza" class="w-24 h-24 drop-shadow-2xl" />
            </div>
            <div class="relative flex flex-col justify-between h-full z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest bg-blue-50 px-3 py-1.5 rounded-xl">İmza Sirküsü</span>
                </div>
                <div class="mt-6">
                    <div class="text-3xl font-black text-slate-900">{{ $imzaSirkusu }}</div>
                    <div class="mt-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kayıtlı Belge</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="rounded-[30px] border border-slate-200/60 bg-white/90 p-5 shadow-xl backdrop-blur">
        <form method="GET" action="{{ route('company-documents.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="md:col-span-2">
                <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">Belge Ara</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Evrak adı, türü vb. ile arayın..."
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 shadow-sm outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 shadow-lg">
                    Ara / Filtrele
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-[30px] border border-slate-200/60 bg-white/90 shadow-xl backdrop-blur">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Şirket Arşivi</h3>
                <p class="mt-1 text-sm text-slate-500">Tüm şirket belgeleriniz güvende.</p>
            </div>
            <div class="text-sm font-medium text-slate-400">Toplam {{ $totalDocs }} kayıt</div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-[1000px] w-full">
                <thead class="border-b border-slate-100 bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" @change="toggleAll" x-model="allSelected" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Evrak Adı</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Tür</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Tarih</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-[0.14em] text-slate-500">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($documents as $doc)
                        <tr class="transition duration-200 hover:bg-indigo-50/40">
                            <td class="px-6 py-5">
                                <input type="checkbox" value="{{ $doc->id }}" x-model="selectedIds" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white border border-slate-100 text-lg shadow-sm">
                                        @if(str_contains(strtolower($doc->file_path), '.pdf'))
                                            <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Page%20Facing%20Up.png" class="w-8 h-8">
                                        @else
                                            <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Framed%20Picture.png" class="w-8 h-8">
                                        @endif
                                    </div>
                                    <div class="text-sm font-extrabold tracking-wide text-slate-900">
                                        {{ $doc->document_name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center rounded-lg bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                    {{ $doc->document_type ?: 'Diğer' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-sm font-semibold text-slate-700">
                                {{ $doc->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ url('storage/' . $doc->file_path) }}" target="_blank"
                                       class="rounded-xl bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-100">
                                        Görüntüle/İndir
                                    </a>
                                    
                                    @if(auth()->user()->hasPermission('company_documents.delete'))
                                    <form action="{{ route('company-documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Silmek istediğine emin misin?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-xl bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 transition hover:bg-rose-100">
                                            Sil
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-14 text-center">
                                <div class="mx-auto max-w-md">
                                    <div class="mb-6 flex justify-center">
                                        <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Open%20File%20Folder.png" class="w-24 h-24 drop-shadow-xl" />
                                    </div>
                                    <div class="text-base font-semibold text-slate-700">Henüz evrak yüklenmemiş.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $documents->links() }}
        </div>
    </div>

    <!-- Upload Modal -->
    <div x-show="showUploadModal" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="showUploadModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="showUploadModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="relative transform overflow-hidden rounded-[32px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
                    
                    <form action="{{ route('company-documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-8">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-2xl bg-indigo-50 sm:mx-0 sm:h-16 sm:w-16">
                                    <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Outbox%20Tray.png" class="w-10 h-10">
                                </div>
                                <div class="mt-3 text-center sm:ml-6 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-xl font-black leading-6 text-slate-900" id="modal-title">Evrak Yükle</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-slate-500">Sisteme yeni bir şirket evrağı ekleyin. (PDF, JPG, PNG)</p>
                                    </div>
                                    
                                    <div class="mt-6 space-y-5">
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-1">Evrak Adı</label>
                                            <input type="text" name="document_name" required placeholder="Örn: 2026 Vergi Levhası"
                                                   class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                                            <p class="text-[10px] text-slate-400 mt-1">İçinde "Vergi", "Sicil", "İmza" gibi kelimeler geçerse tür otomatik algılanır.</p>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-1">Dosya</label>
                                            <input type="file" name="file" required accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx"
                                                   class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 bg-slate-50">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-5 sm:flex sm:flex-row-reverse sm:px-8 border-t border-slate-100">
                            <button type="submit" class="inline-flex w-full justify-center rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-lg hover:bg-indigo-500 sm:ml-3 sm:w-auto transition">
                                Evrağı Yükle
                            </button>
                            <button type="button" @click="showUploadModal = false" class="mt-3 inline-flex w-full justify-center rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition">
                                İptal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for bulk delete -->
    <form id="bulkDeleteForm" action="{{ route('company-documents.bulk-delete') }}" method="POST" class="hidden">
        @csrf
    </form>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('documentManager', () => ({
            showUploadModal: false,
            selectedIds: [],
            allSelected: false,
            documentIds: @json($documents->pluck('id')),
            
            toggleAll() {
                if (this.allSelected) {
                    this.selectedIds = [...this.documentIds];
                } else {
                    this.selectedIds = [];
                }
            },
            
            deleteSelected() {
                if (confirm(this.selectedIds.length + ' adet evrağı silmek istediğinize emin misiniz?')) {
                    const form = document.getElementById('bulkDeleteForm');
                    this.selectedIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });
                    form.submit();
                }
            },
            
            downloadSelected() {
                // Build a query string of IDs for the GET request
                let url = '{{ route('company-documents.zip') }}?';
                this.selectedIds.forEach(id => {
                    url += 'ids[]=' + id + '&';
                });
                window.location.href = url;
            }
        }));
    });
</script>

@endsection
