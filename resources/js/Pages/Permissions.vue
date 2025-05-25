<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    permissions: Array
});

const form = useForm({
    name: ''
});

function submit() {
    form.post(route('permissions.store'));
}

function destroyPermission(id) {
    if (confirm('Are you sure you want to delete this permission?')) {
        form.delete(route('permissions.destroy', id));
    }
}
</script>

<template>
    <Head title="Permissions" />

    <div class="p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Permissions Management</h1>

        <!-- Create Form -->
        <form @submit.prevent="submit" class="flex gap-2 mb-6">
            <input
                v-model="form.name"
                type="text"
                placeholder="Permission name"
                class="border p-2 flex-1"
            />
            <button
                type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded"
            >
                Add
            </button>
        </form>

        <!-- Permission List -->
        <table class="w-full table-auto border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Name</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="permission in permissions" :key="permission.id">
                    <td class="border px-4 py-2">{{ permission.id }}</td>
                    <td class="border px-4 py-2">{{ permission.name }}</td>
                    <td class="border px-4 py-2">
                        <button
                            @click="destroyPermission(permission.id)"
                            class="bg-red-500 text-white px-3 py-1 rounded"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
