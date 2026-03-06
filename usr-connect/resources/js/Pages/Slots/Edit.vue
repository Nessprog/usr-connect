<script setup>
import { useForm, Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({ slot: Object });

const form = useForm({
    title: props.slot.title,
    description: props.slot.description,
    start_time: props.slot.start_time,
    end_time: props.slot.end_time,
    min_volunteers: props.slot.min_volunteers,
    max_volunteers: props.slot.max_volunteers,
});

const submit = () => {
    // On utilise put pour une modification
    form.put(route("slots.update", props.slot.id));
};
</script>

<template>
    <Head title="Créer une mission" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
                <h2 class="text-xl font-bold mb-4 text-usr-purple">
                    Modifier la mission "{{ props.slot.title }}"
                </h2>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block font-medium text-sm text-gray-700"
                            >Titre</label
                        >
                        <input
                            v-model="form.title"
                            type="text"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-usr-purple focus:ring-usr-purple"
                        />
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700"
                            >Description</label
                        >
                        <textarea
                            v-model="form.description"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-usr-purple focus:ring-usr-purple"
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block font-medium text-sm text-gray-700"
                                >Début</label
                            >
                            <input
                                v-model="form.start_time"
                                type="datetime-local"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-usr-purple focus:ring-usr-purple"
                            />
                        </div>
                        <div>
                            <label
                                class="block font-medium text-sm text-gray-700"
                                >Fin</label
                            >
                            <input
                                v-model="form.end_time"
                                type="datetime-local"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-usr-purple focus:ring-usr-purple"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <label
                                class="block font-medium text-sm text-gray-700"
                                >Bénévoles Min</label
                            >
                            <input
                                type="number"
                                v-model="form.min_volunteers"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-usr-purple focus:ring-usr-purple"
                            />
                        </div>
                        <div>
                            <label
                                class="block font-medium text-sm text-gray-700"
                                >Bénévoles Max</label
                            >
                            <input
                                type="number"
                                v-model="form.max_volunteers"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-usr-purple focus:ring-usr-purple"
                            />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Pôle (Catégorie)</label
                        >
                        <select
                            v-model="form.category"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-usr-purple focus:ring-usr-purple mt-1"
                            required
                        >
                            <option value="" disabled>
                                Choisir un pôle...
                            </option>
                            <option value="Sportif">
                                ⚽ Sportif (Tournois, Arbitrage)
                            </option>
                            <option value="Buvette">🍺 Buvette</option>
                            <option value="Restauration">
                                🥘 Restauration (Paëlla, Snack)
                            </option>
                            <option value="Caisse">💸 Caisse</option>
                        </select>
                    </div>

                    <button
                        type="submit"
                        class="bg-usr-purple text-white px-4 py-2 rounded font-bold"
                    >
                        Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
