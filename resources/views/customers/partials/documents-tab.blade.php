<style>
    @keyframes shimmer {
        100% { transform: translateX(100%); }
    }
</style>
<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-xl font-black tracking-tight text-slate-800">
                Araç ve Şoför Evrakları
            </h3>
            <p class="mt-1 text-sm text-slate-500">
                Tanımlı servis güzergahlarındaki araç ve şoförlere ait yasal belgeleri yönetin.
            </p>
        </div>
    </div>

    @if($customer->serviceRoutes && $customer->serviceRoutes->count() > 0)
        <div class="space-y-4">
            @foreach($customer->serviceRoutes as $route)
                <div class="group relative flex flex-col md:flex-row md:items-center justify-between gap-4 overflow-hidden rounded-[24px] border border-slate-200/60 bg-white p-5 shadow-lg shadow-slate-200/40 transition-all hover:shadow-xl hover:border-indigo-200 hover:ring-4 hover:ring-indigo-50/50">
                    <div class="absolute -left-10 -top-10 h-32 w-32 rounded-full bg-gradient-to-br from-indigo-50 to-blue-50 blur-2xl transition-all group-hover:bg-indigo-100/50"></div>
                    
                    <div class="relative flex items-center gap-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-[18px] bg-gradient-to-br from-indigo-500 via-blue-500 to-cyan-500 text-2xl text-white shadow-md shadow-indigo-500/20">
                            🚐
                        </div>
                        <div>
                            <div class="text-xs font-bold uppercase tracking-widest text-indigo-400 mb-1">
                                SERVİS GÜZERGAH ADI
                            </div>
                            <h4 class="text-lg font-black text-slate-800">
                                {{ $route->route_name }}
                            </h4>
                        </div>
                    </div>

                    <div class="relative flex flex-wrap items-center gap-3">
                        <button type="button" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-amber-500/30 transition-all hover:-translate-y-0.5 hover:shadow-xl active:scale-95 group/btn overflow-hidden">
                            <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/40 to-transparent group-hover/btn:animate-[shimmer_1.5s_infinite]"></div>
                            <span class="text-lg drop-shadow-md">📄</span>
                            <span>Araç Evrakları</span>
                        </button>
                        
                        <button type="button" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-500/30 transition-all hover:-translate-y-0.5 hover:shadow-xl active:scale-95 group/btn overflow-hidden">
                            <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/40 to-transparent group-hover/btn:animate-[shimmer_1.5s_infinite]"></div>
                            <span class="text-lg drop-shadow-md">🪪</span>
                            <span>Şoför Evrakları</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-[32px] border border-dashed border-slate-300 bg-slate-50 py-16 text-center">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white text-4xl shadow-sm ring-1 ring-slate-200">
                📭
            </div>
            <h3 class="mt-4 text-lg font-bold text-slate-900">Güzergah Bulunamadı</h3>
            <p class="mt-2 text-sm text-slate-500">Bu müşteriye henüz bir servis güzergahı tanımlanmamış.</p>
            <div class="mt-6">
                <a href="{{ route('customers.show', [$customer, 'tab' => 'services']) }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition">
                    <span>🛣️</span>
                    Servisler Sekmesine Git
                </a>
            </div>
        </div>
    @endif
</div>
