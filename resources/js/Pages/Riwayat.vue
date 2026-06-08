<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    transactions: Array,
    filters: Object
});

// State untuk Filter
const filterBulan = ref(props.filters.bulan || '');
const filterTahun = ref(props.filters.tahun || new Date().getFullYear());

// Array nama bulan
const bulanList = [
    { value: '01', text: 'Januari' }, { value: '02', text: 'Februari' }, { value: '03', text: 'Maret' },
    { value: '04', text: 'April' }, { value: '05', text: 'Mei' }, { value: '06', text: 'Juni' },
    { value: '07', text: 'Juli' }, { value: '08', text: 'Agustus' }, { value: '09', text: 'September' },
    { value: '10', text: 'Oktober' }, { value: '11', text: 'November' }, { value: '12', text: 'Desember' }
];

// Fungsi memicu filter ke backend
const applyFilter = () => {
    router.get(route('riwayat'), { // pastikan nama route ini sesuai ('riwayat' atau 'riwayat.index')
        bulan: filterBulan.value,
        tahun: filterTahun.value
    }, { preserveState: true, replace: true });
};

// Fungsi Cetak/Ekspor PDF
const exportPDF = () => {
    window.print();
};

// Format Tanggal
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
};
</script>

<template>
    <Head title="Riwayat Transaksi - Sistok Lapas" />

    <AuthenticatedLayout>
        
        <div class="p-4 md:p-8 print:p-0">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-4 md:p-8 print:shadow-none print:border-none print:rounded-none">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8 print:hidden">
                    <div>
                        <h3 class="text-xl font-bold">Log Data Masuk & Keluar</h3>
                        <p class="text-sm text-gray-500">Pilih filter bulan dan tahun untuk melihat rekapan</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-end gap-3 w-full md:w-auto">
                        <div class="flex gap-2 w-full sm:w-auto">
                            <select v-model="filterBulan" @change="applyFilter" class="w-full sm:w-auto bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="">Semua Bulan</option>
                                <option v-for="bulan in bulanList" :key="bulan.value" :value="bulan.value">{{ bulan.text }}</option>
                            </select>
                            <select v-model="filterTahun" @change="applyFilter" class="w-full sm:w-auto bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                                <option value="2028">2028</option>
                            </select>
                        </div>
                        
                        <button @click="exportPDF" class="w-full sm:w-auto flex items-center justify-center px-6 py-2.5 bg-red-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-red-500/20 hover:bg-red-600 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Laporan PDF
                        </button>
                    </div>
                </div>

                <div class="hidden print:block text-center mb-8 border-b-2 border-black pb-4">
                    <h2 class="text-2xl font-bold uppercase">Laporan Mutasi Barang Gudang</h2>
                    <p class="text-lg">LAPAS KELAS 1 PALEMBANG</p>
                    <p class="text-sm">Bulan: {{ filterBulan ? bulanList.find(b => b.value === filterBulan).text : 'Semua Bulan' }} | Tahun: {{ filterTahun }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left print:border-collapse">
                        <thead>
                            <tr class="text-[10px] md:text-xs font-bold text-gray-400 uppercase border-b border-gray-100 print:text-black print:border-black print:border-b-2">
                                <th class="pb-4 px-2 print:border print:border-black print:p-2">Waktu Transaksi</th>
                                <th class="pb-4 px-2 print:border print:border-black print:p-2">Nama Barang</th>
                                <th class="pb-4 px-2 print:border print:border-black print:p-2">Tipe</th>
                                <th class="pb-4 px-2 print:border print:border-black print:p-2">Jumlah</th>
                                <th class="pb-4 px-2 print:border print:border-black print:p-2">Keterangan / Tujuan</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr v-for="log in transactions" :key="log.id" class="border-b border-gray-50 hover:bg-gray-50 transition-colors print:border-black print:break-inside-avoid">
                                <td class="py-4 px-2 text-gray-500 text-xs print:border print:border-black print:p-2 print:text-black">{{ formatDate(log.created_at) }}</td>
                                <td class="py-4 px-2 font-bold text-gray-900 print:border print:border-black print:p-2">{{ log.product ? log.product.name : 'Barang Dihapus' }}</td>
                                <td class="py-4 px-2 print:border print:border-black print:p-2">
                                    <span v-if="log.type === 'masuk'" class="px-2 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full print:bg-transparent print:text-black print:p-0">MASUK</span>
                                    <span v-else class="px-2 py-1 bg-orange-50 text-orange-600 text-[10px] font-bold rounded-full print:bg-transparent print:text-black print:p-0">MENUNGGU VERIFIKASI</span>
                                </td>
                                <td class="py-4 px-2 font-black text-gray-900 print:border print:border-black print:p-2">{{ log.quantity }}</td>
                                <td class="py-4 px-2 text-gray-600 print:border print:border-black print:p-2">{{ log.keterangan || '-' }}</td>
                            </tr>
                            <tr v-if="transactions?.length === 0">
                                <td colspan="5" class="py-10 text-center text-gray-400 font-medium print:border print:border-black">Tidak ada riwayat transaksi pada periode ini.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="hidden print:flex justify-end mt-16 text-center">
                    <div>
                        <p class="mb-20">Palembang, ........................ 2026</p>
                        <p class="font-bold">Kepala Gudang Lapas</p>
                    </div>
                </div>

            </div>
        </div>

    </AuthenticatedLayout>
</template>

<style>
/* Modifikasi CSS Print agar Sidebar dari AuthenticatedLayout tidak ikut tercetak */
@media print {
    @page { margin: 1cm; size: A4 portrait; }
    body { 
        -webkit-print-color-adjust: exact; 
        print-color-adjust: exact; 
        background-color: white !important;
    }
    
    /* Menyembunyikan elemen navigasi (Sidebar & Topbar) dari layout utama */
    aside, header, nav { 
        display: none !important; 
    }

    /* Mengembalikan area konten utama agar full dan bisa page-break */
    main {
        overflow: visible !important;
        height: auto !important;
        background-color: white !important;
    }
}
</style>