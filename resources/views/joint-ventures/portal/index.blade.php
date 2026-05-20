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

@endsection
