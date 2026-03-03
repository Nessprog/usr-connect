<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = useForm({
    title: '',
    description: '',
    start_time: '',
    end_time: '',
    min_volunteers: 1,
    max_volunteers: 5,
});

const submit = () => {
    form.post(route('slots.store'));
};
</script>

<template>
    <Head title="Créer une mission" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
                <h2 class="text-xl font-bold mb-4 text-usr-purple">Nouvelle mission USR</h2>
                
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block">Titre</label>
                        <input v-model="form.title" type="text" class="w-full border-gray-300 rounded shadow-sm focus:border-usr-purple">
                    </div>
                    
                    <div>
                        <label class="block">Description</label>
                        <textarea v-model="form.description" class="w-full border-gray-300 rounded shadow-sm"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block">Début</label>
                            <input v-model="form.start_time" type="datetime-local" class="w-full border-gray-300 rounded shadow-sm">
                        </div>
                        <div>
                            <label class="block">Fin</label>
                            <input v-model="form.end_time" type="datetime-local" class="w-full border-gray-300 rounded shadow-sm">
                        </div>
                    </div>

                    <button type="submit" class="bg-usr-purple text-white px-4 py-2 rounded font-bold">
                        Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>