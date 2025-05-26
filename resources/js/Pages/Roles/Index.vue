<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const props = defineProps({
    roles: Array,
    permissions: Array,
    canViewUsers: Boolean,
    canAssignRoles: Boolean,
    canPermission: Boolean,
    users: Array,

});



const form = useForm({
    name: '',
    permissions: [],
});

function submit() {
    form.post('/roles');
}

function destroyRole(id) {
    if (confirm("Are you sure?")) {
        form.delete(`/roles/${id}`);
    }
}
</script>

<template>

    <Head title="Roles" />



    <div>
        <div class="min-h-screen bg-gray-100">

            <nav class="border-b border-gray-100 bg-white">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard
                                </NavLink>
                                <NavLink :href="route('permissions.index')"
                                    :active="route().current('permissions.index')">
                                    Permissions</NavLink>
                                <NavLink :href="route('roles.index')" :active="route().current('roles.index')">Roles
                                </NavLink>
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
            <h1 class="text-xl font-bold mb-4">Roles Management</h1>
            <div>
                <!-- Add Role Form -->
                <form @submit.prevent="submit" class="mb-6 space-y-4">
                    <input v-model="form.name" class="border p-2 w-full" placeholder="Role name" />
                    <div>
                        <label class="font-semibold mb-2 block">Assign Permissions:</label>
                        <div class="flex flex-wrap gap-2">
                            <label v-for="permission in permissions" :key="permission.id"
                                class="inline-flex items-center space-x-2">
                                <input type="checkbox" :value="permission.id" v-model="form.permissions"
                                    class="form-checkbox" />
                                <span>{{ permission.name }}</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded" :disabled="form.processing">
                        Add Role
                    </button>
                </form>

                <!-- Roles Table -->
                <table class="w-full table-auto border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2">ID</th>
                            <th class="border px-4 py-2">Name</th>
                            <th class="border px-4 py-2">Permissions</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="role in roles" :key="role.id">
                            <td class="border px-4 py-2">{{ role.id }}</td>
                            <td class="border px-4 py-2">{{ role.name }}</td>
                            <td class="border px-4 py-2">{{role.permissions.map(p => p.name).join(', ')}}</td>
                            <td class="border px-4 py-2 space-x-2">
                                <button @click="$inertia.visit(`/roles/${role.id}/edit`)"
                                    class="bg-blue-500 text-white px-3 py-1 rounded">
                                    Edit
                                </button>
                                <button @click="destroyRole(role.id)" class="bg-red-500 text-white px-3 py-1 rounded">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>
    </div>
</template>
