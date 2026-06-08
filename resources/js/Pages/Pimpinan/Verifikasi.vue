<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    transactions: {
        type: Array,
        default: () => []
    }
});

const isSidebarOpen = ref(false);

const approve = (id) => {
    if (confirm('Anda yakin ingin menyetujui pengeluaran barang ini?')) {
        router.patch(route('pimpinan.approve', id), {}, {
            preserveScroll: true
        });
    }
};
</script>

<template>
    <Head title="Verifikasi Pimpinan - Sistok Lapas" />

    <div class="flex h-screen bg-[#F8FAFC] font-sans text-gray-900 overflow-hidden relative">
        
        <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-black/50 z-20 md:hidden transition-opacity"></div>

        <aside :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed md:relative z-30 w-64 h-full bg-[#0F172A] text-white flex flex-col flex-shrink-0 transition-transform duration-300 ease-in-out md:translate-x-0">
            <div class="h-16 md:h-20 flex items-center justify-between px-6 border-b border-gray-800">
                <h1 class="text-xl font-extrabold">SISTOK <span class="text-blue-500">LAPAS</span></h1>
                <button @click="isSidebarOpen = false" class="md:hidden text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

           <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <Link :href="route('dashboard')" class="flex items-center px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-xl transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Pantau Stok
                </Link>
                <Link :href="route('riwayat.index')" class="flex items-center px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-xl transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Riwayat Transaksi
                </Link>
                
                <Link v-if="$page.props.auth?.user?.is_pimpinan" :href="route('pimpinan.verifikasi')" class="flex items-center px-4 py-3 bg-red-600 text-white rounded-xl font-medium shadow-lg shadow-red-600/20">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Verifikasi Barang
                </Link>
                <Link :href="route('profile.edit')" class="flex items-center px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-xl transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Edit Profil
                </Link>
            </nav>
            
            <div class="p-4 border-t border-gray-800">
                <Link :href="route('logout')" method="post" as="button" class="flex items-center w-full px-4 py-3 text-red-400 hover:bg-red-900/20 rounded-xl">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar
                </Link>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-hidden w-full">
<header class="h-16 md:h-20 bg-white flex items-center justify-between px-4 md:px-8 border-b border-gray-200 shrink-0">
                <div class="flex items-center">
                    <button @click="isSidebarOpen = true" class="md:hidden p-2 -ml-2 text-gray-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></button>
                    <h2 class="text-lg md:text-2xl font-bold text-gray-800">Dashboard</h2> 
                </div>

                <div class="flex items-center space-x-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-gray-900">{{ $page.props.auth.user.name }}</p>
                        <p class="text-[10px] text-gray-500 font-bold uppercase">
                            {{ $page.props.auth.user.is_pimpinan ? 'Pimpinan Lapas' : 'Admin Lapas' }}
                        </p>
                    </div>
                    
                    <Link :href="route('profile.edit')" class="relative group cursor-pointer" title="Edit Profil">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold group-hover:ring-4 group-hover:ring-blue-100 transition-all">
                            {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                        </div>
                    </Link>
                </div>
                </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8">
                
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-4 md:p-8">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold">Daftar Tunggu Verifikasi</h3>
                        <p class="text-sm text-gray-500">Permintaan pengeluaran barang yang membutuhkan persetujuan Anda</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] md:text-xs font-bold text-gray-400 uppercase border-b border-gray-50">
                                    <th class="pb-4 px-2">Tanggal</th>
                                    <th class="pb-4 px-2">Nama Barang</th>
                                    <th class="pb-4 px-2">Jumlah</th>
                                    <th class="pb-4 px-2">Tujuan/Keterangan</th>
                                    <th class="pb-4 px-2 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <tr v-for="tx in transactions" :key="tx.id" class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-2 text-gray-500 text-xs">{{ new Date(tx.created_at).toLocaleDateString('id-ID') }}</td>
                                    <td class="py-4 px-2 font-bold text-gray-900">{{ tx.product?.name }}</td>
                                    <td class="py-4 px-2 font-black text-red-600">-{{ tx.quantity }} <span class="text-[10px] font-normal text-gray-400 uppercase">{{ tx.product?.unit }}</span></td>
                                    <td class="py-4 px-2 text-gray-600">{{ tx.keterangan }}</td>
                                    <td class="py-4 px-2 text-right">
                                        <button @click="approve(tx.id)" class="inline-flex items-center justify-center px-4 py-2 bg-green-500 text-white text-xs font-bold rounded-lg shadow-lg shadow-green-500/20 hover:bg-green-600 transition">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Setujui
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="transactions.length === 0">
                                    <td colspan="5" class="py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <p class="text-gray-500 font-medium">Semua permintaan sudah diverifikasi.</p>
                                            <p class="text-xs text-gray-400 mt-1">Tidak ada data tertunda saat ini.</p>
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