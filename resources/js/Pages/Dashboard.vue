<script setup>
import { ref } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    users: Array,
    roles: Array,
});

const selectedRoles = ref({});
const showingNavigationDropdown = ref(false);

props.users.forEach(user => {
    selectedRoles.value[user.id] = user.roles.length > 0 ? user.roles[0].name : '';
});

const page = usePage();
const permissionNames = page.props.auth.user.can;

const assignRole = (userId) => {
    if (!selectedRoles.value[userId]) {
        alert('Please select a role first.');
        return;
    }

    const form = useForm({
        role: selectedRoles.value[userId],
    });

    form.post(route('users.assignRole', userId), {
        preserveScroll: true,
        onSuccess: () => {
            console.log(`Role assigned to user ${userId}`);
        },
        onError: (errors) => {
            console.error(errors);
        }
    });
};


</script>

<template>

    <Head title="Dashboard" />

    <div>
        <div class="min-h-screen bg-gray-100">
            <!-- Navigation -->
            <nav class="border-b border-gray-100 bg-white">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">

                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink  :href="route('dashboard')" :active="route().current('dashboard')">Dashboard
                                </NavLink>
                                <NavLink  v-if="permissionNames.includes('permission')" :href="route('permissions.index')"
                                    :active="route().current('permissions.index')">Permissions</NavLink>
                                <NavLink v-if="permissionNames.includes('assign roles')" :href="route('roles.index')"
                                    :active="route().current('roles.index')">Roles</NavLink>
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                                {{ $page.props.auth.user.name }}
                                                <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger Menu -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path
                                        :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden">
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard
                        </ResponsiveNavLink>
                    </div>
                    <div class="border-t border-gray-200 pb-1 pt-4">
                        <div class="px-4">
                            <div class="text-base font-medium text-gray-800">{{ $page.props.auth.user.name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Header -->
            <header class="bg-white shadow">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        User Management Dashboard
                    </h2>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <div class="py-12">
                    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                        <table class="table-auto w-full border-collapse border border-gray-300">
                            <thead>
                                <tr v-if="permissionNames.includes('view users')" class="bg-gray-100">
                                    <th class="border px-4 py-2">Name</th>
                                    <th class="border px-4 py-2">Email</th>
                                    <th class="border px-4 py-2">Roles</th>
                                    <th v-if="permissionNames.includes('assign roles')" class="border px-4 py-2">Assign
                                        Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="permissionNames.includes('view users')" v-for="user in users"
                                    :key="user.id" class="hover:bg-gray-50">
                                    <td class="border px-4 py-2">{{ user.name }}</td>
                                    <td class="border px-4 py-2">{{ user.email }}</td>
                                    <td class="border px-4 py-2">
                                        <span v-if="user.roles.length > 0">
                                            {{user.roles.map(r => r.name).join(', ')}}
                                        </span>
                                        <span v-else class="italic text-gray-500">No roles assigned</span>
                                    </td>
                                    <td v-if="permissionNames.includes('assign roles')" class="border px-4 py-2">
                                        <form @submit.prevent="assignRole(user.id)">
                                            <select v-model="selectedRoles[user.id]" class="border rounded px-2 py-1"
                                                multiple>
                                                <option value="" disabled>Select role</option>
                                                <option v-for="role in roles" :key="role.id" :value="role.name">{{
                                                    role.name }}
                                                </option>
                                            </select>
                                            <button type="submit"
                                                class="ml-2 px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
                                                :disabled="!selectedRoles[user.id]">
                                                Assign
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
table {
    border-collapse: collapse;
}

th,
td {
    border: 1px solid #ddd;
    padding: 8px;
}
</style>
