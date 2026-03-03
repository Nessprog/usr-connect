<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    slot: Object
});

// Petite fonction pour formater l'heure proprement
const formatTime = (dateString) => {
    return new Date(dateString).toLocaleTimeString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <Head :title="slot.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-usr-purple leading-tight">{{ slot.title }}</h2>
                <Link :href="route('slots.index')" class="text-sm text-gray-600 hover:text-usr-purple">← Retour au planning</Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 border-l-8 border-usr-purple">
                    <p class="text-gray-600 mb-6">{{ slot.description }}</p>
                    
                    <div class="bg-usr-light p-4 rounded-lg mb-8 flex justify-around text-usr-purple font-bold">
                        <span>🕒 {{ formatTime(slot.start_time) }} - {{ formatTime(slot.end_time) }}</span>
                    </div>

                    <h3 class="text-lg font-bold mb-4 text-gray-800 border-b pb-2">L'équipe de bénévoles ({{ slot.users.length }})</h3>
                    
                    <ul v-if="slot.users.length > 0" class="divide-y divide-gray-100">
                        <li v-for="user in slot.users" :key="user.id" class="py-3 flex items-center">
                            <div class="h-8 w-8 rounded-full bg-usr-purple text-white flex items-center justify-center text-xs mr-3">
                                {{ user.name.charAt(0) }}
                            </div>
                            <span class="text-gray-700 font-medium">{{ user.name }}</span>
                        </li>
                    </ul>
                    <p v-else class="text-gray-500 italic">Aucun bénévole n'est encore inscrit. Soyez le premier !</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>