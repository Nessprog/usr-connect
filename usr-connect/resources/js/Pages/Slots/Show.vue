<script setup>
import { Head, Link, router, usePage } from "@inertiajs/vue3"; // On ajoute router et usePage ici
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { computed } from "vue"; // On ajoute computed pour la réactivité

const props = defineProps({
    slot: Object,
});

// 1. On récupère l'utilisateur connecté
const user = computed(() => usePage().props.auth.user);

// 2. On vérifie si l'utilisateur est déjà inscrit dans la liste "users" du slot
const isRegistered = computed(() => {
    // some() renvoie vrai si l'ID de l'utilisateur est trouvé dans la liste des inscrits
    return props.slot.users.some((u) => u.id === user.value.id);
});

// 3. Ta fonction pour formater l'heure (inchangée)
const formatTime = (dateString) => {
    return new Date(dateString).toLocaleTimeString("fr-FR", {
        hour: "2-digit",
        minute: "2-digit",
    });
};

// 4. La logique du bouton (Inscription / Désistement)
const handleSubscription = () => {
    if (isRegistered.value) {
        if (confirm("Es-tu sûr de vouloir te désister ?")) {
            router.delete(route("slots.unregister", props.slot.id));
        }
    } else {
        router.post(route("slots.register", props.slot.id));
    }
};

const deleteSlot = () => {
    if (
        confirm(
            "ATTENTION : Veux-tu vraiment supprimer cette mission et désister tous les bénévoles ?",
        )
    ) {
        router.delete(route("slots.destroy", props.slot.id));
    }
};
</script>

<template>
    <Head :title="slot.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-usr-purple leading-tight">
                    {{ slot.title }}
                </h2>
                <Link
                    :href="route('slots.index')"
                    class="text-sm text-gray-600 hover:text-usr-purple"
                    >← Retour au planning</Link
                >
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white p-6 rounded-lg shadow border-l-4 border-usr-purple"
                >
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-gray-600 text-lg flex-1 mr-4">
                            {{ slot.description }}
                        </p>

                        <div class="shrink-0 flex gap-2">
                            <button
                                @click="handleSubscription"
                                :class="
                                    isRegistered
                                        ? 'bg-red-500 hover:bg-red-600'
                                        : 'bg-usr-purple hover:bg-opacity-90'
                                "
                                class="px-4 py-2 text-xs text-white font-bold rounded shadow-sm transition transform active:scale-95 whitespace-nowrap uppercase tracking-wider"
                            >
                                {{ isRegistered ? "Se désister" : "Rejoindre" }}
                            </button>

                            <button
                                v-if="$page.props.auth.user.role === 'admin'"
                                @click="deleteSlot"
                                class="px-4 py-2 text-xs text-red-600 font-bold border border-red-200 rounded hover:bg-red-50 transition uppercase"
                            >
                                Supprimer la mission
                            </button>
                        </div>
                    </div>

                    <hr class="border-gray-100 mb-6" />

                    <div
                        class="bg-purple-50 p-4 rounded-xl flex items-center justify-center text-usr-purple font-bold mb-8"
                    >
                        <span class="mr-2">🕒</span>
                        {{ formatTime(slot.start_time) }} -
                        {{ formatTime(slot.end_time) }}
                    </div>

                    <h3 class="font-bold text-xl mb-4">
                        L'équipe de bénévoles ({{ slot.users.length }})
                    </h3>

                    <ul
                        v-if="slot.users.length > 0"
                        class="divide-y divide-gray-100"
                    >
                        <li
                            v-for="user in slot.users"
                            :key="user.id"
                            class="py-3 flex items-center"
                        >
                            <div
                                class="h-8 w-8 rounded-full bg-usr-purple text-white flex items-center justify-center text-xs mr-3"
                            >
                                {{ user.name.charAt(0) }}
                            </div>
                            <span class="text-gray-700 font-medium">{{
                                user.name
                            }}</span>
                        </li>
                    </ul>
                    <p v-else class="text-gray-500 italic">
                        Aucun bénévole n'est encore inscrit. Soyez le premier !
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
