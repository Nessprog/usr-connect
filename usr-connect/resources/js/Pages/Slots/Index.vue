<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    slots: Array,
});

// Formatage : mercredi 20 mai
const formatDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return date.toLocaleDateString("fr-FR", {
        weekday: "long",
        day: "numeric",
        month: "long",
    });
};

// Formatage : à 18:00
const formatTime = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return date
        .toLocaleTimeString("fr-FR", { hour: "2-digit", minute: "2-digit" })
        .replace(":", "h");
};
</script>

<template>
    <Head title="Planning" />

    <AuthenticatedLayout>
        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-10">
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                        Planning Solida'Foot
                    </h1>
                    <Link
                        v-if="$page.props.auth?.user?.role === 'admin'"
                        :href="route('slots.create')"
                        class="bg-[#5D2E8E] text-white px-6 py-2 rounded-xl font-black uppercase text-sm tracking-widest shadow-md hover:bg-opacity-90 transition"
                    >
                        + NOUVELLE MISSION
                    </Link>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <div
                        v-for="slot in slots"
                        :key="slot.id"
                        class="bg-white rounded-[2rem] shadow-sm border-t-[8px] border-[#5D2E8E] p-8 relative flex flex-col justify-between"
                    >
                        <div>
                            <div class="flex justify-between items-start mb-6">
                                <Link
                                    :href="route('slots.show', slot.id)"
                                    class="hover:opacity-70 transition"
                                >
                                    <h3
                                        class="text-xl font-black text-gray-900 leading-tight"
                                    >
                                        {{ slot.title }}
                                    </h3>
                                </Link>
                                <div
                                    class="bg-[#F5F3FF] text-[#5D2E8E] text-[11px] font-bold px-3 py-2 rounded-lg whitespace-nowrap uppercase tracking-tighter"
                                >
                                    {{ slot.users_count || 0 }} /
                                    {{ slot.max_volunteers || 30 }} bénévoles
                                </div>
                            </div>

                            <div class="mb-6">
                                <div
                                    class="flex justify-between items-center mb-2"
                                >
                                    <span
                                        class="text-[10px] font-black text-black uppercase tracking-widest italic"
                                        >REMPLISSAGE</span
                                    >
                                </div>
                                <div
                                    class="w-full bg-gray-100 rounded-full h-2"
                                >
                                    <div
                                        class="bg-[#5D2E8E] h-2 rounded-full transition-all duration-1000"
                                        :style="{
                                            width:
                                                Math.min(
                                                    ((slot.users_count || 0) /
                                                        (slot.max_volunteers ||
                                                            30)) *
                                                        100,
                                                    100,
                                                ) + '%',
                                        }"
                                    ></div>
                                </div>
                            </div>

                            <div
                                class="space-y-1 mb-8 text-[15px] font-medium text-black normal-case"
                            >
                                <p class="flex items-center">
                                    <span class="mr-2">🗓️</span>
                                    {{ formatDate(slot.start_time) }}
                                </p>
                                <p class="flex items-center">
                                    <span class="mr-2">🕒</span> à
                                    {{ formatTime(slot.start_time) }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div v-if="slot.is_registered">
                                <Link
                                    :href="route('slots.unregister', slot.id)"
                                    method="delete"
                                    as="button"
                                    class="w-full text-center bg-red-600 text-white py-4 rounded-2xl font-bold text-base uppercase tracking-widest hover:bg-red-500 transition shadow-sm"
                                >
                                    SE DÉSISTER
                                </Link>
                            </div>

                            <div v-else>
                                <Link
                                    :href="route('slots.register', slot.id)"
                                    method="post"
                                    as="button"
                                    class="w-full text-center bg-[#5D2E8E] text-white py-4 rounded-2xl font-black text-base uppercase tracking-widest hover:bg-opacity-90 transition shadow-md"
                                >
                                    REJOINDRE
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
