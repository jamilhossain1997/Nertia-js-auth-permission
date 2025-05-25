<template>
  <div class="max-w-5xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6">Roles & Permissions Table</h2>
    <table class="table-auto w-full border border-collapse border-gray-300">
      <thead class="bg-gray-100">
        <tr>
          <th class="border px-4 py-2 text-left">Role</th>
          <th class="border px-4 py-2 text-left">Permissions</th>
          <th class="border px-4 py-2 text-center">Can View Users</th>
          <th class="border px-4 py-2 text-center">Can Assign Roles</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="role in roles" :key="role.name">
          <td class="border px-4 py-2">{{ role.name }}</td>
          <td class="border px-4 py-2">{{ role.permissions.join(', ') }}</td>
          <td class="border px-4 py-2 text-center">
            <span v-if="role.can_view_users">✅</span>
            <span v-else>❌</span>
          </td>
          <td class="border px-4 py-2 text-center">
            <span v-if="role.can_assign_roles">✅</span>
            <span v-else>❌</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  roles: Array,
})
</script>
