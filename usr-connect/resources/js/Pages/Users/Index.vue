<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    users: Array,
});

const changeRole = (user, newRole) => {
    router.patch(
        route("users.update-role", user.id),
        {
            role: newRole,
        },
        {
            preserveScroll: true,
        },
    );
};

const deleteUser = (user) => {
    if (confirm("Supprimer ce membre ?")) {
        router.delete(route("users.destroy", user.id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Liste des membres" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-black text-2xl uppercase italic text-gray-800 leading-tight"
            >
                Membres de l'USR Connect
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center md:text-left">
                        <p class="mb-6 text-gray-600">
                            Total : <strong>{{ users.length }}</strong> membres
                            inscrits.
                        </p>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Nom
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell"
                                        >
                                            Email
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Téléphone
                                        </th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Missions
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Rôle
                                        </th>
                                        <th
                                            v-if="
                                                $page.props.auth.user.role ===
                                                'admin'
                                            "
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr v-for="user in users" :key="user.id">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap font-medium"
                                        >
                                            {{ user.name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-gray-500 hidden md:table-cell"
                                        >
                                            {{ user.email }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            <template
                                                v-if="
                                                    $page.props.auth.user
                                                        .role === 'admin'
                                                "
                                            >
                                                <a
                                                    v-if="user.phone"
                                                    :href="'tel:' + user.phone"
                                                    class="text-indigo-600 hover:underline"
                                                >
                                                    {{ user.phone }}
                                                </a>
                                                <span
                                                    v-else
                                                    class="text-gray-400 italic text-xs"
                                                    >Non renseigné</span
                                                >
                                            </template>

                                            <template v-else>
                                                <span
                                                    class="text-gray-400 italic text-xs"
                                                    >Masqué</span
                                                >
                                            </template>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-center"
                                        >
                                            <span
                                                class="px-3 py-1 rounded-full bg-purple-100 text-usr-purple font-bold"
                                            >
                                                {{ user.slots_count }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right"
                                        >
                                            <span
                                                :class="
                                                    user.role === 'admin'
                                                        ? 'text-red-600 font-bold'
                                                        : 'text-gray-500'
                                                "
                                            >
                                                {{
                                                    user.role === "admin"
                                                        ? "Admin"
                                                        : user.role ===
                                                            "volunteer"
                                                          ? "Bénévole"
                                                          : "Infirmier"
                                                }}
                                            </span>
                                        </td>
                                        <td
                                            v-if="
                                                $page.props.auth.user.role ===
                                                'admin'
                                            "
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                        >
                                            <div
                                                v-if="
                                                    user.id !==
                                                    $page.props.auth.user.id
                                                "
                                                class="flex justify-end items-center space-x-3"
                                            >
                                                <select
                                                    @change="
                                                        (e) =>
                                                            changeRole(
                                                                user,
                                                                e.target.value,
                                                            )
                                                    "
                                                    class="text-xs border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                >
                                                    <option
                                                        value="volunteer"
                                                        :selected="
                                                            user.role ===
                                                            'volunteer'
                                                        "
                                                    >
                                                        Bénévole
                                                    </option>
                                                    <option
                                                        value="infirmier"
                                                        :selected="
                                                            user.role ===
                                                            'infirmier'
                                                        "
                                                    >
                                                        Infirmier
                                                    </option>
                                                    <option
                                                        value="admin"
                                                        :selected="
                                                            user.role ===
                                                            'admin'
                                                        "
                                                    >
                                                        Admin
                                                    </option>
                                                </select>

                                                <button
                                                    @click="deleteUser(user)"
                                                    class="text-red-600 hover:text-red-900 ml-2"
                                                >
                                                    Supprimer
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
