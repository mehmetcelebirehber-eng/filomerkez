@extends('layouts.customer-portal')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-black text-slate-800 flex items-center gap-2">
                <span>📍</span>
                <span>{{ $route->route_name }} - Durak Listesi</span>
            </h2>
            <p class="text-sm text-slate-500 mt-1">Bu güzergaha ait sıralı durak ve saat bilgileri</p>
        </div>
        <a href="{{ route('customer.portal.dashboard', ['tab' => 'services']) }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-bold text-slate-600 shadow-sm hover:bg-slate-200 transition-all active:scale-95">
            <span>⬅️</span> Geri Dön
        </a>
    </div>

    <div class="rounded-3xl bg-white p-6 shadow-xl shadow-slate-200/40 ring-1 ring-slate-100">
        @if($stops->count() > 0)
            <div class="space-y-3">
                @foreach($stops as $index => $stop)
                    <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl ring-1 ring-slate-200 group transition-all hover:ring-indigo-200 hover:shadow-md hover:bg-white">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-blue-500 text-white font-black shadow-sm text-lg">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Durak Adı</div>
                            <div class="text-base font-bold text-slate-800">{{ $stop->stop_name }}</div>
                        </div>
                        <div class="w-48 shrink-0 text-right pr-4">
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Planlanan Saat</div>
                            <div class="inline-flex items-center gap-2 text-base font-bold text-slate-800 bg-slate-100 px-3 py-1.5 rounded-lg">
                                <span>🕒</span>
                                <span>{{ $stop->stop_time ? \Carbon\Carbon::parse($stop->stop_time)->format('H:i') : 'Belirtilmedi' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-16 text-center">
                <div class="mx-auto max-w-md">
                    <div class="mb-4 text-6xl">🚏</div>
                    <div class="text-xl font-bold text-slate-800">Henüz Durak Eklenmemiş</div>
                    <div class="mt-2 text-sm text-slate-500">
                        Bu güzergaha ait tanımlanmış herhangi bir durak bilgisi bulunmuyor.
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
