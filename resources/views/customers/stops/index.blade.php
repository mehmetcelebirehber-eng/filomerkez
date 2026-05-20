@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 mesh-gradient-light min-h-screen">
    
    <!-- HEADER -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white shadow-xl shadow-blue-500/20 ring-1 ring-black/5">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Travel%20and%20places/Bus%20Stop.png" alt="Stop" class="h-8 w-8 drop-shadow-md animate-bounce-slow" />
            </div>
            <div>
                <h1 class="text-2xl font-black tracking-tight text-slate-900">{{ $route->route_name }}</h1>
                <p class="text-sm font-medium text-slate-500 mt-1">{{ $customer->company_name }} - Güzergah Durakları</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('customers.show', ['customer' => $customer->id, 'tab' => 'services']) }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-300 hover:bg-slate-50 hover:shadow-md hover:ring-slate-300 active:scale-95">
                <i class="fi fi-rr-arrow-small-left text-lg"></i>
                Müşteriye Dön
            </a>
            <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-50 px-5 py-2.5 text-sm font-bold text-emerald-700 shadow-sm ring-1 ring-emerald-200 transition-all duration-300 hover:bg-emerald-100 hover:shadow-md active:scale-95">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Page%20with%20Curl.png" alt="Excel" class="h-5 w-5" />
                Excel'den Aktar
            </button>
        </div>
    </div>

    <!-- MAIN FORM -->
    <form action="{{ route('customers.service-routes.stops.store', [$customer->id, $route->id]) }}" method="POST">
        @csrf
        
        <div class="rounded-3xl bg-white p-6 shadow-xl shadow-slate-200/40 ring-1 ring-slate-100 mb-6">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-lg font-black text-slate-800">Durak Listesi</h2>
                    <p class="text-sm text-slate-500">İlk duraktan son durağa kadar sıralı şekilde ekleyin.</p>
                </div>
                <button type="button" onclick="addStopRow()" class="px-4 py-2 bg-slate-100 rounded-xl text-sm font-black text-blue-600 shadow-sm hover:bg-slate-200 transition-all active:scale-95">
                    + Yeni Durak Ekle
                </button>
            </div>

            <div id="stopsContainer" class="space-y-3">
                @if($stops->count() > 0)
                    @foreach($stops as $index => $stop)
                        <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-2xl ring-1 ring-slate-200 group stop-row">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-slate-400 font-black shadow-sm ring-1 ring-slate-100 stop-number">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <input type="text" name="stops[{{ $index }}][stop_name]" value="{{ $stop->stop_name }}" placeholder="Durak Adı (Örn: Bosna Doğan Çanta)" required class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-4 text-sm font-bold shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                            </div>
                            <div class="w-32 shrink-0">
                                <input type="time" name="stops[{{ $index }}][stop_time]" value="{{ $stop->stop_time ? \Carbon\Carbon::parse($stop->stop_time)->format('H:i') : '' }}" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-4 text-sm font-bold shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                            </div>
                            <button type="button" onclick="this.closest('.stop-row').remove(); updateStopNumbers();" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-slate-200 transition-all duration-300 hover:bg-rose-50 hover:shadow-md hover:shadow-rose-500/20 hover:ring-rose-200 active:scale-95 group/btn">
                                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Wastebasket.png" alt="Sil" class="h-6 w-6 opacity-60 transition-all duration-300 group-hover/btn:opacity-100 group-hover/btn:scale-110 drop-shadow-sm" />
                            </button>
                        </div>
                    @endforeach
                @else
                    <!-- Initial Empty Row -->
                    <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-2xl ring-1 ring-slate-200 group stop-row">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-slate-400 font-black shadow-sm ring-1 ring-slate-100 stop-number">1</div>
                        <div class="flex-1">
                            <input type="text" name="stops[0][stop_name]" placeholder="Durak Adı (Örn: Bosna Doğan Çanta)" required class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-4 text-sm font-bold shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                        </div>
                        <div class="w-32 shrink-0">
                            <input type="time" name="stops[0][stop_time]" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-4 text-sm font-bold shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                        </div>
                        <button type="button" onclick="this.closest('.stop-row').remove(); updateStopNumbers();" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-slate-200 transition-all duration-300 hover:bg-rose-50 hover:shadow-md hover:shadow-rose-500/20 hover:ring-rose-200 active:scale-95 group/btn">
                            <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Wastebasket.png" alt="Sil" class="h-6 w-6 opacity-60 transition-all duration-300 group-hover/btn:opacity-100 group-hover/btn:scale-110 drop-shadow-sm" />
                        </button>
                    </div>
                @endif
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="px-8 py-3 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg shadow-blue-500/30 transition-all hover:scale-[1.02] hover:shadow-xl hover:shadow-blue-500/40 active:scale-95">
                    Durakları Kaydet
                </button>
            </div>
        </div>
    </form>
