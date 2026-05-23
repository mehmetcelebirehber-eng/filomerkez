@extends('layouts.app')

@section('title', 'Muhasebe / Giderler')
@section('subtitle', 'Finans Yönetimi')

@section('content')
<div class="relative w-full">
    <!-- Welcome Screen -->
    <div class="relative group w-full">
        <div class="absolute -inset-1 rounded-[40px] bg-gradient-to-tr from-indigo-500/20 to-purple-600/20 blur opacity-70 group-hover:opacity-100 transition duration-500"></div>
        <div class="relative rounded-[40px] border border-white bg-white/40 shadow-xl backdrop-blur-xl p-12 text-center">
            <div class="max-w-3xl mx-auto py-12">
                <div class="inline-flex h-20 w-20 items-center justify-center rounded-3xl bg-indigo-500 text-white text-4xl shadow-2xl shadow-indigo-500/40 mb-8 animate-bounce">
                    🧾
                </div>
                <h2 class="text-4xl font-black text-slate-900 mb-6 tracking-tight">Muhasebe & Gider Yönetimi</h2>
                <p class="text-lg text-slate-500 font-medium leading-relaxed mb-8">
                    Bu modül şu anda tasarım ve geliştirme aşamasındadır. Çok yakında tüm gelir, gider, fatura ve cari işlemlerinizi buradan kolayca yönetebileceksiniz.
                </p>
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-100 text-amber-700 font-bold text-sm shadow-sm border border-amber-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Yapım Aşamasında
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
