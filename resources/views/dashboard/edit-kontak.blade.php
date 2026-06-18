@extends('layouts.app')
@section('header_title', 'Mode Edit Langsung - Kontak')
@section('content')
    <div class="max-w-6xl mx-auto">
        <div
            class="flex flex-col md:flex-row justify-between items-center mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded shadow-sm">
            <div>
                <h2 class="text-lg font-bold text-yellow-800">Mode Edit Visual Aktif</h2>
                <p class="text-sm text-yellow-700">Ubah kontak, nama pengurus, dan daftar anggota divisi langsung di dalam
                    kotak. Tekan "Enter" pada bagian anggota divisi untuk menambah anggota baru.</p>
            </div>
            <a href="{{ route('kontak') }}"
                class="mt-3 md:mt-0 text-gray-600 hover:text-gray-900 font-bold text-sm bg-white px-4 py-2 border rounded shadow-sm">Batalkan
                Edit</a>
        </div>

        @php
            $data =
                isset($kontenKontak) && !empty($kontenKontak->konten) ? json_decode($kontenKontak->konten, true) : [];
            if (!is_array($data)) {
                $data = [];
            }
            $d = function ($key, $default) use ($data) {
                return $data[$key] ?? $default;
            };
        @endphp

        <form action="{{ route('kontak.update') }}" method="POST">
            @csrf
            <div class="relative bg-gradient-to-r from-[#0d2a80] to-[#800000] rounded-3xl overflow-hidden shadow-lg mb-10">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-64 h-64 rounded-full bg-white opacity-10 blur-2xl"></div>
                <div class="relative z-10 px-8 py-10 md:p-12 md:w-2/3">
                    <input type="text" name="hero_badge" value="{{ $d('hero_badge', 'HUBUNGI KAMI') }}"
                        class="w-full md:w-1/2 bg-white/10 text-white text-xs font-bold tracking-wider mb-4 border border-dashed border-white/50 px-3 py-2 rounded focus:ring-2 focus:ring-white focus:outline-none">
                    <textarea name="hero_title" rows="2"
                        class="w-full bg-transparent border border-dashed border-white/50 text-3xl md:text-4xl font-extrabold text-white mb-4 leading-tight rounded p-2 focus:bg-black/20 focus:outline-none">{{ $d('hero_title', "Kami Siap Mendengar\ndan Melindungi Anda.") }}</textarea>
                    <textarea name="hero_desc" rows="3"
                        class="w-full bg-transparent border border-dashed border-white/50 text-blue-100 text-sm md:text-base leading-relaxed mb-6 rounded p-2 focus:bg-black/20 focus:outline-none">{{ $d('hero_desc', 'Jangan ragu untuk menghubungi Satgas PPKS USN Kolaka. Kami menjamin kerahasiaan identitas dan laporan Anda. Berikut adalah struktur keanggotaan dan kontak resmi kami.') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 border-b border-gray-200 pb-10">
                <div class="bg-white p-6 rounded-2xl border border-red-200 shadow-sm relative pt-8">
                    <span
                        class="absolute top-0 left-0 bg-red-500 text-white text-xs px-2 py-1 rounded-br-lg rounded-tl-xl shadow">Edit
                        Hotline</span>
                    <input type="text" name="wa_title" value="{{ $d('wa_title', 'Hotline PPKS') }}"
                        class="w-full bg-transparent border border-dashed border-gray-400 font-bold text-gray-500 text-sm mb-2 rounded focus:outline-none">
                    <input type="text" name="wa_nomor" value="{{ $d('wa_nomor', '0812-XXXX-XXXX') }}"
                        class="w-full bg-red-50 border border-dashed border-red-400 font-bold text-gray-800 text-lg mb-2 rounded p-1 focus:outline-none">
                    <textarea name="wa_desc" rows="2"
                        class="w-full bg-transparent border border-dashed border-gray-300 text-xs text-gray-500 rounded p-1 focus:outline-none resize-none">{{ $d('wa_desc', 'Aktif pada jam kerja (08:00 - 16:00 WITA)') }}</textarea>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-blue-200 shadow-sm relative pt-8">
                    <span
                        class="absolute top-0 left-0 bg-blue-500 text-white text-xs px-2 py-1 rounded-br-lg rounded-tl-xl shadow">Edit
                        Email</span>
                    <input type="text" name="email_title" value="{{ $d('email_title', 'Email Pengaduan') }}"
                        class="w-full bg-transparent border border-dashed border-gray-400 font-bold text-gray-500 text-sm mb-2 rounded focus:outline-none">
                    <input type="text" name="email_alamat" value="{{ $d('email_alamat', 'satgasppks@usn.ac.id') }}"
                        class="w-full bg-blue-50 border border-dashed border-blue-400 font-bold text-gray-800 text-lg mb-2 rounded p-1 focus:outline-none">
                    <textarea name="email_desc" rows="2"
                        class="w-full bg-transparent border border-dashed border-gray-300 text-xs text-gray-500 rounded p-1 focus:outline-none resize-none">{{ $d('email_desc', 'Kami membalas dalam waktu 1x24 Jam') }}</textarea>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-green-200 shadow-sm relative pt-8">
                    <span
                        class="absolute top-0 left-0 bg-green-500 text-white text-xs px-2 py-1 rounded-br-lg rounded-tl-xl shadow">Edit
                        Lokasi</span>
                    <input type="text" name="alamat_title" value="{{ $d('alamat_title', 'Ruang Satgas') }}"
                        class="w-full bg-transparent border border-dashed border-gray-400 font-bold text-gray-500 text-sm mb-2 rounded focus:outline-none">
                    <input type="text" name="alamat_singkat" value="{{ $d('alamat_singkat', 'Gedung Rektorat Lt. 1') }}"
                        class="w-full bg-green-50 border border-dashed border-green-400 font-bold text-gray-800 text-lg mb-2 rounded p-1 focus:outline-none">
                    <textarea name="alamat_desc" rows="2"
                        class="w-full bg-transparent border border-dashed border-gray-300 text-xs text-gray-500 rounded p-1 focus:outline-none resize-none">{{ $d('alamat_desc', 'Universitas Sembilanbelas November Kolaka') }}</textarea>
                </div>
            </div>

            <div class="mb-12">
                <div class="text-center border-b pb-4 mb-8">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Edit Judul Struktur
                        Organisasi</p>
                    <input type="text" name="struktur_title"
                        value="{{ $d('struktur_title', 'Struktur Organisasi Satgas PPKPT USN Kolaka') }}"
                        class="w-full md:w-3/4 mx-auto text-center bg-transparent border border-dashed border-gray-400 font-bold text-gray-800 text-2xl rounded p-2 focus:bg-gray-50 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                    <div class="bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-600 relative">
                        <span class="absolute -top-3 left-4 bg-gray-500 text-white text-xs px-2 py-1 rounded shadow">Edit
                            Pengarah</span>
                        <p class="text-xs text-gray-300 uppercase tracking-widest font-semibold mb-1">Nama Lengkap & Gelar
                        </p>
                        <input type="text" name="org_pengarah_nama"
                            value="{{ $d('org_pengarah_nama', 'Dr. Nur Ihsan HL, S.Pd.,M.Hum') }}"
                            class="w-full bg-gray-900 border border-dashed border-gray-500 font-bold text-white text-lg mb-3 rounded p-2 focus:outline-none focus:border-white">
                        <p class="text-xs text-gray-300 uppercase tracking-widest font-semibold mb-1">Jabatan Tambahan</p>
                        <input type="text" name="org_pengarah_jab" value="{{ $d('org_pengarah_jab', 'Rektor') }}"
                            class="w-full bg-gray-900 border border-dashed border-gray-500 text-gray-300 text-sm rounded p-2 focus:outline-none focus:border-white">
                    </div>
                    <div class="bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-600 relative">
                        <span class="absolute -top-3 left-4 bg-gray-500 text-white text-xs px-2 py-1 rounded shadow">Edit
                            Penanggungjawab</span>
                        <p class="text-xs text-gray-300 uppercase tracking-widest font-semibold mb-1">Nama Lengkap & Gelar
                        </p>
                        <input type="text" name="org_pj_nama"
                            value="{{ $d('org_pj_nama', 'Qammaddin, S.Kom., M.Kom, CITSM, ECIH') }}"
                            class="w-full bg-gray-900 border border-dashed border-gray-500 font-bold text-white text-lg mb-3 rounded p-2 focus:outline-none focus:border-white">
                        <p class="text-xs text-gray-300 uppercase tracking-widest font-semibold mb-1">Jabatan Tambahan</p>
                        <input type="text" name="org_pj_jab" value="{{ $d('org_pj_jab', 'Wakil Rektor III') }}"
                            class="w-full bg-gray-900 border border-dashed border-gray-500 text-gray-300 text-sm rounded p-2 focus:outline-none focus:border-white">
                    </div>
                </div>

                <div class="flex flex-col md:flex-row justify-center gap-6 mb-10">
                    <div
                        class="w-full md:w-1/3 bg-white border-t-4 border-[#800000] p-6 rounded-xl shadow-sm text-center relative pt-8">
                        <span class="absolute top-2 right-2 bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Edit
                            Ketua</span>
                        <p class="text-xs text-[#800000] uppercase tracking-widest font-bold mb-2">Ketua Satgas</p>
                        <input type="text" name="org_ketua_nama"
                            value="{{ $d('org_ketua_nama', 'Muhamad Aksan Akbar, S.H., M.H') }}"
                            class="w-full text-center bg-transparent border border-dashed border-red-300 font-bold text-gray-900 text-lg rounded p-2 focus:bg-red-50 focus:outline-none">
                    </div>
                    <div
                        class="w-full md:w-1/3 bg-white border-t-4 border-blue-600 p-6 rounded-xl shadow-sm text-center relative pt-8">
                        <span class="absolute top-2 right-2 bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Edit
                            Sekretaris</span>
                        <p class="text-xs text-blue-600 uppercase tracking-widest font-bold mb-2">Sekretaris Satgas</p>
                        <input type="text" name="org_sek_nama" value="{{ $d('org_sek_nama', 'Irwan, S.Pi') }}"
                            class="w-full text-center bg-transparent border border-dashed border-blue-300 font-bold text-gray-900 text-lg rounded p-2 focus:bg-blue-50 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
                        <div class="bg-blue-600 p-3"><input type="text" name="div1_nama"
                                value="{{ $d('div1_nama', 'Divisi Pencegahan & Edukasi') }}"
                                class="w-full text-center bg-white/20 text-white font-bold uppercase tracking-wider text-sm border-none focus:outline-none focus:bg-white/30 rounded">
                        </div>
                        <div class="p-5 flex-1 bg-blue-50/30">
                            <span class="text-[10px] font-bold text-blue-600 uppercase mb-1 block">Koordinator</span>
                            <input type="text" name="div1_koor"
                                value="{{ $d('div1_koor', 'Dr. Grace Tedy Tulak, S.Kep.,Ns.,M.Kep') }}"
                                class="w-full bg-white border border-dashed border-blue-300 font-bold text-gray-900 text-sm mb-4 rounded p-2 focus:outline-none">

                            <span class="text-[10px] font-bold text-gray-500 uppercase mb-1 block flex justify-between">
                                Daftar Anggota
                                <span class="text-blue-500 font-normal normal-case">(Tekan Enter untuk anggota baru)</span>
                            </span>
                            <textarea name="div1_anggota" rows="5"
                                class="w-full bg-white border border-dashed border-gray-300 font-medium text-gray-800 text-sm rounded p-2 focus:outline-none resize-none">{{ $d('div1_anggota', "Dr. Sarmadan, S.Pd., M.Pd.\nSaleh, S. Ag., M.A.") }}</textarea>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
                        <div class="bg-orange-500 p-3"><input type="text" name="div2_nama"
                                value="{{ $d('div2_nama', 'Divisi Informasi & Komunikasi') }}"
                                class="w-full text-center bg-white/20 text-white font-bold uppercase tracking-wider text-sm border-none focus:outline-none focus:bg-white/30 rounded">
                        </div>
                        <div class="p-5 flex-1 bg-orange-50/30">
                            <span class="text-[10px] font-bold text-orange-600 uppercase mb-1 block">Koordinator</span>
                            <input type="text" name="div2_koor"
                                value="{{ $d('div2_koor', 'Hj. Nuraidah Tayeb, S.Pd., M.M.Pd') }}"
                                class="w-full bg-white border border-dashed border-orange-300 font-bold text-gray-900 text-sm mb-4 rounded p-2 focus:outline-none">

                            <span class="text-[10px] font-bold text-gray-500 uppercase mb-1 block flex justify-between">
                                Daftar Anggota
                            </span>
                            <textarea name="div2_anggota" rows="5"
                                class="w-full bg-white border border-dashed border-gray-300 font-medium text-gray-800 text-sm rounded p-2 focus:outline-none resize-none">{{ $d('div2_anggota', "Arman Sagita, S.Kep., Ns.\nAriel Bezalel Santoso\nAndi Lena Patma Dewi") }}</textarea>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
                        <div class="bg-green-600 p-3"><input type="text" name="div3_nama"
                                value="{{ $d('div3_nama', 'Divisi Penanganan & Pemulihan') }}"
                                class="w-full text-center bg-white/20 text-white font-bold uppercase tracking-wider text-sm border-none focus:outline-none focus:bg-white/30 rounded">
                        </div>
                        <div class="p-5 flex-1 bg-green-50/30">
                            <span class="text-[10px] font-bold text-green-600 uppercase mb-1 block">Koordinator</span>
                            <input type="text" name="div3_koor"
                                value="{{ $d('div3_koor', 'Ns. Heriviyatno Siagian, S.Kep., M.N') }}"
                                class="w-full bg-white border border-dashed border-green-300 font-bold text-gray-900 text-sm mb-4 rounded p-2 focus:outline-none">

                            <span class="text-[10px] font-bold text-gray-500 uppercase mb-1 block flex justify-between">
                                Daftar Anggota
                            </span>
                            <textarea name="div3_anggota" rows="5"
                                class="w-full bg-white border border-dashed border-gray-300 font-medium text-gray-800 text-sm rounded p-2 focus:outline-none resize-none">{{ $d('div3_anggota', "Anis Ribcalia Septiana, S.Sos.,M.Si\nTukatman, S.Kep.Ns.M.Kep\nMariany, S.St.,M.Keb") }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] z-50 flex justify-center gap-4">
                <a href="{{ route('kontak') }}"
                    class="bg-gray-200 text-gray-800 font-bold py-3 px-8 rounded-lg transition">Batal</a>
                <button
                    class="bg-[#800000] hover:bg-red-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition"
                    type="submit">
                    Simpan Semua Perubahan
                </button>
            </div>
            <div class="h-24"></div>
        </form>
    </div>
@endsection
