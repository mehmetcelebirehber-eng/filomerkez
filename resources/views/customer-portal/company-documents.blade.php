@extends('layouts.customer-portal')

@section('content')

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <a href="{{ route('customer.portal.dashboard', ['tab' => 'documents']) }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all">
        <span>⬅️</span> Portala Dön
    </a>
</div>

<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/40">
    <div class="mb-8 flex items-center gap-4 border-b border-slate-100 pb-6">
        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-100 text-3xl shadow-inner text-blue-600">
            🏢
        </div>
        <div>
            <h3 class="text-2xl font-black text-slate-800">{{ $customer->company->name ?? 'Firma' }} - Firma Evrakları</h3>
            <p class="text-sm text-slate-500 mt-1">Hizmet sağlayıcı firmanıza ait genel yasal belgeleri (Vergi Levhası, Faaliyet Belgesi vb.) buradan görüntüleyebilirsiniz.</p>
        </div>
    </div>

    @if($documents->count() > 0)
        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach($documents as $doc)
                <div class="group flex flex-col justify-between overflow-hidden rounded-2xl border border-slate-200 bg-slate-50/50 p-5 transition-all hover:border-blue-300 hover:bg-blue-50/30 hover:shadow-md">
                    <div>
                        <div class="mb-3 flex items-start justify-between gap-2">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-2xl text-blue-600">
                                📂
                            </div>
                        </div>
                        <h4 class="font-bold text-slate-800 line-clamp-2" title="{{ $doc->document_name }}">{{ $doc->document_name }}</h4>
                        @if($doc->document_type)
                            <div class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $doc->document_type }}</div>
                        @endif
                        
                        @if($doc->end_date)
                            <div class="mt-3 flex items-center gap-2 text-sm">
                                <span class="text-slate-400">Geçerlilik Bitiş:</span>
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
            <h4 class="mt-4 text-lg font-bold text-slate-800">Sistemde Evrak Bulunamadı</h4>
            <p class="mt-1 text-sm text-slate-500">Firmanıza ait sisteme yüklenmiş bir genel evrak bulunmuyor.</p>
        </div>
    @endif
</div>

@endsection
