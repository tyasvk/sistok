<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    products: {
        type: Array,
        default: () => []
    },
    stats: {
        type: Object,
        default: () => ({
            total_item: 0,
            stok_aman: 0,
            menipis: 0,
            habis: 0
        })
    },
    unverified_count: {
        type: Number,
        default: 0
    }
});

// State untuk sidebar mobile
const isSidebarOpen = ref(false);

// State untuk Modal Pop-up Input (Masuk & Keluar)
const isModalOpen = ref(false);
const isKeluarModalOpen = ref(false);
const activeTab = ref('tambah'); // 'tambah' atau 'baru'

// State untuk Pop-up Sukses di Tengah
const isSuccessModalOpen = ref(false);
const successTitle = ref('Berhasil!');
const successMessage = ref('');

// Form untuk Barang Baru (Masuk)
const formBaru = useForm({
    name: '',
    category: '',
    unit: 'Pcs',
    stock: '',
});

// Form untuk Tambah Stok (Masuk)
const formTambah = useForm({
    product_id: '',
    jumlah: '',
});

// Form untuk Barang Keluar
const formKeluar = useForm({
    product_id: '',
    jumlah: '',
    tujuan: '',
    verifikasi_pimpinan: false,
});

// FUNGSI 1: Menyimpan Barang Baru
const submitBaru = () => {
    formBaru.post(route('barang.store'), {
        preserveScroll: true,
        onSuccess: () => {
            isModalOpen.value = false;
            successTitle.value = 'Barang Didaftarkan!';
            successMessage.value = `Barang baru <strong class="text-gray-900 font-extrabold">"${formBaru.name}"</strong> berhasil ditambahkan ke database dengan stok awal ${formBaru.stock} ${formBaru.unit}.`;
            isSuccessModalOpen.value = true;
            formBaru.reset();
        },
    });
};

// FUNGSI 2: Menambah Stok Item Eksisting
const submitTambah = () => {
    const selectedProduct = props.products.find(p => p.id === formTambah.product_id);
    const productName = selectedProduct ? selectedProduct.name : '';
    const productUnit = selectedProduct ? selectedProduct.unit : 'Unit';

    formTambah.post(route('barang.tambah-stok'), {
        preserveScroll: true,
        onSuccess: () => {
            isModalOpen.value = false;
            successTitle.value = 'Stok Diperbarui!';
            successMessage.value = `Stok barang <strong class="text-gray-900 font-extrabold">"${productName}"</strong> berhasil ditambah sebanyak ${formTambah.jumlah} ${productUnit}.`;
            isSuccessModalOpen.value = true;
            formTambah.reset();
        },
    });
};

// FUNGSI 3: Mengeluarkan Barang
const submitKeluar = () => {
    const selectedProduct = props.products.find(p => p.id === formKeluar.product_id);
    const productName = selectedProduct ? selectedProduct.name : '';
    const productUnit = selectedProduct ? selectedProduct.unit : 'Unit';

    formKeluar.post(route('barang.keluar'), {
        preserveScroll: true,
        onSuccess: () => {
            isKeluarModalOpen.value = false;
            successTitle.value = 'Barang Dikeluarkan!';
            successMessage.value = `Barang <strong class="text-gray-900 font-extrabold">"${productName}"</strong> sejumlah ${formKeluar.jumlah} ${productUnit} telah berhasil dikeluarkan untuk tujuan: <span class="text-gray-800 font-semibold">${formKeluar.tujuan}</span>.`;
            isSuccessModalOpen.value = true;
            formKeluar.reset();
        },
    });
};

const closeSuccessModal = () => {
    isSuccessModalOpen.value = false;
};
</script>

