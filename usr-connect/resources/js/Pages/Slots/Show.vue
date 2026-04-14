<script setup>
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { computed } from "vue";

const props = defineProps({
    slot: Object,
});

const user = computed(() => usePage().props.auth.user);

// Vérifie si l'utilisateur est dans la liste des inscrits
const isRegistered = computed(() => {
    return props.slot.users.some((u) => u.id === user.value.id);
});

// Vérifie si la mission est complète
const isFull = computed(() => {
    return props.slot.users.length >= props.slot.max_volunteers;
});

const formatTime = (dateString) => {
    return new Date(dateString)
        .toLocaleTimeString("fr-FR", {
            hour: "2-digit",
            minute: "2-digit",
        })
        .replace(":", "h");
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("fr-FR", {
        weekday: "long",
        day: "numeric",
        month: "long",
    });
};

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
    if (confirm("ATTENTION : Veux-tu vraiment supprimer cette mission ?")) {
        router.delete(route("slots.destroy", props.slot.id));
    }
};
</script>

<template>
    <Head :title="slot.title" />

    <AuthenticatedLayout>
        <div class="py-12 bg-gray-50 min-h-screen px-4">
            <div class="max-w-3xl mx-auto">
                <div
                    class="flex flex-col items-center gap-6 w-full max-w-4xl mx-auto px-4 md:flex-row md:justify-between mt-8 mb-4"
                >
                    <div class="flex justify-center">
                        <Link
                            :href="route('slots.index')"
                            class="text-[#5D2E8E] font-bold flex items-center hover:underline"
                        >
                            ← Retour aux pôles
                        </Link>
                    </div>

                    <div
                        class="flex flex-row items-center justify-center gap-2 w-full md:w-auto"
                    >
                        <div v-if="user.role === 'admin'" class="flex gap-2">
                            <a
                                :href="route('slots.single.pdf', slot.id)"
                                class="px-3 py-3 text-[10px] bg-white border-2 border-gray-200 text-gray-700 font-bold rounded-xl text-center min-w-[70px]"
                            >
                                EXPORT PDF
                            </a>

                            <Link
                                :href="route('slots.edit', slot.id)"
                                class="px-3 py-3 text-[10px] bg-white border-2 border-gray-200 text-gray-700 font-bold rounded-xl text-center min-w-[70px]"
                            >
                                MODIFIER
                            </Link>

                            <button
                                @click="deleteSlot(slot.id)"
                                class="px-3 py-3 text-[10px] bg-white border-2 border-red-100 text-red-600 font-bold rounded-xl min-w-[70px]"
                            >
                                SUPPRIMER
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-[2.5rem] shadow-sm border-t-[8px] border-[#5D2E8E] overflow-hidden"
                >
                    <div class="p-8 sm:p-12">
                        <div
                            class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-8"
                        >
                            <h1
                                class="text-3xl font-black text-gray-900 uppercase italic leading-tight"
                            >
                                {{ slot.title }}
                            </h1>
                            <div
                                class="bg-[#F5F3FF] text-[#5D2E8E] px-4 py-2 rounded-xl font-black text-sm"
                            >
                                {{ slot.users.length }} /
                                {{ slot.max_volunteers }} places
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-4 mb-10">
                            <div
                                class="bg-gray-50 px-6 py-4 rounded-2xl flex items-center gap-3"
                            >
                                <span class="text-2xl">🗓️</span>
                                <span
                                    class="font-bold text-gray-700 capitalize"
                                    >{{ formatDate(slot.start_time) }}</span
                                >
                            </div>
                            <div
                                class="bg-gray-50 px-6 py-4 rounded-2xl flex items-center gap-3"
                            >
                                <span class="text-2xl">🕒</span>
                                <span class="font-bold text-gray-700">
                                    {{ formatTime(slot.start_time) }} -
                                    {{ formatTime(slot.end_time) }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-12">
                            <h3
                                class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 italic"
                            >
                                Description de la mission
                            </h3>
                            <p class="text-gray-600 text-lg leading-relaxed">
                                {{
                                    slot.description ||
                                    "Aucune description fournie."
                                }}
                            </p>
                        </div>

                        <button
                            @click="handleSubscription"
                            :disabled="!isRegistered && isFull"
                            :class="[
                                'w-full py-5 rounded-2xl font-black uppercase tracking-[0.2em] transition transform active:scale-[0.98] shadow-lg',
                                isRegistered
                                    ? 'bg-red-600 text-white hover:bg-red-700 shadow-red-100'
                                    : isFull
                                      ? 'bg-gray-200 text-gray-400 cursor-not-allowed shadow-none'
                                      : 'bg-[#5D2E8E] text-white hover:bg-[#4a2472] shadow-purple-100',
                            ]"
                        >
                            <span v-if="isRegistered">Se désister</span>
                            <span v-else-if="isFull">Mission complète</span>
                            <span v-else>Rejoindre l'équipe</span>
                        </button>
                    </div>

                    <div
                        class="bg-gray-50 p-8 sm:p-12 border-t border-gray-100"
                    >
                        <h3
                            class="font-black text-gray-900 uppercase italic mb-8 flex items-center gap-3"
                        >
                            <span class="text-2xl">🙌</span>
                            L'équipe actuelle
                        </h3>

                        <div
                            v-if="slot.users.length > 0"
                            class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                        >
                            <div
                                v-for="volunteer in slot.users"
                                :key="volunteer.id"
                                class="bg-white p-4 rounded-2xl flex items-center shadow-sm border border-gray-100"
                            >
                                <div
                                    class="h-10 w-10 rounded-full bg-gradient-to-br from-[#5D2E8E] to-purple-400 text-white flex items-center justify-center font-black text-sm mr-4 shrink-0"
                                >
                                    {{ volunteer.name.charAt(0).toUpperCase() }}
                                </div>
                                <span
                                    class="text-gray-700 font-bold truncate"
                                    >{{ volunteer.name }}</span
                                >
                            </div>
                        </div>

                        <div
                            v-else
                            class="text-center py-8 text-gray-400 font-bold italic border-2 border-dashed border-gray-200 rounded-3xl"
                        >
                            Aucun bénévole pour le moment...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
