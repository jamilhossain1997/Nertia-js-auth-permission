<template>
  <div>
    <h1 class="text-2xl font-bold">User Management Dashboard</h1>
    <table class="table-auto w-full mt-4">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Roles</th>
          <th>Assign Role</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.roles.map(r => r.name).join(', ') }}</td>
          <td>
            <form @submit.prevent="assignRole(user.id)">
              <select v-model="selectedRoles[user.id]">
                <option v-for="role in roles" :key="role.id" :value="role.name">{{ role.name }}</option>
              </select>
              <button class="ml-2 px-2 py-1 bg-blue-500 text-white rounded">Assign</button>
            </form>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  users: Array,
  roles: Array,
})

const selectedRoles = ref({})

const assignRole = (userId) => {
  const form = useForm({
    role: selectedRoles.value[userId]
  })

  form.post(route('users.assignRole', userId))
}
</script>
