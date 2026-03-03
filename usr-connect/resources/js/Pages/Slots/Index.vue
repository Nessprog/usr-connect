<script setup>
import { Head, router, usePage, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    slots: Array,
});

const page = usePage();
const userId = page.props.auth.user.id;

// Vérifier si l'utilisateur est inscrit
const isRegistered = (slot) => {
    return slot.users.some((user) => user.id === userId);
};

// Gérer l'inscription ou la désinscription
const handleSubscription = (slot) => {
    if (isRegistered(slot)) {
        if (confirm("Voulez-vous vraiment vous désister de ce créneau ?")) {
            router.post(
                route("slots.unregister", slot.id),
                {},
                { preserveScroll: true },
            );
        }
    } else {
        router.post(
            route("slots.register", slot.id),
            {},
            { preserveScroll: true },
        );
    }
};
</script>

<template>
    <Head title="Planning Bénévoles" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-usr-purple leading-tight">
                Planning du Solida'Foot - US Reventin
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <div
                        v-for="slot in slots"
                        :key="slot.id"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-usr-purple transition hover:shadow-md"
                    >
                        <Link
                            :href="route('slots.show', slot.id)"
                            class="text-lg font-bold text-gray-900 hover:text-usr-purple transition">
                            {{ slot.title }}
                        </Link>
                        <p class="text-gray-600 text-sm mt-2 h-12">
                            {{ slot.description }}
                        </p>

                        <div class="mt-6">
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700"
                                    >Remplissage</span
                                >
                                <span class="text-sm font-medium text-gray-700"
                                    >{{ slot.users_count }} /
                                    {{ slot.max_volunteers }}</span
                                >
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div
                                    class="bg-usr-purple h-2.5 rounded-full transition-all duration-500"
                                    :style="{
                                        width:
                                            (slot.users_count /
                                                slot.max_volunteers) *
                                                100 +
                                            '%',
                                    }"
                                ></div>
                            </div>
                        </div>

                        <button
                            @click="handleSubscription(slot)"
                            :class="
                                isRegistered(slot)
                                    ? 'bg-red-500 hover:bg-red-600'
                                    : 'bg-usr-purple hover:bg-opacity-90'
                            "
                            class="w-full mt-6 text-white font-bold py-2 px-4 rounded transition shadow-md"
                        >
                            {{
                                isRegistered(slot)
                                    ? "Se désister"
                                    : "Rejoindre l'équipe"
                            }}
                        </button>
                    </div>

                    <div
                        v-if="slots.length === 0"
                        class="text-center py-10 bg-white rounded-lg col-span-full shadow"
                    >
                        <p class="text-gray-500 italic">
                            Aucun créneau n'est disponible pour le moment.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
