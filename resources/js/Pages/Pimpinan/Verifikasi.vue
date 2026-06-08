<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    transactions: {
        type: Array,
        default: () => []
    }
});

// State untuk sidebar mobile
const isSidebarOpen = ref(false);

// Fungsi untuk menyetujui transaksi pengeluaran barang
const approveTransaction = (id, productName, quantity) => {
    if (confirm(`Apakah Anda yakin ingin menyetujui pengeluaran ${quantity} item untuk "${productName}"?`)) {
        router.patch(route('pimpinan.approve', id), {}, {
            preserveScroll: true,
            onSuccess: () => {
                alert('Transaksi berhasil diverifikasi dan stok telah resmi dikurangi.');
            }
        });
    }
};

// Format Tanggal & Waktu (Sesuai Zona Waktu Lokal)
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { 
        day: '2-digit', 
        month: 'short', 
        year: 'numeric', 
        hour: '2-digit', 
        minute: '2-digit' 
    });
};
</script>

<template>
    <Head title="Verifikasi Pimpinan - Sistok Lapas" />

    <div class="flex h-screen bg-[#F8FAFC] font-sans text-gray-900 relative overflow-hidden">
        
        <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-black/50 z-20 md:hidden transition-opacity"></div>

        <aside :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed md:relative z-30 w-64 h-full bg-[#0F172A] text-white flex flex-col flex-shrink-0 transition-transform duration-300 ease-in-out md:translate-x-0">
            <div class="h-16 md:h-20 flex items-center justify-between px-6 border-b border-gray-800">
                <h1 class="text-xl font-extrabold">SISTOK <span class="text-blue-500">LAPAS</span></h1>
                <button @click="isSidebarOpen = false" class="md:hidden text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <Link :href="route('pimpinan.verifikasi')" class="flex items-center px-4 py-3 bg-blue-600 text-white rounded-xl font-medium shadow-lg shadow-blue-600/20">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Butuh Verifikasi
                    <span v-if="transactions.length > 0" class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full animate-pulse">
                        {{ transactions.length }}
                    </span>
                </Link>
            </nav>

            <div class="p-4 border-t border-gray-800">
                <Link :href="route('logout')" method="post" as="button" class="flex items-center w-full px-4 py-3 text-red-400 hover:bg-red-900/20 rounded-xl">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar
                </Link>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen w-full overflow-hidden">
            
            <header class="h-16 md:h-20 bg-white flex items-center justify-between px-4 md:px-8 border-b border-gray-200 shrink-0">
                <div class="flex items-center">
                    <button @click="isSidebarOpen = true" class="md:hidden p-2 -ml-2 text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h2 class="text-lg md:text-2xl font-bold text-gray-800">Persetujuan Pimpinan</h2>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-gray-900">{{ $page.props.auth?.user?.name || 'Pimpinan' }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Kalapas Palembang</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold shadow-md">P</div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8">
                
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-4 md:p-8">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900">Pengajuan Mutasi Barang Keluar</h3>
                        <p class="text-sm text-gray-500">Daftar permintaan pengambilan barang logistik gudang yang memerlukan validasi Anda</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] md:text-xs font-bold text-gray-400 uppercase border-b border-gray-100">
                                    <th class="pb-4 px-2">Tanggal Pengajuan</th>
                                    <th class="pb-4 px-2">Nama Barang</th>
                                    <th class="pb-4 px-2">Jumlah Diambil</th>
                                    <th class="pb-4 px-2">Tujuan / Keperluan</th>
                                    <th class="pb-4 px-2">Status Administrasi</th>
                                    <th class="pb-4 px-2 text-center">Aksi Persetujuan</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <tr v-for="log in transactions" :key="log.id" class="border-b border-gray-50 hover:bg-gray-50/80 transition-colors">
                                    <td class="py-4 px-2 text-gray-500 text-xs">{{ formatDate(log.created_at) }}</td>
                                    
                                    <td class="py-4 px-2 font-bold text-gray-900">{{ log.product ? log.product.name : 'Barang Dihapus' }}</td>
                                    
                                    <td class="py-4 px-2 font-black text-gray-900">
                                        {{ log.quantity }} 
                                        <span class="text-[10px] font-normal text-gray-400 uppercase ml-0.5">{{ log.product?.unit || 'Pcs' }}</span>
                                    </td>
                                    
                                    <td class="py-4 px-2 text-gray-600 font-medium">{{ log.keterangan || '-' }}</td>
                                    
                                    <td class="py-4 px-2">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-orange-50 text-orange-600 tracking-wide">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-orange-500 rounded-full animate-ping"></span>
                                            MENUNGGU VERIFIKASI
                                        </span>
                                    </td>
                                    
                                    <td class="py-4 px-2 text-center">
                                        <button 
                                            @click="approveTransaction(log.id, log.product?.name, log.quantity)"
                                            class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/10 hover:bg-emerald-700 hover:shadow-lg transition-all duration-200"
                                        >
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            Setujui & Kurangi Stok
                                        </button>
                                    </td>
                                </tr>
                                
                                <tr v-if="transactions.length === 0">
                                    <td colspan="6" class="py-16 text-center text-gray-400 font-medium">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <p class="text-gray-400 text-sm">Bersih! Tidak ada pengajuan pengeluaran barang yang menunggu verifikasi.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>
</template>