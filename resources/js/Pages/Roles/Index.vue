<script setup>
import { Head, useForm ,usePage} from '@inertiajs/vue3'

const props = defineProps({
    roles: Array,
    permissions: Object,
})



const form = useForm({
    name: '',
    permissions: []
})




function submit() {
    form.post('/roles')
}

function destroyRole(id) {
    if (confirm('Are you sure?')) {
        form.delete(`/roles/${id}`)
    }
}



function getAllPermissionIds() {
    return Object.values(props.permissions)
        .flat()
        .map(p => p.id)
}




function toggleAll(checked) {
    if (checked) {
        form.permissions = getAllPermissionIds()
    } else {
        form.permissions = []
    }
}

function isAllSelected() {
    const allIds = getAllPermissionIds()

    return allIds.length &&
        allIds.every(id => form.permissions.includes(id))
}



function toggleAllPermissions(module, checked) {

    const moduleIds = props.permissions[module].map(p => p.id)

    if (checked) {
        form.permissions = [
            ...new Set([
                ...form.permissions,
                ...moduleIds
            ])
        ]
    } else {
        form.permissions = form.permissions.filter(
            id => !moduleIds.includes(id)
        )
    }
}

function isAllModuleSelected(module) {

    const moduleIds = props.permissions[module].map(p => p.id)

    return moduleIds.length &&
        moduleIds.every(id => form.permissions.includes(id))
}

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
    <Head title="Roles" />

    <div class="p-6 max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Create Role</h1>
        <form @submit.prevent="submit" class="space-y-6">
            <input v-model="form.name" placeholder="Role name" class="border p-2 w-full rounded" />

            <div class="font-bold text-lg flex items-center gap-2">
                <input type="checkbox" :checked="isAllSelected()" @change="toggleAll($event.target.checked)" />
                ALL Permissions
            </div>


            <div v-for="(group, module) in permissions" :key="module" class="border p-4 rounded mb-4">

                <h3 class="font-semibold mb-3 capitalize flex items-center gap-2">

                    <input type="checkbox" :checked="isAllModuleSelected(module)"
                        @change="toggleAllPermissions(module, $event.target.checked)" />

                    {{ module }}
                </h3>


                <div class="flex flex-wrap gap-4 ml-6">

                    <label v-for="perm in group" :key="perm.id" class="flex items-center gap-2">
                        <input type="checkbox" :value="perm.id" v-model="form.permissions" />

                        {{ perm.name.replace(`${module}.`, '') }}
                    </label>

                </div>
            </div>


            <button class="bg-green-600 text-white px-4 py-2 rounded" :disabled="form.processing">
                Save Role
            </button>
        </form>





        <h2 class="text-xl font-bold mt-10 mb-4">Roles List</h2>

        <table class="w-full border">

            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">ID</th>
                    <th class="border p-2">Name</th>
                    <th class="border p-2">Permissions</th>
                    <th class="border p-2">Action</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="role in roles" :key="role.id">

                    <td class="border p-2">{{ role.id }}</td>
                    <td class="border p-2">{{ role.name }}</td>

                    <td class="border p-2 text-sm">
                        {{role.permissions.map(p => p.name).join(', ')}}
                    </td>

                    <td class="border p-2">
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
</template>
