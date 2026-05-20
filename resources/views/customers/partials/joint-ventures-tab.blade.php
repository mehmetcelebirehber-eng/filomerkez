<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Ortak Girişim Firmaları</h3>
            <p class="text-sm text-slate-500">Bu müşteriye tanımladığınız taşeron/ortak firmalar ve onlara atanan servis güzergahları.</p>
        </div>
        
        <button type="button" x-data @click="$dispatch('open-modal', 'joint-venture-modal')" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-indigo-500/20 hover:shadow-lg transition-all active:scale-95">
            <span>🤝</span>
            <span>Yeni Ortak Firma Aç</span>
        </button>
    </div>

    @if($customer->jointVentures->count() > 0)
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-2">
            @foreach($customer->jointVentures as $jv)
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-md">
                    <div class="border-b border-slate-100 bg-slate-50/50 p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-2xl text-indigo-600">
                                    🏢
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800">{{ $jv->company_name }}</h4>
                                    <div class="mt-1 flex items-center gap-2 text-xs text-slate-500">
                                        @if($jv->tax_number)
                                            <span title="Vergi No" class="inline-flex items-center gap-1"><span class="text-slate-400">📄</span> {{ $jv->tax_number }}</span>
                                        @endif
                                        @if($jv->phone)
                                            <span title="Telefon" class="inline-flex items-center gap-1"><span class="text-slate-400">📞</span> {{ $jv->phone }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex gap-2">
                                <button type="button" 
                                    x-data 
                                    @click="$dispatch('edit-joint-venture', {
                                        id: {{ $jv->id }},
                                        company_name: '{{ addslashes($jv->company_name) }}',
                                        tax_number: '{{ addslashes($jv->tax_number) }}',
                                        phone: '{{ addslashes($jv->phone) }}',
                                        address: '{{ addslashes($jv->address) }}',
                                        routes: {{ json_encode($jv->serviceRoutes->pluck('id')) }}
                                    })"
                                    class="rounded-lg p-2 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-colors" title="Düzenle">
                                    ✏️
                                </button>
                                
                                <form action="{{ route('customers.joint-ventures.destroy', [$customer, $jv]) }}" method="POST" onsubmit="return confirm('Bu ortak firmayı silmek istediğinize emin misiniz? Atanmış güzergahlar boşa çıkacaktır.')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg p-2 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-colors" title="Sil">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <h5 class="mb-3 text-sm font-bold text-slate-700">Atanan Servis Güzergahları ({{ $jv->serviceRoutes->count() }})</h5>
                        @if($jv->serviceRoutes->count() > 0)
                            <div class="flex flex-col gap-2">
                                @foreach($jv->serviceRoutes as $r)
                                    <div class="flex items-center gap-2 rounded-lg border border-slate-100 bg-slate-50 px-3 py-2 text-sm text-slate-700">
                                        <span class="text-emerald-500">✔️</span>
                                        <span class="font-medium">{{ $r->route_name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-lg border border-dashed border-slate-200 p-4 text-center text-sm italic text-slate-400">
                                Henüz güzergah atanmamış.
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-3xl border border-dashed border-slate-300 py-16 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-3xl">🤝</div>
            <h4 class="mt-4 text-lg font-bold text-slate-800">Ortak Girişim Bulunamadı</h4>
            <p class="mt-1 text-sm text-slate-500">Bu müşteri için henüz bir ortak/taşeron firma tanımlamamışsınız.</p>
        </div>
    @endif
</div>

<!-- Yeni Ekle/Düzenle Modal -->
<div x-data="{ 
        show: false,
        isEdit: false,
        formAction: '{{ route('customers.joint-ventures.store', $customer) }}',
        jvId: null,
        companyName: '',
        taxNumber: '',
        phone: '',
        address: '',
        selectedRoutes: [],
        
        resetForm() {
            this.isEdit = false;
            this.formAction = '{{ route('customers.joint-ventures.store', $customer) }}';
            this.jvId = null;
            this.companyName = '';
            this.taxNumber = '';
            this.phone = '';
            this.address = '';
            this.selectedRoutes = [];
        }
     }"
     x-show="show"
     x-on:open-modal.window="if ($event.detail === 'joint-venture-modal') { resetForm(); show = true; }"
     x-on:edit-joint-venture.window="
        resetForm();
        isEdit = true;
        jvId = $event.detail.id;
        formAction = '{{ url('customers/' . $customer->id . '/joint-ventures') }}/' + jvId;
        companyName = $event.detail.company_name;
        taxNumber = $event.detail.tax_number;
        phone = $event.detail.phone;
        address = $event.detail.address;
        selectedRoutes = $event.detail.routes.map(String);
        show = true;
     "
     x-on:keydown.escape.window="show = false"
     class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0"
     style="display: none;">
    
    <div x-show="show" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="show = false"></div>

    <div x-show="show" 
         x-transition.duration.300ms
         class="relative mx-auto w-full max-w-2xl transform overflow-visible rounded-3xl bg-white shadow-2xl ring-1 ring-slate-200 transition-all">
        
        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-between rounded-t-3xl">
            <h3 class="text-lg font-bold text-slate-800" x-text="isEdit ? 'Ortak Firma Düzenle' : 'Yeni Ortak Firma Aç'"></h3>
            <button @click="show = false" class="text-slate-400 hover:text-slate-600">
                ✖️
            </button>
        </div>

        <form :action="formAction" method="POST" class="p-6">
            @csrf
            <template x-if="isEdit">
                <input type="hidden" name="_method" value="PUT">
            </template>
            
            <div class="grid gap-6 md:grid-cols-2">
                <!-- Firma Bilgileri -->
                <div class="space-y-4">
                    <h4 class="font-bold text-slate-700 border-b border-slate-100 pb-2">Firma Bilgileri</h4>
                    
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Firma Adı <span class="text-rose-500">*</span></label>
                        <input type="text" name="company_name" x-model="companyName" required class="block w-full rounded-xl border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Vergi Numarası</label>
                        <input type="text" name="tax_number" x-model="taxNumber" class="block w-full rounded-xl border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Telefon</label>
                        <input type="text" name="phone" x-model="phone" class="block w-full rounded-xl border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Adres</label>
                        <textarea name="address" x-model="address" rows="3" class="block w-full rounded-xl border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"></textarea>
                    </div>
                </div>

                <!-- Güzergah Atamaları -->
                <div>
                    <h4 class="font-bold text-slate-700 border-b border-slate-100 pb-2 mb-4">Servis Güzergahı Atama</h4>
                    <p class="text-xs text-slate-500 mb-3">Bu ortak firmanın yapacağı servis güzergahlarını işaretleyin.</p>
                    
                    <div class="space-y-2 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($customer->serviceRoutes as $route)
                            <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 hover:bg-slate-50 cursor-pointer transition-colors"
                                :class="{'bg-indigo-50 border-indigo-200': selectedRoutes.includes('{{ $route->id }}')}">
                                <input type="checkbox" name="routes[]" value="{{ $route->id }}" x-model="selectedRoutes"
                                    class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                                <div>
                                    <div class="text-sm font-bold text-slate-800">{{ $route->route_name }}</div>
                                    @if($route->joint_venture_id)
                                        <div class="text-xs text-slate-400 mt-0.5" x-show="!selectedRoutes.includes('{{ $route->id }}')">
                                            Şu an <span class="font-medium">{{ $route->jointVenture->company_name ?? 'başka bir firmaya' }}</span> atanmış. Seçerseniz bu firmaya geçer.
                                        </div>
                                    @endif
                                </div>
                            </label>
                        @empty
                            <div class="text-sm text-slate-400 italic">Bu müşteriye henüz tanımlı servis güzergahı yok.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                <button type="button" @click="show = false" class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50">
                    İptal
                </button>
                <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-2 text-sm font-bold text-white shadow-md shadow-indigo-500/20 hover:bg-indigo-500 hover:shadow-lg transition-all">
                    Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
