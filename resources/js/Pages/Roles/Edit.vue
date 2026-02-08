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

function toggleAllPermissions(module, checked) {
    const modulePermIds = props.permissions[module].map(p => p.id);

    if (checked) {
        form.permissions = [...new Set([...form.permissions, ...modulePermIds])];
    } else {
        form.permissions = form.permissions.filter(id => !modulePermIds.includes(id));
    }
}

function isAllModuleSelected(module) {
    const modulePermIds = props.permissions[module].map(p => p.id);
    return modulePermIds.every(id => form.permissions.includes(id));
}
</script>
<template>
    <Head title="Edit Role" />
    <div>
        <h1 class="text-xl font-bold mb-4">Edit Role</h1>
        <form @submit.prevent="updateRole" class="space-y-4">
            <input v-model="form.name" class="border p-2 w-full" placeholder="Role name" />

            <div v-for="(group, module) in permissions" :key="module" class="mb-4">
                <h3 class="font-semibold mb-2 capitalize">
                    <input type="checkbox" 
                        :checked="isAllModuleSelected(module)"
                        @change="toggleAllPermissions(module, $event.target.checked)" 
                        class="form-checkbox" /> 
                    {{ module }}
                </h3>

                <div class="flex flex-wrap gap-2">
                    <label v-for="perm in group" :key="perm.id" class="inline-flex items-center space-x-2">
                        <input type="checkbox" 
                            :value="perm.id" 
                            v-model="form.permissions" 
                            class="form-checkbox" />
                        <span>{{ perm.name.replace(`${module}.`, '') }}</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded" :disabled="form.processing">
                Update Role
            </button>
        </form>
    </div>
</template>