</div>

<!-- EXCEL IMPORT MODAL -->
<div id="importModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('importModal').classList.add('hidden')"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="w-full max-w-lg rounded-3xl bg-white shadow-2xl overflow-hidden flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 p-6 shrink-0 bg-slate-50/50">
                <h3 class="text-lg font-black text-slate-900 flex items-center gap-3">
                    <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Page%20with%20Curl.png" class="w-7 h-7 drop-shadow-sm"/>
                    Excel'den Toplu Aktar
                </h3>
                <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors h-8 w-8 flex items-center justify-center rounded-full hover:bg-slate-200">
                    <i class="fi fi-rr-cross"></i>
                </button>
            </div>
            <div class="p-6">
                <p class="text-sm text-slate-500 mb-6">Excel veya CSV dosyanızda ilk sütunda <strong>Durak Adı</strong>, ikinci sütunda <strong>Saat (SS:DD)</strong> formatında bilgiler olmalıdır. Sütun başlıkları eklemeyin.</p>
                
                <form action="{{ route('customers.service-routes.stops.import', [$customer->id, $route->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <label class="mb-2 block text-xs font-bold text-slate-700">Dosya Seçin (CSV, Excel)</label>
                        <input type="file" name="excel_file" accept=".csv,.xlsx,.xls" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 file:transition-colors file:cursor-pointer bg-white rounded-xl border border-slate-200 shadow-sm cursor-pointer">
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-white shadow-sm ring-1 ring-slate-200 rounded-xl hover:bg-slate-50 active:scale-95 transition-all">İptal</button>
                        <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-xl shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 active:scale-95 transition-all">Yükle ve Aktar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let stopIndex = {{ max($stops->count(), 1) }};
    
    function addStopRow() {
        const container = document.getElementById('stopsContainer');
        const row = document.createElement('div');
        row.className = 'flex items-center gap-3 bg-slate-50 p-3 rounded-2xl ring-1 ring-slate-200 group stop-row';
        row.innerHTML = `
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-slate-400 font-black shadow-sm ring-1 ring-slate-100 stop-number">#</div>
            <div class="flex-1">
                <input type="text" name="stops[${stopIndex}][stop_name]" placeholder="Durak Adı (Örn: Bosna Doğan Çanta)" required class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-4 text-sm font-bold shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
            </div>
            <div class="w-32 shrink-0">
                <input type="time" name="stops[${stopIndex}][stop_time]" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-4 text-sm font-bold shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
            </div>
            <button type="button" onclick="this.closest('.stop-row').remove(); updateStopNumbers();" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-slate-200 transition-all duration-300 hover:bg-rose-50 hover:shadow-md hover:shadow-rose-500/20 hover:ring-rose-200 active:scale-95 group/btn">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Wastebasket.png" alt="Sil" class="h-6 w-6 opacity-60 transition-all duration-300 group-hover/btn:opacity-100 group-hover/btn:scale-110 drop-shadow-sm" />
            </button>
        `;
        container.appendChild(row);
        stopIndex++;
        updateStopNumbers();
    }

    function updateStopNumbers() {
        const numbers = document.querySelectorAll('.stop-number');
        numbers.forEach((el, idx) => {
            el.textContent = idx + 1;
        });
    }
</script>
@endsection
