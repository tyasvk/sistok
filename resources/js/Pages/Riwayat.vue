<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    transactions: {
        type: Array,
        default: () => []
    },
    filters: {
        type: Object,
        default: () => ({})
    }
});

// State untuk Filter
const filterBulan = ref(props.filters?.bulan || '');
const filterTahun = ref(props.filters?.tahun || new Date().getFullYear());

// State Pop-up Konfirmasi Persetujuan
const isConfirmModalOpen = ref(false);
const selectedLog = ref(null);
const isProcessing = ref(false);

// State Pop-up Sukses
const isSuccessModalOpen = ref(false);
const successTitle = ref('Berhasil!');
const successMessage = ref('');

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

// Memicu pembukaan Pop-up Konfirmasi
const approveTransaction = (log) => {
    selectedLog.value = log;
    isConfirmModalOpen.value = true;
};

// Menjalankan aksi persetujuan
const confirmApprove = () => {
    if (!selectedLog.value) return;
    
    isProcessing.value = true;
    router.patch(route('pimpinan.approve', selectedLog.value.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            const productName = selectedLog.value.product ? selectedLog.value.product.name : 'Barang Dihapus';
            const quantity = selectedLog.value.quantity;
            const unit = selectedLog.value.product?.unit || 'Pcs';
            
            isConfirmModalOpen.value = false;
            isProcessing.value = false;
            
            successTitle.value = 'Verifikasi Berhasil!';
            successMessage.value = `Pengeluaran <strong class="text-gray-900 font-extrabold">${quantity} ${unit}</strong> untuk barang <strong class="text-gray-900 font-extrabold">"${productName}"</strong> telah resmi disetujui pimpinan.`;
            isSuccessModalOpen.value = true;
            
            selectedLog.value = null;
        },
        onError: () => {
            isProcessing.value = false;
        }
    });
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
            <div class="bg-white rounded-[24px] md:rounded-[32px] border border-gray-100 shadow-sm p-4 md:p-8 print:shadow-none print:border-none print:rounded-none">
                
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-5 mb-6 md:mb-8 print:hidden">
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

                <div class="hidden print:block text-center mb-8 border-b-2 border-black pb-4">
                    <h2 class="text-2xl font-bold uppercase">Laporan Mutasi Barang Gudang</h2>
                    <p class="text-lg">LAPAS KELAS 1 PALEMBANG</p>
                    <p class="text-sm mt-1">Bulan: {{ filterBulan ? bulanList.find(b => b.value === filterBulan).text : 'Semua Bulan' }} | Tahun: {{ filterTahun }}</p>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-50 print:border-none print:overflow-visible">
                    <table class="w-full text-left print:border-collapse min-w-[700px]">
                        <thead>
                            <tr class="text-[10px] md:text-xs font-bold text-gray-400 uppercase border-b border-gray-100 bg-gray-50/50 print:bg-transparent print:text-black print:border-black print:border-b-2">
                                <th class="py-3 px-4 print:border print:border-black print:p-2">Waktu Transaksi</th>
                                <th class="py-3 px-4 print:border print:border-black print:p-2">Nama Barang</th>
                                <th class="py-3 px-4 print:border print:border-black print:p-2">Tipe</th>
                                <th class="py-3 px-4 print:border print:border-black print:p-2">Jumlah</th>
                                <th class="py-3 px-4 print:border print:border-black print:p-2">Tujuan / Keterangan</th>
                                <th class="py-3 px-4 print:border print:border-black print:p-2">Status / Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs md:text-sm">
                            <tr v-for="log in transactions" :key="log.id" class="border-b border-gray-50 hover:bg-gray-50 transition-colors print:border-black print:break-inside-avoid">
                                <td class="py-3 md:py-4 px-4 text-gray-500 print:border print:border-black print:p-2 print:text-black">
                                    {{ formatDate(log.created_at) }}
                                </td>
                                
                                <td class="py-3 md:py-4 px-4 font-bold text-gray-900 print:border print:border-black print:p-2">
                                    {{ log.product ? log.product.name : 'Barang Dihapus' }}
                                </td>
                                
                                <td class="py-3 md:py-4 px-4 print:border print:border-black print:p-2">
                                    <span v-if="log.type === 'masuk'" class="px-2.5 py-1 bg-blue-50 text-blue-600 text-[10px] md:text-xs font-bold rounded-full print:bg-transparent print:text-black print:p-0 print:font-bold border border-blue-100 print:border-none">MASUK</span>
                                    <span v-else class="px-2.5 py-1 bg-red-50 text-red-600 text-[10px] md:text-xs font-bold rounded-full print:bg-transparent print:text-black print:p-0 print:font-bold border border-red-100 print:border-none">KELUAR</span>
                                </td>
                                
                                <td class="py-3 md:py-4 px-4 font-black text-gray-900 print:border print:border-black print:p-2">
                                    {{ log.quantity }} <span class="text-[10px] text-gray-400 font-normal uppercase print:hidden">{{ log.product?.unit || '' }}</span>
                                </td>
                                
                                <td class="py-3 md:py-4 px-4 text-gray-600 print:border print:border-black print:p-2">
                                    {{ log.keterangan || '-' }}
                                </td>
                                
                                <td class="py-3 md:py-4 px-4 print:border print:border-black print:p-2">
                                    <span v-if="log.is_verified" class="px-2.5 py-1 bg-green-50 text-green-600 text-[10px] md:text-xs font-bold rounded-full print:bg-transparent print:text-black print:p-0 print:font-bold border border-green-100 print:border-none">
                                        SUDAH DIVERIFIKASI
                                    </span>
                                    <div v-else>
                                        <button 
                                            v-if="$page.props.auth?.user?.is_pimpinan"
                                            @click="approveTransaction(log)"
                                            class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-[10px] md:text-xs font-bold rounded-lg shadow-md hover:bg-emerald-700 hover:shadow-lg transition-all print:hidden active:scale-95"
                                        >
                                            <svg class="w-3 h-3 md:w-3.5 md:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            Setujui
                                        </button>
                                        <span v-else class="inline-flex items-center px-2.5 py-1 bg-orange-50 text-orange-600 text-[10px] md:text-xs font-bold rounded-full print:bg-transparent print:text-black print:p-0 print:font-bold border border-orange-100 print:border-none">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-orange-500 rounded-full animate-ping print:hidden"></span>
                                            MENUNGGU
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="transactions.length === 0">
                                <td colspan="6" class="py-12 text-center text-gray-400 font-medium print:border print:border-black">
                                    <div class="flex flex-col items-center justify-center space-y-2 print:block">
                                        <svg class="w-10 h-10 text-gray-300 print:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <span>Tidak ada riwayat transaksi pada periode ini.</span>
                                    </div>
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

            </div>
        </div>

        <Transition name="fade-scale">
            <div v-if="isConfirmModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 md:p-6 print:hidden">
                <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="isConfirmModalOpen = false"></div>
                
                <div class="relative bg-white w-full max-w-md rounded-[28px] md:rounded-[32px] p-6 md:p-8 text-center shadow-2xl transition-all duration-300 transform">
                    <div class="mx-auto w-16 h-16 md:w-20 md:h-20 bg-amber-50 rounded-full flex items-center justify-center mb-5 md:mb-6 border-4 border-amber-100 shadow-inner">
                        <svg class="w-8 h-8 md:w-10 md:h-10 text-amber-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>

                    <h4 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight">Konfirmasi Verifikasi</h4>
                    <p class="text-gray-500 mt-2 md:mt-3 mb-6 md:mb-8 leading-relaxed text-sm md:text-base">
                        Apakah Anda yakin ingin menyetujui pengeluaran 
                        <strong class="text-gray-900 font-extrabold">{{ selectedLog?.quantity }}</strong> item untuk 
                        <strong class="text-gray-900 font-extrabold">"{{ selectedLog?.product ? selectedLog.product.name : 'Barang Dihapus' }}"</strong>?
                    </p>

                    <div class="flex gap-2.5 md:gap-3">
                        <button @click="isConfirmModalOpen = false" class="flex-1 py-3 md:py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl md:rounded-2xl font-bold text-sm transition-colors outline-none">
                            Batal
                        </button>
                        <button @click="confirmApprove" :disabled="isProcessing" class="flex-1 py-3 md:py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl md:rounded-2xl font-bold text-sm shadow-lg shadow-emerald-600/20 transition-colors outline-none flex items-center justify-center">
                            <svg v-if="isProcessing" class="animate-spin h-4 w-4 text-white mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ isProcessing ? 'Memproses...' : 'Ya, Setujui' }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>

        <Transition name="fade-scale">
            <div v-if="isSuccessModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-6 print:hidden">
                <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="isSuccessModalOpen = false"></div>
                
                <div class="relative bg-white w-full max-w-sm rounded-[28px] md:rounded-[32px] p-6 md:p-8 text-center shadow-2xl transition-all duration-300 transform">
                    <div class="mx-auto w-20 h-20 md:w-24 md:h-24 bg-green-50 rounded-full flex items-center justify-center mb-5 md:mb-6 border-4 border-green-100 shadow-inner">
                        <svg class="w-10 h-10 md:w-12 md:h-12 text-green-500 animate-pulse-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>

                    <h4 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight">{{ successTitle }}</h4>
                    <p class="text-gray-500 mt-2 md:mt-2.5 mb-6 md:mb-8 leading-relaxed text-sm md:text-base" v-html="successMessage"></p>

                    <button @click="isSuccessModalOpen = false" class="w-full py-3.5 md:py-4 bg-blue-600 text-white rounded-xl md:rounded-2xl font-bold text-sm md:text-base shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-colors focus:ring-4 focus:ring-blue-300 outline-none">
                        Selesai
                    </button>
                </div>
            </div>
        </Transition>
    </AuthenticatedLayout>
</template>

<style scoped>
.fade-scale-enter-active,
.fade-scale-leave-active { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.fade-scale-enter-from,
.fade-scale-leave-to { opacity: 0; transform: scale(0.9) translateY(10px); }
@keyframes pulse-slow { 0%, 100% { transform: scale(1); opacity: 1; } 50% { transform: scale(1.05); opacity: 0.8; } }
.animate-pulse-slow { animation: pulse-slow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }

@media print {
    @page { margin: 1cm; size: A4 portrait; }
    body { 
        -webkit-print-color-adjust: exact; 
        print-color-adjust: exact; 
        background-color: white !important;
    }
    
    /* These styles might need adjusting depending on how your AuthenticatedLayout handles overflow */
    :deep(.flex-1.overflow-y-auto) {
        overflow: visible !important;
        height: auto !important;
    }
}
</style>