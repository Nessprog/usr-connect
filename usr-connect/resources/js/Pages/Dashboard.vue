<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

// On récupère les props envoyées par le contrôleur (web.php)
const props = defineProps({
    totalMissions: Number,
    myMissionsCount: Number,
    nextMission: Object,
    totalVolunteers: Number,
});

// On met à jour notre tableau de stats avec les vraies valeurs
const stats = [
    { label: "Missions à venir", value: props.totalMissions, icon: "📅" },
    { label: "Mes inscriptions", value: props.myMissionsCount, icon: "✅" },
    { label: "Bénévoles USR", value: props.totalVolunteers, icon: "👥" },
];

// Fonction pour formater la date joliment sur mobile
const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("fr-FR", {
        weekday: "long",
        day: "numeric",
        month: "long",
        hour: "2-digit",
        minute: "2-digit",
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tableau de bord
            </h2>
        </template>

        <div class="py-6 px-4 md:py-12">
            <div class="max-w-7xl mx-auto space-y-6">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-usr-purple"
                >
                    <h3 class="text-lg font-bold text-gray-900">
                        Salut {{ $page.props.auth.user.name }} ! 👋
                    </h3>
                    <p class="text-gray-600">
                        Prêt à aider l'USR aujourd'hui ? Voici un résumé de
                        l'activité.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div
                        v-for="stat in stats"
                        :key="stat.label"
                        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center space-x-4"
                    >
                        <div class="text-3xl">{{ stat.icon }}</div>
                        <div>
                            <p
                                class="text-sm text-gray-500 uppercase font-bold"
                            >
                                {{ stat.label }}
                            </p>
                            <p class="text-2xl font-black text-usr-purple">
                                {{ stat.value }}
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-100"
                >
                    <h3
                        class="text-md font-bold text-gray-800 mb-4 uppercase tracking-wider"
                    >
                        Ma prochaine mission
                    </h3>

                    <div
                        v-if="nextMission"
                        class="bg-purple-50 border border-purple-100 rounded-lg p-4 flex justify-between items-center"
                    >
                        <div>
                            <p class="font-bold text-usr-purple">
                                {{ nextMission.title }}
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ formatDate(nextMission.start_time) }}
                            </p>
                        </div>
                        <Link
                            :href="route('slots.show', nextMission.id)"
                            class="text-usr-purple font-bold text-sm underline"
                        >
                            Détails
                        </Link>
                    </div>

                    <div v-else class="text-center py-4">
                        <p class="text-gray-500 italic mb-3">
                            Aucune mission prévue pour le moment.
                        </p>
                        <Link
                            :href="route('slots.index')"
                            class="text-usr-purple font-bold text-sm"
                        >
                            Trouver une mission →
                        </Link>
                    </div>
                </div>

                <div class="flex justify-center">
                    <Link
                        :href="route('slots.index')"
                        class="w-full md:w-auto text-center bg-usr-purple text-white px-8 py-4 rounded-xl font-bold shadow-lg hover:bg-opacity-90 transition transform hover:scale-105"
                    >
                        Explorer le Planning Complet
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
