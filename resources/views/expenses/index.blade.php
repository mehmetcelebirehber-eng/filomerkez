@extends('layouts.app')

@section('title', 'Muhasebe / Giderler')
@section('subtitle', 'Finans Yönetimi')

@section('content')
<div class="relative w-full space-y-6" x-data="expenseManager()">
    
    <!-- Üst İstatistik Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-[30px] p-6 text-white shadow-xl shadow-indigo-500/20 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex items-start justify-between">
                <div>
                    <p class="text-indigo-100 font-bold uppercase tracking-wider text-xs mb-1">BU AYKİ TOPLAM GİDER</p>
                    <h3 class="text-3xl font-black">{{ number_format($totalThisMonth ?? 0, 2, ',', '.') }} ₺</h3>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white/70 backdrop-blur-xl rounded-[30px] p-6 border border-white shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-slate-400 font-bold uppercase tracking-wider text-xs mb-1">EN ÇOK MASRAF (BU AY)</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $topVehicle ? $topVehicle->plate : 'Veri Yok' }}</h3>
                </div>
                <div class="w-12 h-12 bg-rose-50 border border-rose-100 rounded-2xl flex items-center justify-center text-rose-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white/70 backdrop-blur-xl rounded-[30px] p-6 border border-white shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-slate-400 font-bold uppercase tracking-wider text-xs mb-1">GİDER DAĞILIMI (BU AY)</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center justify-center text-emerald-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                @if(isset($typeDistribution) && $typeDistribution->count() > 0)
                    @foreach($typeDistribution->take(2) as $type => $amount)
                        @php
                            $percentage = $totalThisMonth > 0 ? ($amount / $totalThisMonth) * 100 : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-1">
                                <span class="text-slate-600">{{ \App\Models\Expense::getTypes()[$type] ?? $type }}</span>
                                <span class="text-slate-900">%{{ round($percentage) }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5">
                                <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-sm text-slate-400 font-bold">Henüz gider girilmemiş.</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Filtreler ve Ekle Butonu -->
    <div class="bg-white/70 backdrop-blur-xl rounded-[30px] border border-white shadow-sm p-4 flex flex-col md:flex-row gap-4 justify-between items-center z-10 relative">
        <form action="{{ route('expenses.index') }}" method="GET" class="flex flex-wrap gap-3 w-full md:w-auto">
            <select name="vehicle_id" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 p-3 font-bold">
                <option value="">Tüm Araçlar</option>
                @foreach($vehicles as $vehicle)
                    <option value="{{ $vehicle->id }}" {{ request('vehicle_id') == $vehicle->id ? 'selected' : '' }}>{{ $vehicle->plate }}</option>
                @endforeach
            </select>

            <select name="type" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 p-3 font-bold">
                <option value="">Tüm Gider Türleri</option>
                @foreach(\App\Models\Expense::getTypes() as $key => $label)
                    <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            
            <input type="date" name="date_start" value="{{ request('date_start') }}" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 p-3 font-bold">
            <input type="date" name="date_end" value="{{ request('date_end') }}" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 p-3 font-bold">

            <button type="submit" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold py-3 px-6 rounded-2xl transition shadow-sm">
                Filtrele
            </button>
            <a href="{{ route('expenses.index') }}" class="bg-rose-50 border border-rose-100 hover:bg-rose-100 text-rose-600 font-bold py-3 px-4 rounded-2xl transition shadow-sm">
                Temizle
            </a>
        </form>

        <button @click="openCreateModal()" class="w-full md:w-auto bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-2xl shadow-lg shadow-indigo-500/30 transition-all active:scale-95 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Yeni Gider Ekle
        </button>
    </div>

    <!-- Gider Tablosu -->
    <div class="bg-white/70 backdrop-blur-xl rounded-[30px] border border-white shadow-sm overflow-hidden z-0 relative">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 text-xs uppercase tracking-wider font-bold">
                        <th class="p-5 border-b border-slate-100">Tarih</th>
                        <th class="p-5 border-b border-slate-100">Gider Türü</th>
                        <th class="p-5 border-b border-slate-100">Araç</th>
                        <th class="p-5 border-b border-slate-100">Açıklama</th>
                        <th class="p-5 border-b border-slate-100 text-right">Tutar</th>
                        <th class="p-5 border-b border-slate-100 text-center">İşlem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="p-5">
                                <div class="font-bold text-slate-800">{{ $expense->date->format('d.m.Y') }}</div>
                                <div class="text-[10px] text-slate-400 font-bold">{{ $expense->date->diffForHumans() }}</div>
                            </td>
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    @php
                                        $iconMap = [
                                            'insurance' => ['🛡️', 'bg-blue-100 text-blue-600'],
                                            'maintenance' => ['🔧', 'bg-orange-100 text-orange-600'],
                                            'tires' => ['🛞', 'bg-slate-200 text-slate-700'],
                                            'electric' => ['⚡', 'bg-yellow-100 text-yellow-600'],
                                            'bodywork' => ['🛠️', 'bg-cyan-100 text-cyan-600'],
                                            'fuel' => ['⛽', 'bg-emerald-100 text-emerald-600'],
                                            'tax' => ['📜', 'bg-rose-100 text-rose-600'],
                                            'other' => ['📦', 'bg-purple-100 text-purple-600'],
                                        ];
                                        $icon = $iconMap[$expense->type] ?? ['💰', 'bg-slate-100 text-slate-600'];
                                    @endphp
                                    <div class="w-10 h-10 rounded-xl {{ $icon[1] }} flex items-center justify-center text-lg shadow-sm">
                                        {{ $icon[0] }}
                                    </div>
                                    <span class="font-bold text-slate-700">{{ $expense->type_name }}</span>
                                </div>
                            </td>
                            <td class="p-5">
                                @if($expense->vehicle)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 font-bold text-sm border border-slate-200">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                        {{ $expense->vehicle->plate }}
                                    </span>
                                @else
                                    <span class="text-slate-400 font-bold text-sm">-</span>
                                @endif
                            </td>
                            <td class="p-5 max-w-xs">
                                <div class="text-sm font-medium text-slate-600 truncate" title="{{ $expense->description }}">
                                    {{ $expense->description ?: '-' }}
                                </div>
                            </td>
                            <td class="p-5 text-right">
                                <span class="text-lg font-black text-slate-800">{{ number_format($expense->amount, 2, ',', '.') }} ₺</span>
                            </td>
                            <td class="p-5 text-center">
                                <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button @click="openEditModal({{ $expense->toJson() }})" class="p-2 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-100 transition" title="Düzenle">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="inline" onsubmit="return confirm('Bu gider kaydını silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-100 transition" title="Sil">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center">
                                <div class="inline-flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-100 text-slate-400 mb-4 shadow-inner">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                </div>
                                <h3 class="text-lg font-black text-slate-800">Kayıt Bulunamadı</h3>
                                <p class="text-slate-500 font-medium mt-1">Belirlediğiniz kriterlere uygun gider kaydı bulunmuyor.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Gider Ekle/Düzenle Modal -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <div x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                
                <form :action="formAction" method="POST">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    
                    <div class="bg-white px-8 pt-8 pb-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-black text-slate-800" x-text="isEdit ? 'Gideri Düzenle' : 'Yeni Gider Ekle'"></h3>
                            <button type="button" @click="closeModal()" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-rose-100 hover:text-rose-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">İlgili Araç <span class="text-rose-500">*</span></label>
                                <select name="vehicle_id" x-model="formData.vehicle_id" required class="w-full bg-slate-50 border-2 border-slate-100 text-slate-900 rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 block p-3.5 font-bold transition-all">
                                    <option value="">Araç Seçiniz</option>
                                    @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}">{{ $vehicle->plate }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Gider Türü <span class="text-rose-500">*</span></label>
                                    <select name="type" x-model="formData.type" required class="w-full bg-slate-50 border-2 border-slate-100 text-slate-900 rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 block p-3.5 font-bold transition-all">
                                        <option value="">Tür Seçin</option>
                                        @foreach(\App\Models\Expense::getTypes() as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Tarih <span class="text-rose-500">*</span></label>
                                    <input type="date" name="date" x-model="formData.date" required class="w-full bg-slate-50 border-2 border-slate-100 text-slate-900 rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 block p-3.5 font-bold transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Tutar (₺) <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="number" step="0.01" min="0.01" name="amount" x-model="formData.amount" required class="w-full bg-slate-50 border-2 border-slate-100 text-slate-900 rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 block p-3.5 pl-12 font-bold text-lg transition-all" placeholder="0.00">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-slate-400 font-black text-lg">₺</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Açıklama / Notlar</label>
                                <textarea name="description" x-model="formData.description" rows="3" class="w-full bg-slate-50 border-2 border-slate-100 text-slate-900 rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 block p-3.5 font-bold transition-all" placeholder="Yapılan işlemi detaylandırın..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-8 py-5 border-t border-slate-100 flex justify-end gap-3 rounded-b-[32px]">
                        <button type="button" @click="closeModal()" class="px-6 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-50 transition-colors shadow-sm">
                            İptal
                        </button>
                        <button type="submit" class="px-6 py-3 rounded-2xl bg-indigo-500 text-white font-bold hover:bg-indigo-600 shadow-lg shadow-indigo-500/30 transition-colors">
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    function expenseManager() {
        return {
            isModalOpen: false,
            isEdit: false,
            formAction: '{{ route('expenses.store') }}',
            formData: {
                vehicle_id: '',
                type: '',
                date: '{{ date('Y-m-d') }}',
                amount: '',
                description: ''
            },
            openCreateModal() {
                this.isEdit = false;
                this.formAction = '{{ route('expenses.store') }}';
                this.formData = {
                    vehicle_id: '',
                    type: '',
                    date: '{{ date('Y-m-d') }}',
                    amount: '',
                    description: ''
                };
                this.isModalOpen = true;
            },
            openEditModal(expense) {
                this.isEdit = true;
                this.formAction = '/expenses/' + expense.id;
                this.formData = {
                    vehicle_id: expense.vehicle_id,
                    type: expense.type,
                    date: expense.date.split('T')[0],
                    amount: expense.amount,
                    description: expense.description || ''
                };
                this.isModalOpen = true;
            },
            closeModal() {
                this.isModalOpen = false;
            }
        }
    }
</script>
@endsection
