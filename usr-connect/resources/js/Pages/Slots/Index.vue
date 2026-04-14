<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    slots: Array,
});

// Configuration des icônes par pôle
const categoryEmojis = {
    Animation: "🎉",
    Buvette: "🍺",
    Caisse: "💸",
    HDG: "🍨",
    Infirmerie: "🚑",
    Logistique: "🛠️",
    Sportif: "⚽",
    Restauration: "🥘",
    Parking: "🚗",
};

const getEmoji = (category) => categoryEmojis[category] || "✨";

// Calcul des catégories uniques et du nombre de missions
const categories = computed(() => {
    if (!props.slots) return [];

    const counts = props.slots.reduce((acc, slot) => {
        const cat = slot.category || "Autres";
        acc[cat] = (acc[cat] || 0) + 1;
        return acc;
    }, {});

    return Object.keys(counts).map((name) => ({
        name,
        count: counts[name],
    }));
});
</script>

<template>
    <Head title="Pôles de Mission" />

    <AuthenticatedLayout>
        <div
            class="py-12 bg-gray-50 min-h-screen px-4 sm:px-6 lg:px-8 text-gray-900"
        >
            <div class="max-w-7xl mx-auto">
                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 mb-16"
                >
                    <div>
                        <h1
                            class="text-4xl font-black uppercase italic tracking-tighter"
                        >
                            Planning Solida'Foot
                        </h1>
                        <p
                            class="text-gray-500 font-bold mt-2 uppercase text-xs tracking-[0.2em]"
                        >
                            Sélectionnez un pôle pour vous inscrire
                        </p>
                    </div>

                    <Link
                        v-if="$page.props.auth?.user?.role === 'admin'"
                        :href="route('slots.create')"
                        class="w-full sm:w-auto text-center bg-[#5D2E8E] text-white px-6 py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg shadow-black-100 hover:bg-[#4a2472] transition"
                    >
                        + NOUVELLE MISSION
                    </Link>
                </div>

                <div
                    v-if="categories.length > 0"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10"
                >
                    <Link
                        v-for="category in categories"
                        :key="category.name"
                        :href="route('slots.category', category.name)"
                        class="group bg-white rounded-[2.5rem] shadow-sm border-t-[8px] border-[#5D2E8E] p-10 hover:shadow-xl transition-all hover:-translate-y-2 flex flex-col items-center text-center relative overflow-hidden"
                    >
                        <div
                            class="absolute top-6 right-6 bg-gray-100 text-gray-500 text-[10px] font-black px-3 py-1 rounded-full uppercase italic"
                        >
                            {{ category.count }} Mission{{
                                category.count > 1 ? "s" : ""
                            }}
                        </div>

                        <div
                            class="bg-purple-50 w-24 h-24 rounded-full flex items-center justify-center mb-8 group-hover:scale-110 transition duration-500 ease-out shadow-inner"
                        >
                            <span
                                class="text-4xl group-hover:rotate-12 transition transform"
                                >{{ getEmoji(category.name) }}</span
                            >
                        </div>

                        <h2
                            class="text-2xl font-black uppercase italic mb-2 tracking-tight"
                        >
                            Pôle {{ category.name }}
                        </h2>

                        <p
                            class="text-[#5D2E8E] font-bold text-sm uppercase tracking-wide"
                        >
                            {{ category.count }} créneau{{
                                category.count > 1 ? "x" : ""
                            }}
                            à pourvoir
                        </p>

                        <div
                            class="mt-8 bg-[#5D2E8E] text-white px-8 py-3 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] group-hover:bg-[#4a2472] transition shadow-md"
                        >
                            VOIR LE PLANNING
                        </div>
                    </Link>
                </div>

                <div
                    v-else
                    class="text-center py-20 bg-white rounded-[3rem] shadow-inner border-2 border-dashed border-gray-200"
                >
                    <span class="text-5xl block mb-4">🙌</span>
                    <h3
                        class="text-xl font-bold text-gray-400 uppercase italic"
                    >
                        Aucune mission prévue pour le moment
                    </h3>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
