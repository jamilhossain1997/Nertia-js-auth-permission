<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                User Management Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2">Name</th>
                            <th class="border px-4 py-2">Email</th>
                            <th class="border px-4 py-2">Roles</th>
                            <th v-if="canAssignRoles" class="border px-4 py-2">Assign Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ user.name }}</td>
                            <td class="border px-4 py-2">{{ user.email }}</td>
                            <td class="border px-4 py-2">
                                <span v-if="user.roles.length > 0">
                                    {{user.roles.map(r => r.name).join(', ')}}
                                </span>
                                <span v-else class="italic text-gray-500">No roles assigned</span>
                            </td>
                            <td v-if="canAssignRoles" class="border px-4 py-2">
                                <form @submit.prevent="assignRole(user.id)">
                                    <select v-model="selectedRoles[user.id]" class="border rounded px-2 py-1">
                                        <option value="" disabled>Select role</option>
                                        <option v-for="role in roles" :key="role.id" :value="role.name">
                                            {{ role.name }}
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
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

const props = defineProps({
    users: Array,
    roles: Array,
    canViewUsers: Boolean,
    canAssignRoles: Boolean,
})


const selectedRoles = ref({})


props.users.forEach(user => {
    selectedRoles.value[user.id] = user.roles.length > 0 ? user.roles[0].name : ''
})

const assignRole = (userId) => {
    if (!selectedRoles.value[userId]) {
        alert('Please select a role first.')
        return
    }

    const form = useForm({
        role: selectedRoles.value[userId],
    })

    form.post(route('users.assignRole', userId), {
        preserveScroll: true,
        onSuccess: () => {
            console.log(`Role assigned to user ${userId}`)
        },
        onError: (errors) => {
            console.error(errors)
        }
    })
}
</script>

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
