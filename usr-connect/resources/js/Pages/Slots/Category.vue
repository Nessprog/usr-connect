<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    slots: Array,
    categoryName: String,
});

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
    <Head :title="'Pôle ' + categoryName" />

    <AuthenticatedLayout>
        <div class="py-12 bg-gray-50 min-h-screen px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="mb-12">
                    <Link
                        :href="route('slots.index')"
                        class="text-[#5D2E8E] font-bold flex items-center mb-6 hover:underline"
                    >
                        ← Retour aux pôles
                    </Link>

                    <div
                        class="flex flex-col md:flex-row md:items-center justify-between gap-6"
                    >
                        <h1
                            class="text-4xl font-black text-gray-900 tracking-tight uppercase italic m-0"
                        >
                            Pôle {{ categoryName }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-4">
                            <Link
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
                                v-if="categoryName"
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
                                PDF
                            </a>
                        </div>
                    </div>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10"
                >
                    <div
                        v-for="slot in slots"
                        :key="slot.id"
                        class="bg-white rounded-[2.5rem] shadow-sm border-t-[8px] border-[#5D2E8E] p-8 flex flex-col justify-between"
                    >
                        <div>
                            <div class="flex justify-between items-start mb-6">
                                <Link
                                    :href="route('slots.show', slot.id)"
                                    class="hover:opacity-75 transition"
                                >
                                    <h3
                                        class="text-xl font-black text-gray-900 leading-tight pr-4"
                                    >
                                        {{ slot.title }}
                                    </h3>
                                </Link>
                                <div
                                    class="bg-[#F5F3FF] text-[#5D2E8E] text-[11px] font-bold px-3 py-2 rounded-lg uppercase"
                                >
                                    {{ slot.users_count || 0 }} /
                                    {{ slot.max_volunteers || 30 }}
                                </div>
                            </div>

                            <div class="mb-6">
                                <div
                                    class="flex justify-between items-center mb-2"
                                >
                                    <span
                                        class="text-[10px] font-black text-gray-400 uppercase italic"
                                        >REMPLISSAGE</span
                                    >
                                    <span
                                        class="text-sm font-black text-[#5D2E8E] italic"
                                    >
                                        {{
                                            Math.round(
                                                ((slot.users_count || 0) /
                                                    (slot.max_volunteers ||
                                                        30)) *
                                                    100,
                                            )
                                        }}%
                                    </span>
                                </div>
                                <div
                                    class="w-full bg-gray-100 rounded-full h-2"
                                >
                                    <div
                                        class="bg-[#5D2E8E] h-2 rounded-full transition-all duration-100"
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
                                class="space-y-1 mb-8 text-[20px] font-bold text-black normal"
                            >
                                <p>
                                    <span class="mr-2">🗓️</span>
                                    {{ formatDate(slot.start_time) }}
                                </p>
                                <p>
                                    <span class="mr-2">🕒</span>
                                    {{ formatTime(slot.start_time) }}
                                </p>
                            </div>
                        </div>

                        <Link
                            v-if="slot.is_registered"
                            :href="route('slots.unregister', slot.id)"
                            method="delete"
                            as="button"
                            class="w-full text-center bg-red-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-red-500 transition"
                        >
                            SE DÉSISTER
                        </Link>
                        <Link
                            v-else
                            :href="route('slots.register', slot.id)"
                            method="post"
                            as="button"
                            class="w-full text-center bg-[#5D2E8E] text-white py-4 rounded-full font-black uppercase tracking-[0.2em] hover:bg-opacity-90 transition"
                        >
                            REJOINDRE
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
