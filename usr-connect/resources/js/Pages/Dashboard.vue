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
// On met à jour notre tableau de stats avec les routes cibles
const stats = [
    {
        label: "Missions à venir",
        value: props.totalMissions,
        icon: "📅",
        route: "slots.index",
    },
    {
        label: "Mes inscriptions",
        value: props.myMissionsCount,
        icon: "✅",
        route: "slots.my-slots",
    },
    {
        label: "Bénévoles USR",
        value: props.totalVolunteers,
        icon: "👥",
        route: "users.index",
    },
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
            <h2
                class="font-black text-2xl uppercase italic text-gray-800 leading-tight"
            >
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

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-5">
                    <component
                        :is="Link"
                        v-for="stat in stats"
                        :key="stat.label"
                        :href="route(stat.route)"
                        class="bg-white p-6 rounded-[2.5rem] shadow-sm flex items-center transition-all hover:shadow-md hover:border-b-4 hover:border-[#5D2E8E] group cursor-pointer"
                    >
                        <div
                            class="p-3 rounded-2xl mr-4 transition-transform group-hover:scale-110"
                            :class="[
                                stat.label === 'Missions à venir'
                                    ? 'bg-blue-100'
                                    : stat.label === 'Mes inscriptions'
                                      ? 'bg-green-100'
                                      : 'bg-purple-100',
                            ]"
                        >
                            <span class="text-2xl">{{ stat.icon }}</span>
                        </div>
                        <div>
                            <p
                                class="text-gray-500 font-bold uppercase text-[10px] tracking-widest"
                            >
                                {{ stat.label }}
                            </p>
                            <p class="text-2xl font-black text-gray-900">
                                {{ stat.value }}
                            </p>
                        </div>
                    </component>
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
                        class="bg-purple-50 border border-purple-100 rounded-lg p-3 flex justify-between items-center"
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
                            v-if="nextMission"
                            :href="route('slots.my-slots')"
                            class="text-[#5D2E8E] font-bold text-sm hover:underline"
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
