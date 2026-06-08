<script setup>
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Edit Profil - Sistok Lapas" />

    <AuthenticatedLayout>
        <div class="p-4 md:p-8">
            <div class="max-w-4xl mx-auto space-y-6">
                
                <div class="p-6 md:p-8 bg-white shadow-sm border border-gray-100 rounded-3xl">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="p-6 md:p-8 bg-white shadow-sm border border-gray-100 rounded-3xl">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="p-6 md:p-8 bg-red-50 shadow-sm border border-red-100 rounded-3xl">
                    <DeleteUserForm class="max-w-xl" />
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>