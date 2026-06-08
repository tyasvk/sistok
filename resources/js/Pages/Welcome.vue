<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

// Import logo asli dari folder assets
import logoKemenimipas from '@/assets/images/logo_kemenimipas.png';
import logoPemasyarakatan from '@/assets/images/logo_pemasyarakatan.png';
import logoLameta from '@/assets/images/logo_lameta.png';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    status: String,
});

// Menggunakan email agar sesuai dengan backend bawaan Laravel Breeze
const form = useForm({
    email: '', 
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Welcome - Sistok Lapas Palembang" />

    <div class="relative min-h-screen lg:h-screen bg-black bg-cover bg-center text-white flex items-center justify-center overflow-x-hidden" 
         style="background-image: url('https://wartaoke.net/wp-content/uploads/2021/04/Wartasoke-Net-Pemda-Pemprov-Nasional-logo-pemasyarakatan-pengayoman.png');">
        
        <div class="absolute inset-0 bg-black/75 md:bg-black/70"></div>

        <div class="relative z-10 w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col lg:flex-row items-center justify-center gap-8 lg:gap-12 h-full">
            
            <div class="w-full lg:w-1/2 flex flex-col items-center lg:items-start text-center lg:text-left mt-4 lg:mt-0">
                
                <div class="flex items-center space-x-3 mb-4 lg:mb-6">
                    <div class="p-1.5 bg-white rounded-lg shadow-xl">
                        <img :src="logoKemenimipas" alt="Logo Kemenimipas" class="h-8 w-8 sm:h-10 sm:w-10 lg:h-12 lg:w-12 object-contain" />
                    </div>
                    <div class="p-1.5 bg-white rounded-lg shadow-xl">
                        <img :src="logoPemasyarakatan" alt="Logo Pemasyarakatan" class="h-8 w-8 sm:h-10 sm:w-10 lg:h-12 lg:w-12 object-contain" />
                    </div>
                    <div class="p-1.5 bg-white rounded-lg shadow-xl">
                        <img :src="logoLameta" alt="Logo Lameta" class="h-8 w-8 sm:h-10 sm:w-10 lg:h-12 lg:w-12 object-contain" />
                    </div>
                </div>

                <div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight text-white tracking-tight">
                        SISTOK<br />
                        <span class="text-blue-500 lg:text-blue-600">LAPAS KELAS 1</span><br />
                        PALEMBANG
                    </h1>
                    
                    <p class="mt-3 lg:mt-5 text-sm lg:text-base text-gray-300 leading-relaxed max-w-md lg:max-w-xl mx-auto lg:mx-0">
                        Sistem Informasi Stok Barang Terpadu. Pantau, kelola, dan rekap inventaris barang instansi dengan mudah, cepat, dan transparan secara <em>real-time</em>.
                    </p>
                </div>
            </div>

            <div class="w-full lg:w-1/2 flex items-center justify-center lg:justify-end">
                <div class="bg-gray-900/70 backdrop-blur-xl p-6 lg:p-8 rounded-3xl lg:rounded-[30px] shadow-2xl border border-gray-700/50 w-full max-w-sm sm:max-w-md">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-1 lg:mb-2">Login</h2>
                    <p class="text-gray-400 text-xs sm:text-sm lg:text-base mb-5 lg:mb-6">Selamat datang kembali, silakan masuk</p>

                    <form @submit.prevent="submit" class="space-y-4">
                        
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <TextInput
                                id="email"
                                type="text"
                                class="w-full bg-gray-800/80 border border-gray-700/50 text-white rounded-xl py-3 lg:py-3.5 pl-12 pr-4 text-sm lg:text-base placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 shadow-inner"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="Email / NIP"
                            />
                            <InputError class="mt-1" :message="form.errors.email" />
                        </div>

                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <TextInput
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                class="w-full bg-gray-800/80 border border-gray-700/50 text-white rounded-xl py-3 lg:py-3.5 pl-12 pr-12 text-sm lg:text-base placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 shadow-inner"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                placeholder="Password"
                            />
                            <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-white transition">
                                <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                            <InputError class="mt-1" :message="form.errors.password" />
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center cursor-pointer">
                                <Checkbox name="remember" v-model:checked="form.remember" class="w-4 h-4 lg:w-5 lg:h-5 border-gray-600 bg-gray-800 rounded text-blue-500 focus:ring-blue-500" />
                                <span class="ml-2 lg:ml-3 text-sm lg:text-base text-gray-400 select-none">Ingat saya</span>
                            </label>
                        </div>

                        <PrimaryButton 
    class="w-full bg-blue-600 py-3 lg:py-4 rounded-xl shadow-lg text-white font-bold text-base lg:text-lg transition duration-150 flex items-center justify-center focus:ring-4 focus:ring-blue-500/50 mt-2" 
    :class="{ 'opacity-75 cursor-wait hover:bg-blue-600': form.processing, 'hover:bg-blue-700': !form.processing }" 
    :disabled="form.processing"
>
    <span v-if="form.processing" class="flex items-center">
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Memproses...
    </span>

    <span v-else>Masuk</span>
</PrimaryButton>
                    </form>

                    <div class="mt-6 lg:mt-8 text-center text-xs sm:text-sm text-gray-500">
                        Developed for <span class="font-semibold text-gray-400">Lapas Palembang</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>