<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    slots: Array,
});
</script>

<template>
    <Head title="Pôle Infirmerie" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pôle Infirmerie
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                >
                    <h3 class="text-lg font-medium mb-4">
                        Missions médicales disponibles
                    </h3>

                    <div
                        v-if="slots.length === 0"
                        class="text-gray-500 text-center py-4"
                    >
                        Aucune mission d'infirmerie prévue pour le moment.
                    </div>

                    <div v-else class="grid gap-4">
                        <div
                            v-for="slot in slots"
                            :key="slot.id"
                            class="border p-4 rounded-xl flex justify-between items-center"
                        >
                            <div>
                                <p class="font-bold text-lg">
                                    {{ slot.title }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ slot.description }}
                                </p>
                            </div>
                            <Link
                                :href="route('slots.show', slot.id)"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg"
                            >
                                Voir la mission
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
