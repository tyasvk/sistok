<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    users: {
        type: Array,
        default: () => []
    }
});

// States
const isModalOpen = ref(false);
const isEditMode = ref(false);
const isDeleteModalOpen = ref(false);
const selectedUser = ref(null);

const isSuccessModalOpen = ref(false);
const successMessage = ref('');

// Form User
const form = useForm({
    id: '',
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'staff',
});

// Fungsi Buka Modal Tambah
const openAddModal = () => {
    isEditMode.value = false;
    form.reset();
    form.clearErrors();
    isModalOpen.value = true;
};

// Fungsi Buka Modal Edit
const openEditModal = (user) => {
    isEditMode.value = true;
    form.reset();
    form.clearErrors();
    
    let userRole = 'staff';
    if (user.is_admin) userRole = 'admin';
    if (user.is_pimpinan) userRole = 'pimpinan';

    form.id = user.id;
    form.name = user.name;
    form.email = user.email;
    form.role = userRole;
    
    isModalOpen.value = true;
};

// Fungsi Simpan (Tambah / Edit)
const submitUser = () => {
    if (isEditMode.value) {
        form.put(route('users.update', form.id), {
            preserveScroll: true,
            onSuccess: () => {
                isModalOpen.value = false;
                showSuccess(`Data user <strong class="text-gray-900">${form.name}</strong> berhasil diperbarui.`);
            }
        });
    } else {
        form.post(route('users.store'), {
            preserveScroll: true,
            onSuccess: () => {
                isModalOpen.value = false;
                showSuccess(`User baru <strong class="text-gray-900">${form.name}</strong> berhasil ditambahkan.`);
            }
        });
    }
};

// Fungsi Hapus
const confirmDelete = (user) => {
    selectedUser.value = user;
    isDeleteModalOpen.value = true;
};

const deleteUser = () => {
    router.delete(route('users.destroy', selectedUser.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            isDeleteModalOpen.value = false;
            showSuccess(`User <strong class="text-gray-900">${selectedUser.value.name}</strong> telah dihapus dari sistem.`);
        }
    });
};

const showSuccess = (message) => {
    successMessage.value = message;
    isSuccessModalOpen.value = true;
};
</script>

