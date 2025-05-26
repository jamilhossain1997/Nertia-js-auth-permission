<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    roles: Array,
    permissions: Array,
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
        <h1 class="text-xl font-bold mb-4">Roles Management</h1>

        <!-- Add Role Form -->
        <form @submit.prevent="submit" class="mb-6 space-y-4">
            <input v-model="form.name" class="border p-2 w-full" placeholder="Role name" />
            <div>
                <label class="font-semibold mb-2 block">Assign Permissions:</label>
                <div class="flex flex-wrap gap-2">
                    <label v-for="permission in permissions" :key="permission.id" class="inline-flex items-center space-x-2">
                        <input type="checkbox" :value="permission.id" v-model="form.permissions" class="form-checkbox" />
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
                    <td class="border px-4 py-2">{{ role.permissions.map(p => p.name).join(', ') }}</td>
                    <td class="border px-4 py-2 space-x-2">
                        <button @click="$inertia.visit(`/roles/${role.id}/edit`)" class="bg-blue-500 text-white px-3 py-1 rounded">
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
