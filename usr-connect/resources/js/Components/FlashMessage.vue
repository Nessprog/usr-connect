<script setup>
import { ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const show = ref(false);
const message = ref("");
const type = ref("success"); // 'success' ou 'error'

watch(
    () => page.props.flash,
    (flash) => {
        if (flash.message) {
            message.value = flash.message;
            type.value = "success";
            triggerShow();
        } else if (flash.error) {
            message.value = flash.error;
            type.value = "error";
            triggerShow();
        }
    },
    { deep: true },
);

const triggerShow = () => {
    show.value = true;
    setTimeout(() => (show.value = false), 4000);
};
</script>

<template>
    <Transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="show" class="fixed top-5 right-5 z-50 shadow-lg">
            <div
                :class="[
                    'px-6 py-3 rounded-lg font-bold flex items-center space-x-2 text-white',
                    type === 'success' ? 'bg-green-600' : 'bg-red-600',
                ]"
            >
                <span>{{ type === "success" ? "✅" : "⚠️" }}</span>
                <span>{{ message }}</span>
            </div>
        </div>
    </Transition>
</template>
