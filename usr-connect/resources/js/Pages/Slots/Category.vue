<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";
import dayjs from "dayjs";
import "dayjs/locale/fr";

dayjs.locale("fr");

const props = defineProps({
    slots: Array,
    categoryName: String,
});

// Regroupement des missions par jour
const groupedSlots = computed(() => {
    const groups = {};
    props.slots.forEach((slot) => {
        const date = dayjs(slot.start_time).format("YYYY-MM-DD");
        if (!groups[date]) groups[date] = [];
        groups[date].push(slot);
    });
    return groups;
});

const formatDateHeader = (dateStr) => {
    // Affiche par exemple : "Samedi 23 Mai"
    const formatted = dayjs(dateStr).format("dddd D MMMM");
    return formatted.charAt(0).toUpperCase() + formatted.slice(1);
};

// Formatage de la date (ex: samedi 24 juin)
const formatDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return date.toLocaleDateString("fr-FR", {
        weekday: "long",
        day: "numeric",
        month: "long",
    });
};

// Formatage de l'heure (ex: 14h30)
const formatTime = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return date
        .toLocaleTimeString("fr-FR", { hour: "2-digit", minute: "2-digit" })
        .replace(":", "h");
};

// Calcul du pourcentage de remplissage
const getPercentage = (current, max) => {
    if (!max || max === 0) return 0;
    const percent = Math.round((current / max) * 100);
    return Math.min(percent, 100);
};

const canUnregister = (startTime) => {
    const now = dayjs();
    const start = dayjs(startTime);
    // On vérifie s'il reste plus de 24 heures
    return start.diff(now, "hour") >= 24;
};
</script>

