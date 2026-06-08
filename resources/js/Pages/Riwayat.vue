<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Gunakan Object untuk transactions karena menggunakan Paginate
const props = defineProps({
    transactions: {
        type: Object,
        default: () => ({ data: [], links: [] })
    },
    filters: {
        type: Object,
        default: () => ({})
    }
});

// State untuk Filter
const filterBulan = ref(props.filters?.bulan || '');
const filterTahun = ref(props.filters?.tahun || new Date().getFullYear());

// Array nama bulan
const bulanList = [
    { value: '01', text: 'Januari' }, { value: '02', text: 'Februari' }, { value: '03', text: 'Maret' },
    { value: '04', text: 'April' }, { value: '05', text: 'Mei' }, { value: '06', text: 'Juni' },
    { value: '07', text: 'Juli' }, { value: '08', text: 'Agustus' }, { value: '09', text: 'September' },
    { value: '10', text: 'Oktober' }, { value: '11', text: 'November' }, { value: '12', text: 'Desember' }
];

// Fungsi memicu filter
const applyFilter = () => {
    router.get(route('riwayat.index'), {
        bulan: filterBulan.value,
        tahun: filterTahun.value
    }, { preserveState: true, replace: true });
};

// Fungsi Cetak
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
        <div class="p-4 md:p-8 print:p-0 print:block">
            <div class="bg-transparent md:bg-white md:rounded-[32px] md:border md:border-gray-100 md:shadow-sm md:p-8 print:shadow-none print:border-none print:rounded-none print:bg-white">
                
                <!-- Header & Filter -->
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-5 mb-6 md:mb-8 print:hidden bg-white p-5 rounded-[24px] shadow-sm border border-gray-100 md:bg-transparent md:p-0 md:shadow-none md:border-none">
                    <div class="w-full lg:w-auto">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900">Log Data Masuk & Keluar</h3>
                        <p class="text-xs md:text-sm text-gray-500 mt-1">Pilih filter bulan dan tahun untuk melihat rekapan</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-2.5 sm:gap-3 w-full lg:w-auto">
                        <div class="flex flex-row gap-2 w-full sm:w-auto">
                            <select v-model="filterBulan" @change="applyFilter" class="flex-1 sm:flex-none w-full sm:w-auto bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-2.5 md:py-3 px-3 md:px-4 text-xs md:text-sm font-medium focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                <option value="">Semua Bulan</option>
                                <option v-for="bulan in bulanList" :key="bulan.value" :value="bulan.value">{{ bulan.text }}</option>
                            </select>
                            <select v-model="filterTahun" @change="applyFilter" class="flex-1 sm:flex-none w-full sm:w-auto bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-2.5 md:py-3 px-3 md:px-4 text-xs md:text-sm font-medium focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                                <option value="2028">2028</option>
                            </select>
                        </div>
                        
                        <button @click="exportPDF" class="w-full sm:w-auto flex items-center justify-center px-5 md:px-6 py-2.5 md:py-3 bg-red-500 text-white text-xs md:text-sm font-bold rounded-xl shadow-lg shadow-red-500/20 hover:bg-red-600 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Laporan
                        </button>
                    </div>
                </div>

                <!-- Tampilan Mobile (Kartu) -->
                <div class="block md:hidden print:hidden">
                    <div v-if="transactions.data.length > 0" class="flex flex-col gap-4 pb-10">
                        <!-- Menggunakan transactions.data -->
                        <div v-for="log in transactions.data" :key="'mob-'+log.id" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden">
                            
                            <div class="absolute left-0 top-0 bottom-0 w-1.5" :class="log.type === 'masuk' ? 'bg-blue-500' : 'bg-red-500'"></div>

                            <div class="flex justify-between items-start mb-3 pl-2">
                                <div>
                                    <h3 class="font-black text-gray-900 text-base leading-tight">{{ log.product ? log.product.name : 'Barang Dihapus' }}</h3>
                                    <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-wider">{{ formatDate(log.created_at) }}</p>
                                </div>
                                <div>
                                    <span v-if="log.type === 'masuk'" class="px-2 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-md border border-blue-100">MASUK</span>
                                    <span v-else class="px-2 py-1 bg-red-50 text-red-600 text-[10px] font-bold rounded-md border border-red-100">KELUAR</span>
                                </div>
                            </div>

                            <div class="space-y-2 text-sm pl-2 mt-3">
                                <div class="flex justify-between items-center py-1 border-b border-gray-50">
                                    <span class="text-gray-500 font-medium">Jumlah</span>
                                    <span class="font-extrabold text-gray-900">{{ log.quantity }} <span class="text-xs font-normal uppercase text-gray-500">{{ log.product?.unit || '' }}</span></span>
                                </div>
                                <div class="flex justify-between items-center py-1 border-b border-gray-50">
                                    <span class="text-gray-500 font-medium">Pemohon</span>
                                    <span class="font-bold text-gray-800">{{ log.user?.name || 'Sistem' }}</span>
                                </div>
                                <div class="flex justify-between items-start py-1">
                                    <span class="text-gray-500 font-medium pt-0.5">Tujuan/Ket</span>
                                    <span class="font-bold text-gray-800 text-right max-w-[65%] leading-relaxed whitespace-normal break-words">{{ log.keterangan || '-' }}</span>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-gray-100 pl-2">
                                <div v-if="log.type === 'masuk'" class="flex items-center justify-center py-2.5 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-100">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    STOK DITAMBAHKAN
                                </div>
                                <div v-else>
                                    <div v-if="log.is_verified" class="flex items-center justify-center py-2.5 bg-green-50 text-green-700 text-xs font-bold rounded-lg border border-green-100">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        DISETUJUI
                                    </div>
                                    <div v-else>
                                        <Link v-if="$page.props.auth?.user?.is_admin || $page.props.auth?.user?.is_pimpinan" :href="route('pimpinan.verifikasi')" class="flex items-center justify-center w-full py-2.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg text-xs font-bold transition-colors">
                                            Tinjau Permintaan <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </Link>
                                        <div v-else class="text-center py-2.5 bg-orange-50 text-orange-600 text-xs font-bold rounded-lg border border-orange-100">
                                            MENUNGGU VERIFIKASI
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div v-else class="text-center py-16 bg-white rounded-2xl border border-gray-100 shadow-sm">
                        <p class="text-gray-500 text-sm">Tidak ada riwayat transaksi.</p>
                    </div>
                </div>

                <!-- Tampilan Desktop & Print (Tabel) -->
                <div class="hidden md:block print:block w-full">
                    
                    <div class="hidden print:block text-center mb-8 border-b-2 border-black pb-4">
                        <h2 class="text-2xl font-bold uppercase">Laporan Mutasi Barang Gudang</h2>
                        <p class="text-lg">LAPAS KELAS 1 PALEMBANG</p>
                        <p class="text-sm mt-1">Bulan: {{ filterBulan ? bulanList.find(b => b.value === filterBulan).text : 'Semua Bulan' }} | Tahun: {{ filterTahun }}</p>
                    </div>

                    <div class="rounded-xl border border-gray-100 overflow-x-auto print:border-none print:overflow-visible">
                        <table class="w-full text-left print:border-collapse min-w-[800px]">
                            <thead>
                                <tr class="text-[11px] lg:text-xs font-bold text-gray-400 uppercase bg-gray-50 print:bg-transparent print:text-black print:border-black print:border-b-2">
                                    <th class="py-3.5 px-4 print:border print:border-black print:p-2">Waktu Transaksi</th>
                                    <th class="py-3.5 px-4 print:border print:border-black print:p-2">Nama Barang</th>
                                    <th class="py-3.5 px-4 print:border print:border-black print:p-2 text-center">Tipe</th>
                                    <th class="py-3.5 px-4 print:border print:border-black print:p-2 text-center">Jumlah</th>
                                    <th class="py-3.5 px-4 print:border print:border-black print:p-2">Pemohon</th>
                                    <th class="py-3.5 px-4 print:border print:border-black print:p-2">Tujuan / Ket</th>
                                    <th class="py-3.5 px-4 print:border print:border-black print:p-2 text-right">Status / Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <!-- Menggunakan transactions.data -->
                                <tr v-for="log in transactions.data" :key="log.id" class="border-t border-gray-100 hover:bg-gray-50 transition-colors print:border-black print:break-inside-avoid">
                                    <td class="py-4 px-4 text-gray-500 whitespace-nowrap print:border print:border-black print:p-2 print:text-black">
                                        {{ formatDate(log.created_at) }}
                                    </td>
                                    <td class="py-4 px-4 font-bold text-gray-900 print:border print:border-black print:p-2 break-words">
                                        {{ log.product ? log.product.name : 'Barang Dihapus' }}
                                    </td>
                                    <td class="py-4 px-4 text-center print:border print:border-black print:p-2">
                                        <span v-if="log.type === 'masuk'" class="px-2.5 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-md border border-blue-100 print:bg-transparent print:text-black print:p-0 print:border-none">MASUK</span>
                                        <span v-else class="px-2.5 py-1 bg-red-50 text-red-600 text-[10px] font-bold rounded-md border border-red-100 print:bg-transparent print:text-black print:p-0 print:border-none">KELUAR</span>
                                    </td>
                                    <td class="py-4 px-4 text-center font-black text-gray-900 whitespace-nowrap print:border print:border-black print:p-2">
                                        {{ log.quantity }} <span class="text-[10px] text-gray-400 font-normal uppercase print:hidden">{{ log.product?.unit || '' }}</span>
                                    </td>
                                    <td class="py-4 px-4 font-bold text-gray-800 print:border print:border-black print:p-2 break-words">
                                        {{ log.user?.name || 'Sistem' }}
                                    </td>
                                    <td class="py-4 px-4 text-gray-700 whitespace-normal break-words leading-relaxed print:border print:border-black print:p-2 max-w-[250px]">
                                        {{ log.keterangan || '-' }}
                                    </td>
                                    <td class="py-4 px-4 text-right print:border print:border-black print:p-2 print:text-center">
                                        
                                        <!-- BARANG MASUK -->
                                        <div v-if="log.type === 'masuk'">
                                            <span class="inline-flex items-center px-2.5 py-1.5 bg-blue-50 border border-blue-100 text-blue-700 text-[10px] font-bold rounded-md whitespace-nowrap print:bg-transparent print:border-none print:text-black print:p-0">
                                                <svg class="w-3.5 h-3.5 mr-1 print:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                STOK DITAMBAHKAN
                                            </span>
                                        </div>
                                        
                                        <!-- BARANG KELUAR -->
                                        <div v-else>
                                            <span v-if="log.is_verified" class="inline-flex items-center px-2.5 py-1.5 bg-green-50 border border-green-100 text-green-700 text-[10px] font-bold rounded-md whitespace-nowrap print:bg-transparent print:border-none print:text-black print:p-0">
                                                <svg class="w-3.5 h-3.5 mr-1 print:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                DISETUJUI
                                            </span>
                                            <div v-else>
                                                <Link v-if="$page.props.auth?.user?.is_admin || $page.props.auth?.user?.is_pimpinan" :href="route('pimpinan.verifikasi')" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-lg border border-blue-100 hover:bg-blue-600 hover:text-white transition-all whitespace-nowrap print:hidden">
                                                    Tinjau <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                </Link>
                                                <span v-else class="inline-flex items-center px-2.5 py-1 bg-orange-50 text-orange-600 text-[10px] font-bold rounded-md border border-orange-100 whitespace-nowrap print:bg-transparent print:border-none print:text-black print:p-0">
                                                    MENUNGGU
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="transactions.data.length === 0">
                                    <td colspan="7" class="py-12 text-center text-gray-400 font-medium print:border print:border-black">
                                        Tidak ada riwayat transaksi pada periode ini.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="hidden print:flex justify-end mt-16 text-center">
                        <div>
                            <p class="mb-24">Palembang, ........................ 2026</p>
                            <p class="font-bold underline uppercase">Kepala Lapas</p>
                        </div>
                    </div>

                    <!-- PAGINATION (Tombol Navigasi Halaman) -->
                    <div v-if="transactions.links && transactions.links.length > 3" class="flex justify-center items-center mt-8 pb-4 print:hidden">
                        <div class="flex flex-wrap gap-1 shadow-sm rounded-lg overflow-hidden border border-gray-200">
                            <template v-for="(link, index) in transactions.links" :key="index">
                                <!-- Link non-aktif -->
                                <div v-if="link.url === null" class="px-3.5 py-2 text-sm font-medium text-gray-400 bg-gray-50 border-r border-gray-200 last:border-0" v-html="link.label"></div>
                                <!-- Link Aktif -->
                                <Link v-else :href="link.url" 
                                      class="px-3.5 py-2 text-sm font-semibold border-r border-gray-200 last:border-0 transition-colors" 
                                      :class="link.active ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-blue-50 hover:text-blue-600'" 
                                      v-html="link.label">
                                </Link>
                            </template>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@media print {
    @page { margin: 1cm; size: A4 landscape; }
    body { 
        -webkit-print-color-adjust: exact; 
        print-color-adjust: exact; 
        background-color: white !important;
    }
    
    :deep(.flex-1.overflow-y-auto) {
        overflow: visible !important;
        height: auto !important;
    }
}
</style>