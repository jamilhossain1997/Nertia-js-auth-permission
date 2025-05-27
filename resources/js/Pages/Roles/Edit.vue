<script setup>
import { Head, useForm } from '@inertiajs/vue3';
const props = defineProps({
    role: Object,
    permissions: Array,
});
const form = useForm({
    name: props.role.name,
    permissions: props.role.permissions.map(p => p.id),
});

function updateRole() {
    form.put(`/roles/${props.role.id}`);
}
</script>
<template>
    <Head title="Edit Role" />
    <div>
        <h1 class="text-xl font-bold mb-4">Edit Role</h1>
        <form @submit.prevent="updateRole" class="space-y-4">
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

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded" :disabled="form.processing">
                Update Role
            </button>
        </form>
    </div>
</template>
