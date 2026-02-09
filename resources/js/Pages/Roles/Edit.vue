<script setup>
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps({
    role: Object,
    permissions: Object, // grouped { users:[], roles:[] }
})

const form = useForm({
    name: props.role.name,
    permissions: props.role.permissions.map(p => p.id),
})



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

    return (
        allIds.length &&
        allIds.every(id => form.permissions.includes(id))
    )
}




function toggleAllPermissions(module, checked) {
    const modulePermIds = props.permissions[module].map(p => p.id)

    if (checked) {
        form.permissions = [...new Set([...form.permissions, ...modulePermIds])]
    } else {
        form.permissions = form.permissions.filter(
            id => !modulePermIds.includes(id)
        )
    }
}

function isAllModuleSelected(module) {
    const modulePermIds = props.permissions[module].map(p => p.id)

    return modulePermIds.every(id =>
        form.permissions.includes(id)
    )
}




function updateRole() {
    form.put(`/roles/${props.role.id}`)
}
</script>



<template>
    <Head title="Edit Role" />

    <div class="p-6 bg-white rounded shadow max-w-4xl">

        <h1 class="text-xl font-bold mb-6">
            Edit Role
        </h1>


        <form @submit.prevent="updateRole" class="space-y-6">

            <input
                v-model="form.name"
                class="border p-2 w-full rounded"
                placeholder="Role name"
            />


        
            <div>
                <label class="font-semibold mb-4 block text-lg">
                    Assign Permissions
                </label>



                <div class="mb-5 flex items-center gap-2 font-bold text-blue-600">
                    <input
                        type="checkbox"
                        :checked="isAllSelected()"
                        @change="toggleAll($event.target.checked)"
                        class="form-checkbox"
                    />
                    ALL Permissions
                </div>



                
                <div
                    v-for="(group, module) in permissions"
                    :key="module"
                    class="mb-6 border p-4 rounded bg-gray-50"
                >
                    <h3 class="font-semibold mb-3 capitalize flex items-center gap-2">
                        <input
                            type="checkbox"
                            :checked="isAllModuleSelected(module)"
                            @change="toggleAllPermissions(module, $event.target.checked)"
                            class="form-checkbox"
                        />
                        {{ module }}
                    </h3>

                    <div class="flex flex-wrap gap-4 ml-6">
                        <label
                            v-for="perm in group"
                            :key="perm.id"
                            class="inline-flex items-center gap-2"
                        >
                            <input
                                type="checkbox"
                                :value="perm.id"
                                v-model="form.permissions"
                                class="form-checkbox"
                            />

                            <span>
                                {{ perm.name.replace(`${module}.`, '') }}
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <button
                type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded"
                :disabled="form.processing"
            >
                Update Role
            </button>
        </form>
    </div>
</template>