<template>
    <Head title="Manajemen User - Sistok Lapas" />

    <AuthenticatedLayout>
        <div class="p-4 md:p-8">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-4 md:p-8">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                    <div>
                        <h3 class="text-xl font-bold">Daftar Pengguna Sistem</h3>
                        <p class="text-sm text-gray-500">Kelola akses staf, admin, dan pimpinan</p>
                    </div>
                    
                    <button @click="openAddModal" class="w-full md:w-auto flex items-center justify-center px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah User
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] md:text-xs font-bold text-gray-400 uppercase border-b border-gray-50">
                                <th class="pb-4 px-2">Nama</th>
                                <th class="pb-4 px-2">Email</th>
                                <th class="pb-4 px-2">Peran (Role)</th>
                                <th class="pb-4 px-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr v-for="user in users" :key="user.id" class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-2 font-bold text-gray-900">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-3">
                                            {{ user.name.charAt(0).toUpperCase() }}
                                        </div>
                                        {{ user.name }}
                                        <span v-if="$page.props.auth.user.id === user.id" class="ml-2 text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full font-bold">SAYA</span>
                                    </div>
                                </td>
                                <td class="py-4 px-2 text-gray-600">{{ user.email }}</td>
                                <td class="py-4 px-2">
                                    <span v-if="user.role === 'Admin'" class="px-2.5 py-1 bg-purple-50 text-purple-600 text-[10px] font-bold rounded-full border border-purple-100">ADMIN</span>
                                    <span v-else-if="user.role === 'Pimpinan'" class="px-2.5 py-1 bg-amber-50 text-amber-600 text-[10px] font-bold rounded-full border border-amber-100">PIMPINAN</span>
                                    <span v-else class="px-2.5 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-full border border-gray-200">STAFF</span>
                                </td>
                                <td class="py-4 px-2 text-right space-x-2">
                                    <button @click="openEditModal(user)" class="inline-flex p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button v-if="$page.props.auth.user.id !== user.id" @click="confirmDelete(user)" class="inline-flex p-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-end md:items-center justify-center p-4">
            <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity" @click="isModalOpen = false"></div>
            <div class="relative bg-white w-full md:w-[500px] max-h-[90vh] overflow-y-auto rounded-3xl shadow-2xl flex flex-col transition-all">
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-950">{{ isEditMode ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}</h3>
                    <button @click="isModalOpen = false" class="p-2.5 text-gray-400 rounded-full bg-gray-50 hover:bg-gray-100"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                
                <div class="p-6 md:p-8">
                    <form @submit.prevent="submitUser" class="space-y-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Nama Lengkap *</label>
                            <input type="text" v-model="form.name" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 px-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Alamat Email *</label>
                            <input type="email" v-model="form.email" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 px-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Hak Akses (Role) *</label>
                            <select v-model="form.role" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 px-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none appearance-none">
                                <option value="staff">Staff Biasa</option>
                                <option value="admin">Administrator</option>
                                <option value="pimpinan">Pimpinan Lapas</option>
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Password <span v-if="!isEditMode">*</span></label>
                                <input type="password" v-model="form.password" :required="!isEditMode" :placeholder="isEditMode ? '(Kosongkan jika tak diubah)' : ''" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 px-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                                <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Konfirmasi Password <span v-if="!isEditMode">*</span></label>
                                <input type="password" v-model="form.password_confirmation" :required="!isEditMode" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 px-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>

                        <button type="submit" :disabled="form.processing" class="w-full mt-2 py-4 bg-blue-600 text-white rounded-xl font-bold text-base shadow-lg hover:bg-blue-700 transition flex items-center justify-center">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Data' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <Transition name="fade-scale">
            <div v-if="isDeleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="isDeleteModalOpen = false"></div>
                <div class="relative bg-white w-full max-w-sm rounded-[32px] p-8 text-center shadow-2xl transition-all">
                    <div class="mx-auto w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mb-6 border-4 border-red-100">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h4 class="text-2xl font-black text-gray-900">Hapus User?</h4>
                    <p class="text-gray-500 mt-3 mb-8">Anda yakin ingin menghapus <strong>{{ selectedUser?.name }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="flex gap-3">
                        <button @click="isDeleteModalOpen = false" class="flex-1 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-bold text-sm transition-colors outline-none">Batal</button>
                        <button @click="deleteUser" class="flex-1 py-3.5 bg-red-600 hover:bg-red-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-red-600/20 transition-colors outline-none">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </Transition>

        <Transition name="fade-scale">
            <div v-if="isSuccessModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-gray-950/80 backdrop-blur-sm" @click="isSuccessModalOpen = false"></div>
                <div class="relative bg-white w-full max-w-sm rounded-[30px] p-8 text-center shadow-2xl transition-all">
                    <div class="mx-auto w-24 h-24 bg-green-50 rounded-full flex items-center justify-center mb-6 border-4 border-green-100">
                        <svg class="w-12 h-12 text-green-500 animate-pulse-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h4 class="text-2xl font-black text-gray-950">Berhasil!</h4>
                    <p class="text-gray-600 mt-2.5 mb-8 leading-relaxed" v-html="successMessage"></p>
                    <button @click="isSuccessModalOpen = false" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-bold text-base shadow-lg hover:bg-blue-700 transition-colors">Selesai</button>
                </div>
            </div>
        </Transition>

    </AuthenticatedLayout>
</template>

<style scoped>
.fade-scale-enter-active, .fade-scale-leave-active { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.fade-scale-enter-from, .fade-scale-leave-to { opacity: 0; transform: scale(0.9) translateY(10px); }
@keyframes pulse-slow { 0%, 100% { transform: scale(1); opacity: 1; } 50% { transform: scale(1.05); opacity: 0.8; } }
.animate-pulse-slow { animation: pulse-slow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
</style>