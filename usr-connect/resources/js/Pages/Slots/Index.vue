<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    slots: Array,
});
// 1. On définit les emojis (Vérifie bien les virgules !)
const categoryEmojis = {
    Buvette: "🍺",
    Caisse: "💸",
    Arbitrage: "⚖️",
    Sécurité: "🛡️",
    Accueil: "🤝",
};

// 2. On définit la fonction
const getEmoji = (category) => {
    return categoryEmojis[category] || "✨";
};

// On extrait juste les noms de catégories uniques et le nombre de missions dedans
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
        <div class="py-12 bg-gray-50 min-h-screen px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-12"
                >
                    <h1
                        class="text-4xl font-bold text-black-500 tracking-tight"
                    >
                        Planning Solida'Foot
                    </h1>
                    <Link
                        v-if="$page.props.auth?.user?.role === 'admin'"
                        :href="route('slots.create')"
                        class="w-full sm:w-auto text-center bg-[#5D2E8E] text-white px-3 py-3 rounded-2xl font-black uppercase text-sm tracking-widest shadow-md hover:bg-opacity-90 transition"
                    >
                        + NOUVELLE MISSION
                    </Link>
                </div>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <Link
                        v-for="category in categories"
                        :key="category.name"
                        :href="route('slots.category', category.name)"
                        class="group bg-white rounded-[2.5rem] shadow-sm border-t-[8px] border-[#5D2E8E] p-10 hover:shadow-xl transition-all hover:-translate-y-2 flex flex-col items-center text-center"
                    >
                        <div
                            class="bg-purple-50 w-20 h-20 rounded-full flex items-center justify-center mb-6 group-hover:bg-purple-100 transition"
                        >
                            <span class="text-3xl">{{
                                getEmoji(category.name)
                            }}</span>
                        </div>

                        <h2
                            class="text-2xl font-black text-gray-900 uppercase italic mb-2"
                        >
                            Pôle {{ category.name }}
                        </h2>

                        <p class="text-[#5D2E8E] font-bold">
                            {{ category.count }} mission{{
                                category.count > 1 ? "s" : ""
                            }}
                            disponible{{ category.count > 1 ? "s" : "" }}
                        </p>

                        <div
                            class="mt-8 bg-[#5D2E8E] text-white px-6 py-2 rounded-full font-black text-xs uppercase tracking-widest"
                        >
                            VOIR LE PLANNING
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
