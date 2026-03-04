<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";

const props = defineProps({
    slots: Array,
    categoryName: String,
});

const page = usePage();
<div v-if="$page.props.auth?.user?.role === 'admin'">

const isRegistered = (slot) => slot.users.some((u) => u.id === userId);

const handleSubscription = (slot) => {
    if (isRegistered(slot)) {
        if (confirm("Se désister de cette mission ?")) {
            router.delete(route("slots.unregister", slot.id));
        }
    } else {
        router.post(route("slots.register", slot.id));
    }
};
</script>

<template>
    <Head :title="'Pôle ' + categoryName" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center mt-4">
                <h2
                    class="font-bold text-3xl text-[#5D2E8E] leading-tight uppercase tracking-tight"
                >
                    Missions {{ category }}
                </h2>

                <Link
                    :href="route('slots.index')"
                    class="text-gray-500 hover:text-[#5D2E8E] flex items-center text-sm font-bold transition group"
                >
                    <span
                        class="mr- group-hover:-translate-x-1 transition-transform"
                        >←</span
                    >
                    Retour au choix du pôle
                </Link>
            </div>
        </template>

        <div class="py-12 bg-gray-100 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <div
                        v-for="slot in slots"
                        :key="slot.id"
                        class="bg-white rounded-xl shadow-md overflow-hidden border-t-4 border-[#5D2E8E]"
                    >
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <Link
                                    :href="route('slots.show', slot.id)"
                                    class="hover:opacity-75 transition"
                                >
                                    <h3
                                        class="text-lg font-bold text-gray-900 leading-tight"
                                    >
                                        {{ slot.title }}
                                    </h3>
                                </Link>

                                <span
                                    class="bg-purple-100 text-[#5D2E8E] text-xs font-bold px-3 py-1 rounded-md flex-shrink-0"
                                >
                                    {{ slot.users_count }} /
                                    {{ slot.max_volunteers }}
                                </span>
                            </div>

                            <div class="mt-4 mb-6">
                                <div
                                    class="flex justify-between items-center mb-1 text-xs font-bold uppercase tracking-tighter"
                                >
                                    <span class="text-gray-500 italic"
                                        >Remplissage</span
                                    >
                                    <span
                                        :class="
                                            slot.users_count >=
                                            slot.max_volunteers
                                                ? 'text-red-600'
                                                : 'text-[#5D2E8E]'
                                        "
                                    >
                                    </span>
                                </div>
                                <div
                                    class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden shadow-inner"
                                >
                                    <div
                                        class="h-full rounded-full transition-all duration-700 ease-out shadow-sm"
                                        :class="
                                            slot.users_count >=
                                            slot.max_volunteers
                                                ? 'bg-red-500'
                                                : 'bg-[#5D2E8E]'
                                        "
                                        :style="{
                                            width:
                                                Math.min(
                                                    (slot.users_count /
                                                        slot.max_volunteers) *
                                                        100,
                                                    100,
                                                ) + '%',
                                        }"
                                    ></div>
                                </div>
                            </div>

                            <div
                                class="flex items-center text-gray-900 font-medium text-base"
                            >
                                📅
                                {{
                                    new Date(
                                        slot.start_time,
                                    ).toLocaleDateString("fr-FR", {
                                        weekday: "long",
                                        day: "numeric",
                                        month: "long",
                                    })
                                }}<br />
                                🕒 à
                                {{
                                    new Date(
                                        slot.start_time,
                                    ).toLocaleTimeString("fr-FR", {
                                        hour: "2-digit",
                                        minute: "2-digit",
                                    })
                                }}
                            </div>

                            <button
                                @click="handleSubscription(slot)"
                                class="w-full my-5 py-3 rounded-lg font-bold uppercase transition-all shadow-md"
                                :class="
                                    isRegistered(slot)
                                        ? 'bg-red-600 text-white border border-red-600 hover:bg-red-600'
                                        : 'bg-[#5D2E8E] text-white hover:bg-[#4a2472]'
                                "
                            >
                                {{
                                    isRegistered(slot)
                                        ? "Se désister"
                                        : "Rejoindre"
                                }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
