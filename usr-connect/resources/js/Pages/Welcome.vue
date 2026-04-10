<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Bienvenue - USR Connect" />

    <div
        class="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-6"
    >
        <div
            class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl text-center"
        >
            <div class="mb-8 text-center">
                <img src="/images/logo.png" class="h-24 mx-auto mb-4" />
                <h1 class="text-5xl font-extrabold text-usr-purple mb-2">
                    USR Connect
                </h1>
                <p class="text-gray-600 text-lg">
                    La plateforme des bénévoles de l'Union Sportive Reventinoise
                </p>
            </div>

            <div v-if="$page.props.auth.user">
                <Link
                    :href="route('slots.index')"
                    class="block w-full bg-usr-purple text-white font-bold py-3 rounded-lg hover:bg-opacity-90 transition"
                >
                    Accéder au Planning
                </Link>
            </div>

            <div v-else class="space-y-4">
                <Link
                    :href="route('login')"
                    class="block w-full bg-usr-purple text-white font-bold py-3 rounded-lg hover:bg-opacity-90 transition"
                >
                    Se connecter
                </Link>

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="block w-full border-2 border-usr-purple text-usr-purple font-bold py-3 rounded-lg hover:bg-purple-50 transition"
                >
                    Créer un compte
                </Link>
            </div>
        </div>

        <footer class="mt-12 text-gray-400 text-sm">
            © 2026 USR Connect - Développé pour le plaisir et le sport ⚽
        </footer>
    </div>
</template>