<template>
    <Head :title="'Pôle ' + categoryName" />

    <AuthenticatedLayout>
        <div
            class="py-12 bg-gray-50 min-h-screen px-4 sm:px-6 lg:px-8 text-gray-900"
        >
            <div class="max-w-7xl mx-auto">
                <div class="mb-12">
                    <Link
                        :href="route('slots.index')"
                        class="text-[#5D2E8E] font-bold flex items-center mb-6 hover:underline transition"
                    >
                        ← Retour aux pôles
                    </Link>

                    <div
                        class="flex flex-col md:flex-row md:items-center justify-between gap-6"
                    >
                        <h1
                            class="text-4xl font-black tracking-tight uppercase italic m-0"
                        >
                            Pôle {{ categoryName }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-4">
                            <Link
                                v-if="
                                    categoryName &&
                                    $page.props.auth.user.role === 'admin'
                                "
                                :href="
                                    route('slots.create', {
                                        category: categoryName,
                                    })
                                "
                                class="inline-flex items-center gap-2 bg-[#5D2E8E] text-white px-5 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-[#4a2472] transition shadow-lg shadow-purple-100"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 4v16m8-8H4"
                                    />
                                </svg>
                                Nouvelle Mission
                            </Link>

                            <a
                                v-if="
                                    categoryName &&
                                    $page.props.auth.user.role === 'admin'
                                "
                                :href="route('slots.pdf', categoryName)"
                                class="inline-flex items-center gap-2 bg-[#5D2E8E] text-white px-5 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-[#4a2472] transition shadow-lg shadow-purple-100"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </svg>
                                Exporter PDF
                            </a>
                        </div>
                    </div>
                </div>

                <div v-if="Object.keys(groupedSlots).length > 0">
                    <div
                        v-for="(daySlots, date) in groupedSlots"
                        :key="date"
                        class="mb-16"
                    >
                        <div class="relative flex py-8 items-center">
                            <div
                                class="flex-grow border-t-2 border-gray-200"
                            ></div>
                            <span
                                class="flex-shrink mx-6 px-6 py-2 bg-[#F5F3FF] border-2 border-[#5D2E8E] rounded-full text-lg font-black text-[#5D2E8E] uppercase tracking-widest italic shadow-sm"
                            >
                                {{ formatDateHeader(date) }}
                            </span>
                            <div
                                class="flex-grow border-t-2 border-gray-200"
                            ></div>
                        </div>

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10"
                        >
                            <div
                                v-for="slot in daySlots"
                                :key="slot.id"
                                class="bg-white rounded-[2.5rem] shadow-sm border-t-[8px] border-[#5D2E8E] p-8 flex flex-col justify-between transition-transform hover:scale-[1.02]"
                            >
                                <div>
                                    <div
                                        class="flex justify-between items-start mb-6"
                                    >
                                        <Link
                                            :href="route('slots.show', slot.id)"
                                            class="hover:text-[#5D2E8E] transition flex-1"
                                        >
                                            <h3
                                                class="text-xl font-black leading-tight pr-4 capitalize"
                                            >
                                                {{ slot.title }}
                                            </h3>
                                        </Link>
                                        <div
                                            class="bg-[#F5F3FF] text-[#5D2E8E] text-[11px] font-bold px-3 py-2 rounded-lg uppercase whitespace-nowrap"
                                        >
                                            {{ slot.users_count || 0 }} /
                                            {{ slot.max_volunteers }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-8">
                                    <div
                                        class="flex justify-between items-center mb-2"
                                    >
                                        <span
                                            class="text-[12px] font-black text-gray-400 uppercase italic"
                                            >Remplissage</span
                                        >
                                        <span
                                            class="text-sm font-black text-[#5D2E8E] italic"
                                            >{{
                                                getPercentage(
                                                    slot.users_count,
                                                    slot.max_volunteers,
                                                )
                                            }}%</span
                                        >
                                    </div>
                                    <div
                                        class="w-full bg-gray-100 rounded-full h-2"
                                    >
                                        <div
                                            class="bg-[#5D2E8E] h-2 rounded-full transition-all duration-500"
                                            :style="{
                                                width:
                                                    getPercentage(
                                                        slot.users_count,
                                                        slot.max_volunteers,
                                                    ) + '%',
                                            }"
                                        ></div>
                                    </div>
                                    <div
                                        v-if="
                                            slot.users_count <
                                            slot.min_volunteers
                                        "
                                        class="mt-2 flex items-center text-red-600"
                                    >
                                        <span
                                            class="text-[12px] font-black uppercase italic flex items-center gap-1"
                                        >
                                            ⚠️ Besoin de
                                            {{
                                                slot.min_volunteers -
                                                slot.users_count
                                            }}
                                            bénévole(s)
                                        </span>
                                    </div>
                                </div>

                                <div
                                    class="space-y-3 mb-8 text-[18px] font-bold"
                                >
                                    <p
                                        class="flex items-center gap-3 capitalize"
                                    >
                                        <span class="text-xl">🗓️</span>
                                        {{ formatDate(slot.start_time) }}
                                    </p>
                                    <p class="flex items-center gap-3">
                                        <span class="text-xl">🕒</span>
                                        {{ formatTime(slot.start_time) }} -
                                        {{ formatTime(slot.end_time) }}
                                    </p>
                                </div>

                                <div class="mt-auto">
                                    <Link
                                        v-if="
                                            slot.is_registered &&
                                            canUnregister(slot.start_time)
                                        "
                                        :href="
                                            route('slots.unregister', slot.id)
                                        "
                                        method="delete"
                                        as="button"
                                        class="w-full bg-red-500 text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-red-600 transition shadow-lg shadow-red-100"
                                    >
                                        SE DÉSISTER
                                    </Link>

                                    <div
                                        v-else-if="
                                            slot.is_registered &&
                                            !canUnregister(slot.start_time)
                                        "
                                        class="w-full bg-gray-100 text-gray-400 py-4 rounded-2xl font-black text-center text-xs uppercase italic border-2 border-dashed border-gray-200"
                                    >
                                        🔒 Désistement bloqué (-24h)
                                    </div>
                                    <button
                                        v-else-if="
                                            slot.users_count >=
                                            slot.max_volunteers
                                        "
                                        disabled
                                        class="w-full bg-gray-200 text-gray-500 py-4 rounded-2xl font-black uppercase tracking-widest cursor-not-allowed"
                                    >
                                        COMPLET
                                    </button>
                                    <Link
                                        v-else
                                        :href="route('slots.register', slot.id)"
                                        method="post"
                                        as="button"
                                        class="w-full bg-[#5D2E8E] text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-[#4a2472] transition shadow-lg shadow-purple-100"
                                    >
                                        REJOINDRE
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-20">
                    <p
                        class="text-gray-400 font-bold italic uppercase tracking-widest"
                    >
                        Aucune mission disponible pour le moment.
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
