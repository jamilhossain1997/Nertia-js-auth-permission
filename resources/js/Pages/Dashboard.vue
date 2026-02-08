<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const page = usePage();
const permissionNames = page.props.auth.permissions || [];
</script>

<template>
<nav class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
        <Link :href="route('dashboard')">
            <ApplicationLogo class="h-9 w-auto" />
        </Link>

        <div class="hidden sm:flex space-x-4">
            <NavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</NavLink>
            <NavLink v-if="permissionNames.includes('permissions.index')" :href="route('permissions.index')" :active="route().current('permissions.index')">Permissions</NavLink>
            <NavLink v-if="permissionNames.includes('roles.index')" :href="route('roles.index')" :active="route().current('roles.index')">Roles</NavLink>
            <NavLink v-if="permissionNames.includes('users.index')" :href="route('users.index')" :active="route().current('users.index')">Users</NavLink>
        </div>

        <Dropdown>
            <template #trigger>
                <button>{{ page.props.auth.user.name }}</button>
            </template>
            <template #content>
                <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
            </template>
        </Dropdown>
    </div>
</nav>
</template>
