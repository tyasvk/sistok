<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    transactions: {
        type: Array,
        default: () => []
    }
});

// State untuk Modal & Transaksi Terpilih
const isApproveModalOpen = ref(false);
const isRejectModalOpen = ref(false);
const selectedTx = ref(null);
const processing = ref(false);

const openApproveModal = (tx) => {
    selectedTx.value = tx;
    isApproveModalOpen.value = true;
};

const openRejectModal = (tx) => {
    selectedTx.value = tx;
    isRejectModalOpen.value = true;
};

const closeModals = () => {
    isApproveModalOpen.value = false;
    isRejectModalOpen.value = false;
    // Beri jeda sedikit agar animasi keluar selesai sebelum mengosongkan data
    setTimeout(() => selectedTx.value = null, 300); 
};

// Fungsi Eksekusi Setuju
const confirmApprove = () => {
    if (!selectedTx.value) return;
    processing.value = true;
    router.patch(route('pimpinan.approve', selectedTx.value.id), {}, {
        preserveScroll: true,
        onFinish: () => {
            processing.value = false;
            closeModals();
        }
    });
};

// Fungsi Eksekusi Tolak
const confirmReject = () => {
    if (!selectedTx.value) return;
    processing.value = true;
    router.delete(route('pimpinan.reject', selectedTx.value.id), {
        preserveScroll: true,
        onFinish: () => {
            processing.value = false;
            closeModals();
        }
    });
};
</script>

<template>
    <Head title="Verifikasi Pimpinan - Sistok Lapas" />

    <AuthenticatedLayout>
        <div class="p-4 md:p-8 max-w-5xl mx-auto h-full">
            
            <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-2">
                <div>
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">Verifikasi Barang</h2>
                    <p class="text-sm text-gray-500 mt-1">Tinjau dan setujui permintaan pengeluaran barang</p>
                </div>
            </div>

            <div v-if="transactions.length > 0" class="space-y-4">
                <div v-for="tx in transactions" :key="tx.id" class="bg-white p-5 md:p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-5 transition hover:shadow-md">
                    
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">{{ tx.product?.name }}</h4>
                            <div class="mt-1 space-y-1">
                                <p class="text-sm text-gray-600 flex items-center">
                                    <span class="w-16 text-gray-400 font-medium text-xs uppercase">Jumlah</span>
                                    <span class="font-extrabold text-gray-900">: {{ tx.quantity }} <span class="text-xs font-normal uppercase">{{ tx.product?.unit }}</span></span>
                                </p>
                                <p class="text-sm text-gray-600 flex items-start">
                                    <span class="w-16 text-gray-400 font-medium text-xs uppercase pt-0.5">Tujuan</span>
                                    <span class="font-bold text-gray-800 leading-tight">: {{ tx.keterangan }}</span>
                                </p>
                            </div>
                            <p class="text-[10px] font-bold text-gray-400 mt-3 uppercase tracking-wider">{{ new Date(tx.created_at).toLocaleString('id-ID') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto pt-3 md:pt-0 border-t md:border-0 border-gray-50 mt-2 md:mt-0">
                        <button @click="openRejectModal(tx)" class="flex-1 md:flex-none px-5 py-3.5 bg-red-50 text-red-600 text-sm font-bold rounded-xl hover:bg-red-500 hover:text-white transition-colors">
                            Tolak
                        </button>
                        <button @click="openApproveModal(tx)" class="flex-1 md:flex-none px-5 py-3.5 bg-green-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-green-500/25 hover:bg-green-600 transition-colors">
                            Setujui
                        </button>
                    </div>

                </div>
            </div>

            <div v-else class="text-center py-20 bg-white rounded-3xl border border-gray-100 shadow-sm mt-4">
                <div class="mx-auto w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-5">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-black text-gray-900">Semua Beres!</h3>
                <p class="text-gray-500 mt-2 text-sm">Tidak ada permintaan barang yang menunggu verifikasi saat ini.</p>
            </div>
        </div>

        <Transition name="fade-scale">
            <div v-if="isApproveModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="!processing && closeModals()"></div>
                <div class="relative bg-white w-full max-w-sm rounded-[2rem] p-6 md:p-8 text-center shadow-2xl">
                    <div class="mx-auto w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-5">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Setujui Permintaan?</h3>
                    <p class="text-sm text-gray-500 mb-8 leading-relaxed">
                        Anda akan menyetujui pengeluaran <strong class="text-gray-900 font-black">{{ selectedTx?.quantity }} {{ selectedTx?.product?.unit }}</strong> untuk barang <strong class="text-gray-900 font-bold">{{ selectedTx?.product?.name }}</strong>.
                    </p>
                    <div class="flex gap-3">
                        <button @click="closeModals" :disabled="processing" class="flex-1 py-3.5 bg-gray-50 text-gray-600 rounded-xl font-bold hover:bg-gray-100 transition">Batal</button>
                        <button @click="confirmApprove" :disabled="processing" class="flex-1 py-3.5 bg-green-500 text-white rounded-xl font-bold shadow-lg shadow-green-500/30 hover:bg-green-600 transition flex justify-center items-center">
                            <span v-if="!processing">Ya, Setujui</span>
                            <svg v-else class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </Transition>

        <Transition name="fade-scale">
            <div v-if="isRejectModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="!processing && closeModals()"></div>
                <div class="relative bg-white w-full max-w-sm rounded-[2rem] p-6 md:p-8 text-center shadow-2xl">
                    <div class="mx-auto w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mb-5">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Tolak Permintaan?</h3>
                    <p class="text-sm text-gray-500 mb-8 leading-relaxed">
                        Permintaan <strong class="text-gray-900 font-bold">{{ selectedTx?.product?.name }}</strong> akan ditolak dan stok akan otomatis dikembalikan ke sistem.
                    </p>
                    <div class="flex gap-3">
                        <button @click="closeModals" :disabled="processing" class="flex-1 py-3.5 bg-gray-50 text-gray-600 rounded-xl font-bold hover:bg-gray-100 transition">Batal</button>
                        <button @click="confirmReject" :disabled="processing" class="flex-1 py-3.5 bg-red-500 text-white rounded-xl font-bold shadow-lg shadow-red-500/30 hover:bg-red-600 transition flex justify-center items-center">
                            <span v-if="!processing">Ya, Tolak</span>
                            <svg v-else class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </AuthenticatedLayout>
</template>

<style scoped>
.fade-scale-enter-active,
.fade-scale-leave-active { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.fade-scale-enter-from,
.fade-scale-leave-to { opacity: 0; transform: scale(0.95) translateY(15px); }
</style>