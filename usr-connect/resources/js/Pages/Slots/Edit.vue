<script setup>
import { useForm, Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({ slot: Object });

const formatDateForInput = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);

    // On compense le décalage horaire manuellement pour garder l'heure "réelle"
    const offset = date.getTimezoneOffset() * 60000;
    const localISOTime = new Date(date.getTime() - offset)
        .toISOString()
        .slice(0, 16);

    return localISOTime;
};

const form = useForm({
    title: props.slot.title,
    description: props.slot.description,
    // On utilise ENFIN la fonction que tu as créée !
    start_time: formatDateForInput(props.slot.start_time),
    end_time: formatDateForInput(props.slot.end_time),
    min_volunteers: props.slot.min_volunteers,
    max_volunteers: props.slot.max_volunteers,
    category: props.slot.category,
});

const POLES = [
    { id: "Animation", label: "🎉 Animation (Mascotte, Tombola)" },
    { id: "Basket", label: "🏀 Basket" },
    { id: "Buvette", label: "🍺 Buvette" },
    { id: "Caisse", label: "💸 Caisse" },
    { id: "HDG", label: "🍨 HDG" },
    { id: "Infirmerie", label: "🚑 Infirmerie" },
    { id: "Logistique", label: "🛠️ Logistique" },
    { id: "Parking", label: "🚗 Parking" },
    { id: "Restauration", label: "🥘 Restauration (Sucré, Snack)" },
    { id: "Sportif", label: "⚽ Sportif (Tournois, Arbitrage)" },
];

const submit = () => {
    // On utilise put pour une modification
    form.put(route("slots.update", props.slot.id));
};
</script>

<template>
    <Head :title="'Modifier ' + props.slot.title" />
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
                            <option
                                v-for="pole in POLES"
                                :key="pole.id"
                                :value="pole.id"
                            >
                                {{ pole.label }}
                            </option>
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
