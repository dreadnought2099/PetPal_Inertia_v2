<template>
    <Head title="Adoption Log" />

    <AppLayout>
        <div class="container mx-auto max-w-5xl bg-white mt-6 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh] p-6">
    <div class="relative w-full">
      <h2 class="flex gap-[5px] sticky top-0 py-3 px-6 text-2xl font-semibold bg-white z-10 justify-center">
        Adoption <span class="text-primary">Log</span>
      </h2>

      <div class="overflow-x-auto">
        <table class="table-auto w-full border-collapse border border-gray-300">
          <thead>
            <tr class="bg-gray-100">
              <th class="border p-3 text-left text-sm font-semibold">ID</th>
              <th class="border p-3 text-left text-sm font-semibold">Adopter</th>
              <th class="border p-3 text-left text-sm font-semibold">Pet ID</th>
              <th class="border p-3 text-left text-sm font-semibold">Status</th>
              <th class="border p-3 text-center text-sm font-semibold">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="adoption in adoptions"
              :key="adoption.id"
              class="hover:bg-gray-50 transition"
            >
              <td class="border p-3 text-sm">{{ adoption.id }}</td>
              <td class="border p-3 text-sm">
                {{ adoption.first_name }} {{ adoption.middle_name }} {{ adoption.last_name }}
              </td>
              <td class="border p-3 text-sm">{{ adoption.pet_id }}</td>
              <td
                class="border p-3 text-sm"
                :class="{
                  'text-green-500': adoption.status === 'approved',
                  'text-red-500': adoption.status === 'rejected',
                  'text-yellow-400': adoption.status === 'pending'
                }"
              >
                {{ adoption.status.charAt(0).toUpperCase() + adoption.status.slice(1) }}
              </td>
              <td class="border p-3 text-center">
                <template v-if="$page.props.auth.user.id === adoption.user_id && adoption.status === 'pending'">
                  <Link
                    :href="route('adopt.edit', adoption.id)"
                    class="bg-primary text-white py-2 px-4 mx-4 rounded-md hover:bg-white hover:text-primary border-1 border-primary transition-all ease-in-out duration-300"
                  >
                    Edit
                  </Link>
                  <form
                    @submit.prevent="form.delete(route('adopt.destroy', adoption.id))"
                    class="inline"
                  >
                    <button
                      type="submit"
                      class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-white hover:text-red-500 border-1 border-red-500 transition-all duration-300 ease-in-out"
                    >
                      Delete
                    </button>
                  </form>
                </template>
                <span v-else class="text-gray-500">No actions available</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
    </AppLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import AppLayout from '../../Layouts/AppLayout.vue'

const page = usePage()
const adoptions = page.props.adoptions || []

function capitalize(str) {
  if (!str) return ''
  return str.charAt(0).toUpperCase() + str.slice(1)
}
</script>
