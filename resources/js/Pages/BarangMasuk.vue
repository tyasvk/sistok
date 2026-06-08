<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// State untuk membuka/menutup sidebar di tampilan Mobile
const isSidebarOpen = ref(false);

// Data dummy (nanti diganti dengan data dari database via controller)
const items = ref([
    { id: 1, name: 'Pelubang Kertas Kecil', code: 'BRG-002' },
    { id: 2, name: 'Printer Epson L3110', code: 'BRG-004' },
    { id: 3, name: 'Baterai AA Alkaline', code: 'BRG-005' },
]);

// Setup form Inertia
const form = useForm({
    item_id: '',
    tanggal: new Date().toISOString().substr(0, 10), // Default hari ini
    jumlah: '',
    keterangan: '',
});

const submit = () => {
    // Simulasi pengiriman data
    // form.post(route('barang-masuk.store'));
    alert('Simulasi: Data Barang Masuk berhasil dicatat!');
    form.reset('jumlah', 'keterangan');
};
</script>

<template>
    <Head title="Input Barang Masuk - Sistok" />

    <div class="flex h-screen bg-[#F8FAFC] font-sans text-gray-900 overflow-hidden relative">
        
        <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-black/50 z-20 md:hidden transition-opacity"></div>

        <aside :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed md:relative z-30 w-64 h-full bg-[#0F172A] text-white flex flex-col flex-shrink-0 transition-transform duration-300 ease-in-out md:translate-x-0">
            <div class="h-16 md:h-20 flex items-center justify-between px-6 border-b border-gray-800">
                <div>
                    <h1 class="text-xl md:text-2xl font-extrabold tracking-tight">SISTOK <span class="text-blue-500">LAPAS</span></h1>
                </div>
                <button @click="isSidebarOpen = false" class="md:hidden text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

<nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <Link :href="route('dashboard')" :class="route().current('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-gray-400 hover:text-white hover:bg-gray-800'" class="flex items-center px-4 py-3 rounded-xl font-medium transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"></path></svg>
                    Pantau Stok
                </Link>

                <Link :href="route('barang-masuk.index')" :class="route().current('barang-masuk.index') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-gray-400 hover:text-white hover:bg-gray-800'" class="flex items-center px-4 py-3 rounded-xl font-medium transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Input Barang
                </Link>
                
                <Link :href="route('barang-keluar.index')" :class="route().current('barang-keluar.index') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-gray-400 hover:text-white hover:bg-gray-800'" class="flex items-center px-4 py-3 rounded-xl font-medium transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Input Keluar
                </Link>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-hidden w-full">
            
            <header class="h-16 md:h-20 bg-white flex items-center justify-between px-4 sm:px-6 md:px-8 border-b border-gray-200 shrink-0">
                <div class="flex items-center">
                    <button @click="isSidebarOpen = true" class="mr-4 md:hidden text-gray-600 hover:text-gray-900 focus:outline-none p-2 -ml-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    
                    <div>
                        <h2 class="text-lg md:text-2xl font-bold text-gray-800 leading-tight">Barang Masuk</h2>
                        <p class="text-xs md:text-sm text-gray-500 hidden sm:block">Penambahan stok inventaris baru</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="hidden sm:block text-right">
                        <p class="text-sm font-bold text-gray-900 leading-tight">Admin</p>
                    </div>
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center font-bold border border-blue-200">
                        A
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 sm:p-6 md:p-8">
                
                <div class="max-w-3xl mx-auto">
                    <div class="bg-white rounded-2xl md:rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden">
                        
                        <div class="bg-blue-50/50 px-6 py-5 border-b border-gray-100 flex items-center">
                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Form Input Barang Masuk</h3>
                                <p class="text-sm text-gray-500">Pastikan data yang dimasukkan sesuai dengan fisik barang.</p>
                            </div>
                        </div>

                        <form @submit.prevent="submit" class="p-6 md:p-8 space-y-6">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Barang (ID/Nama) <span class="text-red-500">*</span></label>
                                    <select v-model="form.item_id" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                                        <option value="" disabled>-- Cari atau Pilih Barang --</option>
                                        <option v-for="item in items" :key="item.id" :value="item.id">
                                            [{{ item.code }}] - {{ item.name }}
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Masuk <span class="text-red-500">*</span></label>
                                    <input type="date" v-model="form.tanggal" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Tambahan Stok <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="number" min="1" v-model="form.jumlah" required placeholder="Contoh: 10" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 pl-4 pr-16 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <span class="text-gray-400 font-semibold text-sm">Unit</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan / Sumber Barang</label>
                                    <textarea v-model="form.keterangan" rows="3" placeholder="Contoh: Pengadaan APBN 2026 atau dari Gudang Pusat..." class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all resize-none"></textarea>
                                </div>
                            </div>

                            <hr class="border-gray-100 my-4">

                            <div class="flex flex-col-reverse md:flex-row justify-end gap-3 md:gap-4 mt-6">
                                <Link href="/dashboard" class="w-full md:w-auto px-6 py-3.5 md:py-3 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold text-center hover:bg-gray-50 transition-colors">
                                    Batal
                                </Link>
                                <button type="submit" :disabled="form.processing" class="w-full md:w-auto px-8 py-3.5 md:py-3 bg-blue-600 text-white rounded-xl font-bold text-center hover:bg-blue-700 transition-colors flex items-center justify-center shadow-lg shadow-blue-600/30" :class="{'opacity-75 cursor-wait': form.processing}">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                    Simpan Stok
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </main>
        </div>
    </div>
</template>