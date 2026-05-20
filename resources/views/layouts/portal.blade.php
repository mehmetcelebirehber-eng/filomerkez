<!DOCTYPE html>
<html lang="tr" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FiloMerkez - Ortak Girişim Portalı')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full flex flex-col text-slate-800 antialiased">
    
    <!-- Üst Menü / Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-lg font-bold text-xl">
                        F
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-800">FiloMerkez</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 ring-1 ring-inset ring-indigo-500/10">
                        🤝 Taşeron / Ortak Girişim Portalı
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Ana İçerik -->
    <main class="flex-1 py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <!-- Alt Kısım -->
    <footer class="bg-white border-t border-slate-200 mt-auto">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-sm text-slate-500">
                &copy; {{ date('Y') }} FiloMerkez. Tüm hakları saklıdır. Güvenli evrak yönetim sistemi.
            </p>
        </div>
    </footer>

</body>
</html>
