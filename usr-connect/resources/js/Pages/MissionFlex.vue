<template>
    <div class="p-6 bg-gray-50 min-h-screen">
        <h1 class="text-2xl font-bold mb-6 text-[#5D2E8E]">
            Test : Sélection par Blocs
        </h1>

        <div
            class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 max-w-md"
        >
            <h3 class="text-xl font-black text-gray-800 mb-4">
                Choisir mon créneau
            </h3>

            <p class="text-xs text-gray-400 mb-4 italic uppercase font-bold">
                Cliquez sur votre heure de début et de fin :
            </p>

            <div class="grid grid-cols-4 gap-2 mb-8">
                <button
                    v-for="hour in hoursRange"
                    :key="hour"
                    @click="selectHour(hour)"
                    :class="[
                        'py-2 rounded-xl text-sm font-bold transition-all border',
                        isInRange(hour)
                            ? 'bg-[#5D2E8E] text-white border-[#5D2E8E]'
                            : 'bg-white text-gray-800 border-gray-600 hover:border-purple-400',
                    ]"
                >
                    {{ hour }}h
                </button>
            </div>

            <div
                v-if="selection.start"
                class="mb-8 p-4 bg-purple-50 rounded-2xl border border-purple-100"
            >
                <div class="flex justify-between items-center">
                    <div>
                        <p
                            class="text-[10px] text-purple-400 uppercase font-black"
                        >
                            Votre présence
                        </p>
                        <p class="text-lg font-black text-[#5D2E8E]">
                            {{ selection.start }}h
                            <span v-if="selection.end"
                                >à {{ selection.end }}h</span
                            >
                        </p>
                    </div>
                    <button
                        @click="reset"
                        class="text-xs text-red-400 font-bold underline"
                    >
                        Effacer
                    </button>
                </div>
            </div>

            <button
                :disabled="!selection.start || !selection.end"
                class="w-full py-4 bg-[#5D2E8E] text-white rounded-2xl font-black uppercase tracking-widest disabled:bg-gray-200 shadow-lg"
            >
                Confirmer ce créneau
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

// On définit la plage de la mission (ex: de 8h à 19h)
const hoursRange = [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];

const selection = ref({
    start: null,
    end: null,
});

const selectHour = (hour) => {
    if (
        !selection.value.start ||
        (selection.value.start && selection.value.end)
    ) {
        // Nouveau choix ou on recommence
        selection.value.start = hour;
        selection.value.end = null;
    } else {
        // On définit la fin
        if (hour > selection.value.start) {
            selection.value.end = hour;
        } else {
            // Si on clique sur une heure avant le début, elle devient le nouveau début
            selection.value.start = hour;
        }
    }
};

const isInRange = (hour) => {
    if (!selection.value.start) return false;
    if (!selection.value.end) return hour === selection.value.start;
    return hour >= selection.value.start && hour <= selection.value.end;
};

const reset = () => {
    selection.value.start = null;
    selection.value.end = null;
};
</script>
