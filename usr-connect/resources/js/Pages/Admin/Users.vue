<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    users: Array
});

const form = useForm({
    role: ''
});

const changeRole = (user, newRole) => {
    form.role = newRole;
    form.patch(route('admin.users.update', user.id));
};
</script>

<template>
    <Head title="Gestion des utilisateurs" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="font-semibold text-xl text-usr-purple leading-tight">Gestion des membres USR</h1>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2">Nom</th>
                                <th class="py-2">Rôle</th>
                                <th class="py-2 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users" :key="user.id" class="border-b hover:bg-gray-50">
                                <td class="py-4">{{ user.name }}</td>
                                <td class="py-4">
                                    <span :class="user.role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700'" class="px-2 py-1 rounded text-xs font-bold uppercase">
                                        {{ user.role }}
                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <button 
                                        v-if="user.role === 'volunteer'"
                                        @click="changeRole(user, 'admin')"
                                        class="text-sm text-usr-purple hover:underline"
                                    >
                                        Passer Admin
                                    </button>
                                    <button 
                                        v-else
                                        @click="changeRole(user, 'volunteer')"
                                        class="text-sm text-red-600 hover:underline"
                                    >
                                        Retirer Admin
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>