<script setup>
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    transactions: {
        type: Array,
        default: () => []
    }
});

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

    <AuthenticatedLayout>
        <div class="p-4 md:p-8">
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
        </div>
    </AuthenticatedLayout>
</template>