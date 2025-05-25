<script setup>
import { Head, useForm } from '@inertiajs/vue3';
const props = defineProps(['users', 'roles']);

const form = useForm({
    name: '',
    email: '',
    role: '',
});

const assignForm = useForm({
    role: '',
});

function createUser() {
    form.post('/users');
}

function updateUserRole(userId, role) {
    assignForm.role = role;
    assignForm.put(`/users/${userId}/role`);
}
</script>

<template>

    <Head title="User Management" />
    <h1 class="text-xl font-bold mb-4">User Management</h1>

    <form @submit.prevent="createUser" class="space-y-4 mb-6">
        <input v-model="form.name" placeholder="Name" class="border p-2" />
        <input v-model="form.email" placeholder="Email" class="border p-2" />
        <select v-model="form.role" class="border p-2">
            <option value="">Select Role</option>
            <option v-for="role in roles" :key="role.id" :value="role.name">{{ role.name }}</option>
        </select>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2">Create User</button>
    </form>

    <table class="w-full table-auto border">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Email</th>
                <th class="border px-4 py-2">Role</th>
                <th class="border px-4 py-2">Change Role</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="user in users" :key="user.id">
                <td class="border px-4 py-2">{{ user.name }}</td>
                <td class="border px-4 py-2">{{ user.email }}</td>
                <td class="border px-4 py-2">{{ user.roles[0]?.name ?? 'No Role' }}</td>
                <td class="border px-4 py-2">
                    <select :value="user.roles[0]?.name" @change="e => updateUserRole(user.id, e.target.value)">
                        <option v-for="role in roles" :key="role.id" :value="role.name">{{ role.name }}</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
</template>
