<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ($title ?? '') ? $title . ' • ' : '' }}Müşteri Portalı</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }
        .animate-shimmer {
            animation: shimmer 2s infinite;
        }
        @keyframes text-shine {
            to {
                background-position: 200% center;
            }
        }
        .animate-text-shine {
            animation: text-shine 3s linear infinite;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
@php
    $user = auth()->user();
    $customer = $user?->customer;
@endphp

<div class="min-h-screen">
    <header class="border-b border-slate-200 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-2xl text-white shadow-lg">
                    🏢
                </div>
                <div>
                    <div class="text-sm font-bold text-slate-900">{{ $customer->company_name ?? 'Müşteri Portalı' }}</div>
                    <div class="text-xs text-slate-500">Size özel müşteri ekranı</div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden text-right md:block">
                    <div class="text-sm font-semibold text-slate-800">{{ $user->name ?? '' }}</div>
                    <div class="text-xs text-slate-500">{{ $user->username ?? '' }}</div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="relative flex items-center gap-2 rounded-2xl bg-rose-500 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-rose-500/30 transition-all hover:bg-rose-600 hover:shadow-xl hover:shadow-rose-600/40 active:scale-95 group overflow-hidden">
                        <span class="absolute inset-0 rounded-2xl ring-2 ring-rose-500 ring-offset-2 animate-pulse pointer-events-none opacity-50"></span>
                        <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/40 to-transparent group-hover:animate-shimmer"></div>
                        <span>👋</span> Çıkış Yap
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="px-6 py-8">
        @yield('content')
    </main>
</div>
</body>
</html>
