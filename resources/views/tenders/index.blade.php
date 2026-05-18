@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 mesh-gradient-light min-h-screen">
    <!-- HEADER -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white shadow-xl shadow-indigo-500/20 ring-1 ring-black/5">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/File%20Folder.png" alt="Tenders" class="h-10 w-10 drop-shadow-md animate-bounce-slow" />
            </div>
            <div>
                <h1 class="text-3xl font-black tracking-tight text-slate-900">İhale Dosyaları</h1>
                <p class="text-sm font-medium text-slate-500 mt-1 uppercase tracking-widest"><span class="text-green-500">●</span> KURUMSAL İHALE ARŞİVİ</p>
            </div>
        </div>

        @if(auth()->user()->hasPermission('tenders.create'))
            <a href="{{ route('tenders.create') }}" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-blue-500/40 active:scale-95">
                <div class="absolute inset-0 bg-white/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Briefcase.png" alt="Add" class="h-5 w-5 drop-shadow-sm" />
                <span>Yeni Kurum Ekle</span>
            </a>
        @endif
    </div>

    <!-- FILTER -->
    <div class="mb-8 rounded-3xl bg-white p-4 shadow-lg shadow-slate-200/40 ring-1 ring-slate-100">
        <form action="{{ route('tenders.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-slate-400">Kurum Ara</label>
                <div class="group relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                        <i class="fi fi-rr-search text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Örn: Devlet Su İşleri..."
                        class="block w-full rounded-2xl border-slate-200 bg-slate-50/50 py-2.5 pl-11 pr-4 text-sm font-medium text-slate-700 shadow-sm transition-all focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                </div>
            </div>
            <div class="sm:self-end">
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-slate-800 hover:shadow-md active:scale-95">
                    Ara
                </button>
            </div>
        </form>
    </div>

    <!-- TABLE -->
    <div class="rounded-3xl bg-white p-2 shadow-xl shadow-slate-200/40 ring-1 ring-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50/50 text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-black uppercase tracking-wider text-[11px] rounded-tl-2xl">Kurum Adı</th>
                        <th class="px-6 py-4 font-black uppercase tracking-wider text-[11px]">Araç İhtiyacı</th>
                        <th class="px-6 py-4 font-black uppercase tracking-wider text-[11px] text-center">Kayıtlı İhale Sayısı</th>
                        <th class="px-6 py-4 font-black uppercase tracking-wider text-[11px] text-right rounded-tr-2xl">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/80 font-medium text-slate-700">
                    @forelse($tenders as $tender)
                        <tr class="group hover:bg-slate-50/50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 ring-1 ring-blue-100">
                                        <i class="fi fi-rr-building text-lg"></i>
                                    </div>
                                    <span class="font-bold text-slate-900 text-base">{{ $tender->institution_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-500 truncate max-w-xs">{{ $tender->vehicle_details ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                                    {{ $tender->records_count }} Dosya
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('tenders.show', $tender->id) }}" class="group/btn flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition-all hover:bg-blue-100 hover:scale-110" title="Detay / Kayıtlar">
                                        <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Body%20parts/Eyes.png" alt="Detail" class="h-5 w-5 drop-shadow-sm transition-transform group-hover/btn:scale-110" />
                                    </a>
                                    
                                    @if(auth()->user()->hasPermission('tenders.edit'))
                                        <a href="{{ route('tenders.edit', $tender->id) }}" class="group/btn flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition-all hover:bg-amber-100 hover:scale-110" title="Düzenle">
                                            <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Pencil.png" alt="Edit" class="h-5 w-5 drop-shadow-sm transition-transform group-hover/btn:scale-110" />
                                        </a>
                                    @endif

                                    @if(auth()->user()->hasPermission('tenders.delete'))
                                        <form action="{{ route('tenders.destroy', $tender->id) }}" method="POST" onsubmit="return confirm('Bu ihale dosyasını ve içindeki tüm geçmiş ihale kayıtlarını silmek istediğinize emin misiniz?');" class="inline-block">
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
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Magnifying%20Glass%20Tilted%20Right.png" alt="Empty" class="h-16 w-16 opacity-80" />
                                    <p class="mt-4 text-sm font-bold text-slate-500">Kayıtlı Kurum Bulunamadı</p>
                                    <p class="mt-1 text-xs text-slate-400">Henüz sisteme eklenmiş bir ihale kurumu bulunmuyor.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tenders->hasPages())
            <div class="border-t border-slate-100 p-4">
                {{ $tenders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