<template>
    <Head title="Dashboard - Sistok Lapas" />

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
                <Link :href="route('dashboard')" class="flex items-center px-4 py-3 bg-blue-600 text-white rounded-xl font-medium shadow-lg shadow-blue-600/20">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Pantau Stok
                </Link>
                <Link :href="route('riwayat.index')" class="flex items-center px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-xl transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Riwayat Transaksi
                </Link>
                <Link v-if="$page.props.auth?.user?.is_pimpinan" :href="route('pimpinan.verifikasi')" class="flex items-center px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-xl transition-colors">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        Verifikasi Barang
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
                
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white p-4 md:p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase">Total Item</p>
                        <p class="text-2xl md:text-4xl font-black text-gray-900">{{ stats.total_item }}</p>
                    </div>
                    <div class="bg-white p-4 md:p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase">Stok Aman</p>
                        <p class="text-2xl md:text-4xl font-black text-blue-600">{{ stats.stok_aman }}</p>
                    </div>
                    <div class="bg-white p-4 md:p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase">Menipis</p>
                        <p class="text-2xl md:text-4xl font-black text-orange-500">{{ stats.menipis }}</p>
                    </div>
                    <div class="bg-white p-4 md:p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase">Habis</p>
                        <p class="text-2xl md:text-4xl font-black text-red-500">{{ stats.habis }}</p>
                    </div>
                </div>

                <div v-if="$page.props.auth?.user?.is_pimpinan && unverified_count > 0" 
                     class="mb-8 bg-red-50 border border-red-100 p-4 md:p-5 rounded-3xl flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm relative overflow-hidden">
                    
                    <div class="hidden md:block absolute left-0 top-0 bottom-0 w-1.5 bg-red-500"></div>

                    <div class="flex items-start md:items-center">
                        <div class="flex-shrink-0 bg-red-100 text-red-600 p-2.5 rounded-2xl mt-0.5 md:mt-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <h3 class="text-sm md:text-base font-bold text-red-900 tracking-tight">
                                Verifikasi Tertunda
                            </h3>
                            <p class="mt-0.5 text-xs md:text-sm text-red-700 font-medium">
                                Terdapat <span class="font-extrabold text-red-800">{{ unverified_count }}</span> permintaan barang keluar yang menunggu persetujuan Anda.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0 w-full md:w-auto mt-1 md:mt-0">
                        <Link :href="route('pimpinan.verifikasi')" 
                              class="flex items-center justify-center w-full md:w-auto px-5 py-3 md:py-2.5 bg-red-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-red-500/20 hover:bg-red-600 transition-colors focus:ring-4 focus:ring-red-200 outline-none">
                            Tinjau Permintaan
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                        </Link>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-4 md:p-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                        <div>
                            <h3 class="text-xl font-bold">Daftar Inventaris</h3>
                            <p class="text-sm text-gray-500">Data ketersediaan barang real-time</p>
                        </div>
                        
                        <div class="flex items-center gap-2 w-full md:w-auto">
                            <button @click="isModalOpen = true" class="flex-1 md:flex-none flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white text-xs md:text-sm font-bold rounded-xl shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Barang Masuk
                            </button>
                            <button @click="isKeluarModalOpen = true" class="flex-1 md:flex-none flex items-center justify-center px-4 py-2.5 bg-red-500 text-white text-xs md:text-sm font-bold rounded-xl shadow-lg shadow-red-500/20 hover:bg-red-600 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                Barang Keluar
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] md:text-xs font-bold text-gray-400 uppercase border-b border-gray-50">
                                    <th class="pb-4 px-2">Nama Barang</th>
                                    <th class="pb-4 px-2">Kategori</th>
                                    <th class="pb-4 px-2">Stok</th>
                                    <th class="pb-4 px-2">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <tr v-for="product in products" :key="product.id" class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-2 font-bold text-gray-900">{{ product.name }}</td>
                                    <td class="py-4 px-2 text-gray-500 text-xs">{{ product.category }}</td>
                                    <td class="py-4 px-2 font-black text-gray-900">{{ product.stock }} <span class="text-[10px] font-normal text-gray-400 uppercase">{{ product.unit }}</span></td>
                                    <td class="py-4 px-2">
                                        <span v-if="product.stock > 10" class="px-2 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full">AMAN</span>
                                        <span v-else-if="product.stock > 0" class="px-2 py-1 bg-orange-50 text-orange-600 text-[10px] font-bold rounded-full">MENIPIS</span>
                                        <span v-else class="px-2 py-1 bg-red-50 text-red-600 text-[10px] font-bold rounded-full">HABIS</span>
                                    </td>
                                </tr>
                                <tr v-if="products.length === 0">
                                    <td colspan="4" class="py-10 text-center text-gray-400 font-medium">Belum ada data barang tersedia.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-end md:items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity" @click="isModalOpen = false"></div>
        <div class="relative bg-white w-full md:w-[500px] max-h-[90vh] overflow-y-auto rounded-3xl shadow-2xl flex flex-col transition-all">
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-950">Input Barang Masuk</h3>
                <button @click="isModalOpen = false" class="p-2.5 text-gray-400 rounded-full bg-gray-50 hover:bg-gray-100"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="flex border-b border-gray-100 bg-gray-50">
                <button @click="activeTab = 'tambah'" class="flex-1 py-4 text-sm font-bold text-center border-b-2" :class="activeTab === 'tambah' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-500'">Tambah Stok Item</button>
                <button @click="activeTab = 'baru'" class="flex-1 py-4 text-sm font-bold text-center border-b-2" :class="activeTab === 'baru' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-500'">Buat Barang Baru</button>
            </div>
            <div class="p-6 md:p-8">
                <form v-if="activeTab === 'tambah'" @submit.prevent="submitTambah" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Pilih Item *</label>
                        <select v-model="formTambah.product_id" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 px-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none appearance-none">
                            <option value="" disabled>-- Pilih nama barang --</option>
                            <option v-for="product in products" :key="product.id" :value="product.id">{{ product.name }} (Stok: {{ product.stock }} {{ product.unit }})</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Jumlah Masuk *</label>
                        <input type="number" min="1" v-model="formTambah.jumlah" required placeholder="Cth: 10" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 px-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <button type="submit" :disabled="formTambah.processing" class="w-full py-4 bg-blue-600 text-white rounded-xl font-bold text-base shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition flex items-center justify-center">{{ formTambah.processing ? 'Memproses...' : 'Simpan Stok Baru' }}</button>
                </form>
                <form v-if="activeTab === 'baru'" @submit.prevent="submitBaru" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Nama Barang *</label>
                        <input type="text" v-model="formBaru.name" required placeholder="Cth: Tinta Printer" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 px-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Kategori</label>
                            <input type="text" v-model="formBaru.category" placeholder="Cth: ATK" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 px-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Satuan *</label>
                            <select v-model="formBaru.unit" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 px-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none"><option value="Pcs">Pcs</option><option value="Rim">Rim</option><option value="Box">Box</option><option value="Unit">Unit</option></select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Stok Awal *</label>
                        <input type="number" min="0" v-model="formBaru.stock" required placeholder="Cth: 50" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 px-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <button type="submit" :disabled="formBaru.processing" class="w-full py-4 bg-blue-600 text-white rounded-xl font-bold text-base shadow-lg hover:bg-blue-700 transition flex items-center justify-center">{{ formBaru.processing ? 'Memproses...' : 'Daftarkan Barang Baru' }}</button>
                </form>
            </div>
        </div>
    </div>

    <div v-if="isKeluarModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity" @click="isKeluarModalOpen = false"></div>

        <div class="relative bg-white w-full md:w-[500px] rounded-3xl shadow-2xl flex flex-col transition-all">
            
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-950">Input Barang Keluar</h3>
                <button @click="isKeluarModalOpen = false" class="p-2 text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-full transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6">
                <form @submit.prevent="submitKeluar" class="space-y-4">
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 tracking-wider uppercase mb-1">Pilih Barang <span class="text-red-500">*</span></label>
                        <select v-model="formKeluar.product_id" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-red-500 outline-none appearance-none transition">
                            <option value="" disabled>-- Pilih barang yang diambil --</option>
                            <option v-for="product in products" :key="product.id" :value="product.id">
                                {{ product.name }} (Tersedia: {{ product.stock }} {{ product.unit }})
                            </option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 tracking-wider uppercase mb-1">Jumlah <span class="text-red-500">*</span></label>
                            <input type="number" min="1" v-model="formKeluar.jumlah" required placeholder="Cth: 5" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-red-500 outline-none transition">
                            <p v-if="formKeluar.errors.jumlah" class="text-red-500 text-xs mt-1 font-semibold">{{ formKeluar.errors.jumlah }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 tracking-wider uppercase mb-1">Tujuan <span class="text-red-500">*</span></label>
                            <input type="text" v-model="formKeluar.tujuan" required placeholder="Cth: Kepegawaian" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-red-500 outline-none transition">
                        </div>
                    </div>

                    <div class="pt-1">
                        <label class="flex items-start cursor-pointer bg-red-50/50 p-3.5 border border-red-100 rounded-2xl select-none">
                            <input type="checkbox" v-model="formKeluar.verifikasi_pimpinan" required class="w-5 h-5 mt-0.5 border-gray-300 rounded text-red-500 focus:ring-red-500">
                            <span class="ml-3 text-xs text-gray-700 leading-tight">
                                <strong class="text-red-700 block mb-0.5 font-bold uppercase tracking-wide">Ajukan Verifikasi ke Pimpinan</strong>
                                Saya mengajukan agar pengeluaran barang ini diverifikasi terlebih dahulu oleh pimpinan/Kasi Lapas.
                            </span>
                        </label>
                        <p v-if="formKeluar.errors.verifikasi_pimpinan" class="text-red-500 text-xs mt-1.5 font-semibold">{{ formKeluar.errors.verifikasi_pimpinan }}</p>
                    </div>

                    <button type="submit" :disabled="formKeluar.processing" class="w-full mt-2 py-3.5 bg-red-500 text-white rounded-xl font-bold text-base shadow-lg shadow-red-500/20 hover:bg-red-600 transition flex items-center justify-center" :class="{'opacity-70 cursor-not-allowed': formKeluar.processing}">
                        <svg v-if="!formKeluar.processing" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                        <svg v-else class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        {{ formKeluar.processing ? 'Memproses...' : 'Ajukan Pengeluaran' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <Transition name="fade-scale">
        <div v-if="isSuccessModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-gray-950/80 backdrop-blur-sm" @click="closeSuccessModal"></div>
            
            <div class="relative bg-white w-full max-w-sm rounded-[30px] p-8 text-center shadow-2xl transition-all duration-300 transform">
                
                <div class="mx-auto w-24 h-24 bg-green-50 rounded-full flex items-center justify-center mb-6 border-4 border-green-100 shadow-inner">
                    <svg class="w-12 h-12 text-green-500 animate-pulse-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h4 class="text-2xl font-black text-gray-950 tracking-tight">{{ successTitle }}</h4>
                <p class="text-gray-600 mt-2.5 mb-8 leading-relaxed" v-html="successMessage"></p>

                <button @click="closeSuccessModal" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-bold text-base shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-colors focus:ring-4 focus:ring-blue-300 outline-none">
                    Selesai
                </button>
            </div>
        </div>
    </Transition>

</template>

<style scoped>
.fade-scale-enter-active,
.fade-scale-leave-active { transition: all 0.3s ease-out; }
.fade-scale-enter-from,
.fade-scale-leave-to { opacity: 0; transform: scale(0.8) translateY(20px); }
@keyframes pulse-slow { 0%, 100% { transform: scale(1); opacity: 1; } 50% { transform: scale(1.05); opacity: 0.8; } }
.animate-pulse-slow { animation: pulse-slow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
</style>