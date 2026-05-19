<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agenda->judul }} - Satgas PPKS USN Kolaka</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .article-content {
            font-size: 1rem;
            line-height: 1.7;
            color: #374151;
        }

        .article-content p {
            margin-bottom: 1.25rem;
        }

        .article-content h1,
        .article-content h2,
        .article-content h3,
        .article-content h4 {
            color: #111827;
            font-weight: 800;
            margin-top: 2rem;
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .article-content h2 {
            font-size: 1.5rem;
            border-bottom: 3px solid #fecaca;
            display: inline-block;
            padding-bottom: 0.25rem;
        }

        .article-content h3 {
            font-size: 1.25rem;
        }

        .article-content ul {
            list-style-type: disc;
            padding-left: 1.5rem;
            margin-bottom: 1.25rem;
        }

        .article-content ol {
            list-style-type: decimal;
            padding-left: 1.5rem;
            margin-bottom: 1.25rem;
        }

        .article-content li {
            margin-bottom: 0.5rem;
        }

        .article-content a {
            color: #800000;
            font-weight: 600;
            text-decoration: underline;
            text-decoration-color: #fca5a5;
            text-underline-offset: 4px;
        }

        .article-content a:hover {
            color: #b30000;
            text-decoration-color: #800000;
        }

        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin: 2rem auto;
            display: block;
        }

        .article-content blockquote {
            border-left: 4px solid #800000;
            background-color: #fef2f2;
            padding: 1rem 1.25rem;
            margin: 1.5rem 0;
            font-style: italic;
            border-radius: 0 0.5rem 0.5rem 0;
            color: #4b5563;
        }

        .article-content iframe {
            width: 100%;
            border-radius: 0.75rem;
            margin: 1.5rem 0;
        }

        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-[#f8fafc] text-gray-800 antialiased selection:bg-[#800000] selection:text-white" x-data="{ showImageModal: false }">

    @php
        $prevUrl = url()->previous();

        $isFromAdmin = str_contains($prevUrl, '/agenda') || request()->query('ref') == 'admin';

        $backUrl = $isFromAdmin ? route('agenda.index') : url('/#agenda');
        $backText = $isFromAdmin ? 'Kembali ke Panel Tabel' : 'Kembali ke Beranda';

        $breadcrumbRootUrl = $isFromAdmin ? route('dashboard') : url('/');
        $breadcrumbRootText = $isFromAdmin ? 'Dashboard' : 'Beranda';

        $refParam = $isFromAdmin ? '?ref=admin' : '';
    @endphp

    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ $backUrl }}" class="flex items-center gap-3 group">
                <div
                    class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center border border-gray-100 group-hover:border-red-200 transition-colors">
                    <img src="{{ asset('assets/image/logo.PNG') }}" alt="Logo" class="w-7 h-7 object-contain">
                </div>
                <div>
                    <h1
                        class="font-black text-gray-900 tracking-tight leading-none group-hover:text-[#800000] transition-colors">
                        SATGAS PPKPT</h1>
                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">USN KOLAKA</span>
                </div>
            </a>

            <a href="{{ $backUrl }}"
                class="hidden md:inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-[#800000] bg-gray-50 hover:bg-red-50 px-4 py-2 rounded-full transition-all border border-transparent hover:border-red-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ $backText }}
            </a>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <nav class="flex text-sm text-gray-500 font-medium mb-6 whitespace-nowrap overflow-x-auto hide-scroll pb-2">
            <a href="{{ $breadcrumbRootUrl }}" class="hover:text-[#800000] flex items-center gap-1"><svg class="w-4 h-4"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg> {{ $breadcrumbRootText }}</a>
            <span class="mx-2 text-gray-300">/</span>
            <a href="{{ $backUrl }}" class="hover:text-[#800000]">Agenda & Berita</a>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-gray-800 font-bold truncate max-w-xs md:max-w-md">{{ $agenda->judul }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <main class="lg:col-span-2">
                <article class="bg-white rounded-[1.5rem] shadow-sm border border-gray-100 overflow-hidden">

                    <div class="p-6 md:p-8 lg:p-10 pb-6 md:pb-6 border-b border-gray-50">
                        <div class="flex items-center gap-3 mb-5">
                            <span
                                class="bg-red-50 text-[#800000] px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-md border border-red-100">
                                Berita Terkini
                            </span>
                            <span class="text-gray-400 text-[11px] font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                3 Menit Baca
                            </span>
                        </div>

                        <h1
                            class="text-2xl md:text-3xl lg:text-4xl font-black text-gray-900 leading-tight mb-5 tracking-tight">
                            {{ $agenda->judul }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-4 text-sm font-medium text-gray-600">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center border border-gray-200">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-xs mb-0.5">
                                        {{ $agenda->penulis ?? 'Admin PPKS' }}</p>
                                </div>
                            </div>
                            <div class="hidden sm:block w-px h-6 bg-gray-200"></div>

                            {{-- PERBAIKAN WAKTU DITERBITKAN (Sejajar ke Kanan) --}}
                            <div>
                                <p class="text-[9px] text-gray-400 uppercase tracking-wide mb-1.5">Diterbitkan pada</p>
                                <div class="flex items-center gap-2.5">
                                    <p class="font-bold text-gray-800 text-xs flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-[#800000]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($agenda->created_at)->translatedFormat('d F Y') }}
                                    </p>
                                    <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                    <p class="text-xs text-gray-500 font-medium flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($agenda->created_at)->format('H:i') }} WITA
                                    </p>
                                </div>
                            </div>

                            {{-- PERBAIKAN WAKTU DIEDIT (Sejajar ke Kanan) --}}
                            @if ($agenda->updated_at && $agenda->updated_at->format('Y-m-d H:i') != $agenda->created_at->format('Y-m-d H:i'))
                                <div class="hidden sm:block w-px h-6 bg-gray-200"></div>
                                <div>
                                    <p class="text-[9px] text-gray-400 uppercase tracking-wide mb-1.5">Terakhir
                                        Diperbarui</p>
                                    <div class="flex items-center gap-2.5">
                                        <p class="font-bold text-gray-800 text-xs flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                </path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($agenda->updated_at)->translatedFormat('d F Y') }}
                                        </p>
                                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                        <p class="text-xs text-gray-500 font-medium flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($agenda->updated_at)->format('H:i') }} WITA
                                        </p>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                    @if ($agenda->thumbnail)
                        <div @click="showImageModal = true"
                            class="w-full bg-gray-100 border-b border-gray-50 flex justify-center relative group cursor-pointer overflow-hidden">
                            <img src="{{ asset($agenda->thumbnail) }}" alt="{{ $agenda->judul }}"
                                class="w-full h-auto max-h-[350px] object-cover object-center transition-transform duration-500 group-hover:scale-105">

                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center z-10">
                                <div
                                    class="bg-white/20 backdrop-blur-md px-5 py-2.5 rounded-full text-white font-bold flex items-center gap-2 border border-white/30 shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7">
                                        </path>
                                    </svg>
                                    Lihat Gambar
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="p-6 md:p-8 lg:p-10 pt-6">
                        <div class="article-content">
                            {!! $agenda->konten !!}
                        </div>

                        <div
                            class="mt-10 pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <span class="font-bold text-gray-500 text-[11px] uppercase tracking-wider">Bagikan
                                Informasi
                                Ini:</span>
                            <div class="flex items-center gap-2">

                                <a href="https://api.whatsapp.com/send?text={{ urlencode($agenda->judul . ' - ' . url()->current()) }}"
                                    target="_blank"
                                    class="w-8 h-8 rounded-full bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-500 hover:text-white transition-colors border border-green-100"
                                    title="Bagikan ke WhatsApp">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.417-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.305 1.652zm6.599-3.835c1.516.903 3.003 1.387 4.793 1.388 5.439 0 9.865-4.426 9.868-9.867 0-2.637-1.026-5.112-2.891-6.977-1.864-1.864-4.337-2.891-6.97-2.891-5.442 0-9.866 4.426-9.869 9.868 0 1.908.531 3.448 1.474 5.073l-.951 3.446 3.546-.94z">
                                        </path>
                                    </svg>
                                </a>

                                <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($agenda->judul) }}"
                                    target="_blank"
                                    class="w-8 h-8 rounded-full bg-sky-50 text-sky-500 flex items-center justify-center hover:bg-sky-500 hover:text-white transition-colors border border-sky-100"
                                    title="Bagikan ke Telegram">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.892-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                                    </svg>
                                </a>

                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                                    target="_blank"
                                    class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors border border-blue-100"
                                    title="Bagikan ke Facebook">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z">
                                        </path>
                                    </svg>
                                </a>

                                <button
                                    onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('Tautan berhasil disalin!\nSilakan buka aplikasi Instagram Anda dan tempel tautan ini untuk dibagikan.');"
                                    class="w-8 h-8 rounded-full bg-pink-50 text-pink-600 flex items-center justify-center hover:bg-pink-500 hover:text-white transition-colors border border-pink-100"
                                    title="Bagikan ke Instagram / Lainnya">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                    </svg>
                                </button>

                                <button
                                    onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('Tautan berhasil disalin ke clipboard!');"
                                    class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-gray-800 hover:text-white transition-colors border border-gray-200"
                                    title="Salin Tautan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            </main>

            <aside class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">

                    <div class="bg-white rounded-[1.5rem] p-6 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-3 mb-5 border-b border-gray-100 pb-3">
                            <div class="w-1.5 h-5 bg-[#800000] rounded-full"></div>
                            <h3 class="text-lg font-black text-gray-900 tracking-tight">Baca Juga</h3>
                        </div>

                        <div class="space-y-5">
                            @forelse($agendas_lain as $item)
                                <a href="{{ route('agenda.show', $item->slug) }}{{ $refParam }}"
                                    class="flex flex-col gap-2 group">
                                    <div
                                        class="w-full h-32 shrink-0 rounded-xl overflow-hidden bg-gray-100 border border-gray-100 relative">
                                        @if ($item->thumbnail)
                                            <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->judul }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p
                                            class="text-[9px] font-bold text-gray-400 mb-1 flex items-center gap-1 uppercase tracking-wider">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                        </p>
                                        <h4
                                            class="text-sm font-bold text-gray-800 leading-snug group-hover:text-[#800000] transition-colors line-clamp-2">
                                            {{ $item->judul }}
                                        </h4>
                                    </div>
                                </a>
                            @empty
                                <div
                                    class="py-6 text-center text-gray-400 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                    <p class="text-xs font-medium">Belum ada agenda lain.</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-5 pt-4 border-t border-gray-50">
                            <a href="{{ $backUrl }}"
                                class="w-full flex items-center justify-center gap-2 bg-gray-50 hover:bg-red-50 text-gray-600 hover:text-[#800000] text-xs font-bold py-2.5 rounded-lg transition-colors border border-gray-200 hover:border-red-200">
                                Lihat Semua Berita
                            </a>
                        </div>
                    </div>

                    <div
                        class="bg-gradient-to-br from-[#800000] to-red-900 rounded-[1.5rem] p-6 shadow-sm text-white relative overflow-hidden">
                        <div class="absolute -right-6 -bottom-6 opacity-10">
                            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                            </svg>
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-base font-black leading-tight mb-2">Melihat atau Mengalami Kekerasan?</h3>
                            <p class="text-[11px] text-red-100 mb-5 leading-relaxed">Jangan ragu, laporkan sekarang.
                                Identitas Anda dijamin kerahasiaannya oleh undang-undang.</p>
                            <a href="{{ url('/laporkan') }}"
                                class="block text-center bg-white text-[#800000] font-black text-[11px] uppercase tracking-wider py-2.5 px-4 rounded-lg shadow-sm hover:scale-105 transition-transform">
                                Buat Laporan
                            </a>
                        </div>
                    </div>

                </div>
            </aside>
        </div>
    </div>

    @if ($agenda->thumbnail)
        <template x-teleport="body">
            <div x-show="showImageModal" style="display: none;"
                class="fixed inset-0 z-[10000] flex items-center justify-center bg-gray-900/90 backdrop-blur-sm p-4 md:p-10"
                x-transition.opacity>

                <button @click="showImageModal = false"
                    class="absolute top-4 right-4 md:top-8 md:right-8 text-white/70 hover:text-white bg-black/20 hover:bg-black/40 p-2 rounded-full backdrop-blur-md transition-colors focus:outline-none z-50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <div @click.away="showImageModal = false"
                    class="relative w-full max-w-5xl flex flex-col items-center transform transition-all"
                    x-transition.scale>
                    <img src="{{ asset($agenda->thumbnail) }}" alt="{{ $agenda->judul }}"
                        class="w-full h-auto max-h-[85vh] object-contain shadow-2xl bg-transparent">
                </div>
            </div>
        </template>
    @endif

    <style>
        .hide-scroll::-webkit-scrollbar {
            display: none;
        }

        .hide-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</body>

</html>
