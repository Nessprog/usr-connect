<script setup>
import { ref, onMounted, onUnmounted } from "vue";

const isVisible = ref(false);

const checkScroll = () => {
    // Affiche le bouton après 300px de scroll
    isVisible.value = window.scrollY > 300;
};

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
};

onMounted(() => window.addEventListener("scroll", checkScroll));
onUnmounted(() => window.removeEventListener("scroll", checkScroll));
</script>

<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="translate-y-10 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-10 opacity-0"
    >
        <button
            v-if="isVisible"
            @click="scrollToTop"
            class="fixed bottom-8 right-8 bg-[#5D2E8E] text-white p-4 rounded-full shadow-2xl hover:bg-[#4a2472] hover:scale-110 transition-all z-50 flex items-center justify-center"
            title="Retour en haut"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="3"
                    d="M5 15l7-7 7 7"
                />
            </svg>
        </button>
    </Transition>
</template>
