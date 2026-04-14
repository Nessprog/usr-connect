<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";
import dayjs from "dayjs";

const props = defineProps({
    slots: Array,
});

// Calcul du total d'heures arrondi
const totalHours = computed(() => {
    const total = props.slots.reduce((acc, slot) => {
        const start = new Date(slot.start_time);
        const end = new Date(slot.end_time);
        // Calcul de la différence en heures
        return acc + (end - start) / (1000 * 60 * 60);
    }, 0);

    // Math.round(total) pour 5h
    // total.toFixed(1) pour 5.5h (choisis ce que tu préfères)
    return Math.round(total);
});

// Mission finie si la date de fin est passée
const isFinished = (endTime) => {
    if (!endTime) return false;
    return dayjs().isAfter(dayjs(endTime));
};

// Fonction pour vérifier si l'utilisateur peut se désinscrire (24h avant le début)
const canUnregister = (startTime) => {
    if (!startTime) return false;
    const now = dayjs();
    const start = dayjs(startTime);
    // Retourne vrai s'il reste 24h ou plus
    return start.diff(now, "hour") >= 24;
};

const formatDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return date.toLocaleDateString("fr-FR", {
        weekday: "long",
        day: "numeric",
        month: "long",
    });
};

const formatTime = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return date
        .toLocaleTimeString("fr-FR", { hour: "2-digit", minute: "2-digit" })
        .replace(":", "h");
};
</script>

<template>
    <Head title="Mon Planning" />

    <AuthenticatedLayout>
        <div class="py-12 bg-gray-50 min-h-screen px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div
                    class="mb-12 bg-[#5D2E8E] p-8 rounded-[2.5rem] text-white shadow-xl relative overflow-hidden"
                >
                    <div
                        class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6"
                    >
                        <div>
                            <h1
                                class="text-3xl sm:text-4xl font-black mb-2 italic uppercase"
                            >
                                Mon Planning
                            </h1>
                            <p
                                class="text-purple-200 font-bold uppercase tracking-widest text-sm"
                            >
                                {{ slots.length }} mission{{
                                    slots.length > 1 ? "s" : ""
                                }}
                                • {{ totalHours }}h de présence
                            </p>
                        </div>

                        <Link
                            :href="route('slots.index')"
                            class="bg-white text-[#5D2E8E] px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-xs hover:scale-105 transition-all shadow-lg inline-flex items-center justify-center whitespace-nowrap"
                        >
                            <span class="mr-2 text-lg">🔍</span>
                            Voir les pôles
                        </Link>
                    </div>

                    <div
                        class="absolute right-[-20px] top-[-20px] text-[150px] opacity-10 rotate-12 pointer-events-none"
                    >
                        ⚽
                    </div>
                </div>

                <div
                    v-if="slots.length === 0"
                    class="text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-gray-200"
                >
                    <p class="text-gray-400 font-bold text-xl mb-6">
                        Tu n'as pas encore choisi de mission !
                    </p>
                    <Link
                        :href="route('slots.index')"
                        class="bg-[#5D2E8E] text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest hover:scale-105 transition inline-block"
                    >
                        Découvrir les pôles
                    </Link>
                </div>

                <div
                    v-else
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10"
                >
                    <div
                        v-for="slot in slots"
                        :key="slot.id"
                        :class="[
                            'rounded-[2.5rem] shadow-sm border-t-[8px] p-8 flex flex-col justify-between transition-all',
                            isFinished(slot.end_time)
                                ? 'bg-gray-50 border-gray-300 opacity-70 grayscale-[0.5]'
                                : 'bg-white border-green-500',
                        ]"
                    >
                        <div>
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <div class="mb-4">
                                        <div
                                            v-if="!isFinished(slot.end_time)"
                                            class="text-[12px] font-black text-green-600 bg-green-100 px-4 py-2 rounded-full mb-2 inline-block tracking-tighter uppercase"
                                        >
                                            <span
                                                class="text-green-600 bg-green-100 font-black uppercase tracking-wider"
                                            >
                                                Inscrit ✓
                                            </span>
                                        </div>

                                        <div
                                            v-else
                                            class="flex items-center gap-2 bg-gray-100 px-4 py-2 rounded-full"
                                        >
                                            <span
                                                class="text-gray-500 text-[12px] font-black uppercase tracking-wider"
                                            >
                                                Mission terminée
                                            </span>
                                        </div>
                                    </div>

                                    <Link
                                        :href="route('slots.show', slot.id)"
                                        class="hover:opacity-75 transition group"
                                    >
                                        <h3
                                            class="text-xl font-black text-gray-900 leading-tight pr-4 mt-1 group-hover:text-[#5D2E8E]"
                                        >
                                            {{ slot.title }}
                                        </h3>
                                    </Link>
                                    <p
                                        class="text-[#5D2E8E] text-xs font-black uppercase italic mt-2"
                                    >
                                        Pôle {{ slot.category }}
                                    </p>
                                </div>
                                <div
                                    class="bg-[#F5F3FF] text-[#5D2E8E] text-[11px] font-bold px-2 py-2 rounded-lg uppercase"
                                >
                                    {{ slot.users_count || 0 }} /
                                    {{ slot.max_volunteers || 30 }}
                                </div>
                            </div>

                            <div
                                class="space-y-1 mb-5 text-[20px] font-bold text-black border-l-4 border-purple-100 pl-4"
                            >
                                <p>
                                    <span class="mr-2">🗓️</span>
                                    {{ formatDate(slot.start_time) }}
                                </p>
                                <p>
                                    <span class="mr-2">🕒</span> à
                                    {{ formatTime(slot.start_time) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3">
                            <Link
                                :href="route('slots.show', slot.id)"
                                :class="[
                                    'w-full text-center py-4 rounded-2xl font-black uppercase tracking-widest transition shadow-lg',
                                    isFinished(slot.end_time)
                                        ? 'bg-gray-200 text-gray-400 cursor-not-allowed shadow-none' // Style grisé
                                        : 'bg-[#5D2E8E] text-white hover:bg-purple-700 shadow-purple-100', // Style normal
                                ]"
                            >
                                Voir les détails
                            </Link>

                            <Link
                                v-if="
                                    !isFinished(slot.end_time) &&
                                    canUnregister(slot.start_time)
                                "
                                :href="route('slots.unregister', slot.id)"
                                method="delete"
                                as="button"
                                class="w-full text-center bg-red-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-red-500 transition shadow-lg shadow-red-100"
                            >
                                Se désister
                            </Link>
                            <div
                                v-else
                                class="w-full text-center bg-gray-100 text-gray-400 py-3 rounded-2xl font-black uppercase italic border-2 border-dashed border-gray-200"
                            >
                                🔒 Désistement bloqué (-24h)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
