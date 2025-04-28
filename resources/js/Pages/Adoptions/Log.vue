<template>
  <Head title="Adoption Log" />

  <AppLayout>
      <div class="container mx-auto max-w-5xl bg-white mt-6 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh] p-6">
          <div class="relative w-full">
              <h2 class="flex gap-[5px] sticky top-0 py-3 px-6 text-2xl font-semibold bg-white z-10 justify-center">
                  Pending <span class="text-primary">Adoptions</span>
              </h2>

              <!-- Flash Messages -->
              <!-- <FlashMessage
                v-if="flash.success"
                type="success"
                :message="flash.success"
              />
              <FlashMessage
                v-if="flash.error"
                type="error"
                :message="flash.error"
              /> -->

              <div v-if="pendingAdoptions.length === 0" class="text-center py-8">
                <p class="text-gray-500 text-lg">No pending adoption requests found.</p>
              </div>

              <div v-else class="overflow-x-auto">
                  <table class="table-auto w-full border-collapse border border-gray-300">
                      <thead>
                          <tr class="bg-gray-100">
                              <th class="border p-3 text-left text-sm font-semibold">ID</th>
                              <th class="border p-3 text-left text-sm font-semibold">Adopter</th>
                              <th class="border p-3 text-left text-sm font-semibold">Pet</th>
                              <th class="border p-3 text-left text-sm font-semibold">Status</th>
                              <th class="border p-3 text-center text-sm font-semibold">Actions</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr
                              v-for="adoption in pendingAdoptions"
                              :key="adoption.id"
                              class="hover:bg-gray-50 transition"
                          >
                              <td class="border p-3 text-sm">{{ adoption.id }}</td>
                              <td class="border p-3 text-sm">
                                  {{ adoption.first_name }} {{ adoption.middle_name }} {{ adoption.last_name }}
                              </td>
                              <td class="border p-3 text-sm">
                                  {{ adoption.pet?.name || 'Unknown Pet' }}
                                  <span class="text-gray-500 text-xs">(ID: {{ adoption.pet_id }})</span>
                              </td>
                              <td
                                  class="border p-3 text-sm text-yellow-400"
                              >
                                  {{ capitalize(adoption.status) }}
                              </td>
                              <td class="border p-3 text-center">
                                  <template v-if="canManageAdoption(adoption)">
                                      <Link
                                          :href="`/adopt/${adoption.id}/edit`"
                                          class="bg-primary text-white py-2 px-4 mx-4 rounded-md hover:bg-white hover:text-primary border-1 border-primary transition-all ease-in-out duration-300"
                                      >
                                          Edit
                                      </Link>
                                      <form
                                          @submit.prevent="deleteAdoption(adoption.id)"
                                          class="inline"
                                      >
                                          <button
                                              type="submit"
                                              class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-white hover:text-red-500 border-1 border-red-500 transition-all duration-300 ease-in-out"
                                              :disabled="form.processing"
                                          >
                                              <span v-if="form.processing">Deleting...</span>
                                              <span v-else>Delete</span>
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
import { computed } from 'vue'
import { Head, Link, usePage, useForm } from '@inertiajs/vue3'
import AppLayout from '../../Layouts/AppLayout.vue'
import FlashMessage from '../../Components/FlashMessage.vue'

const props = defineProps({
  adoptions: Array,
  flash: Object,
  errors: Object,
  user: Object,
})

const flash = computed(() => props.flash)
const page = usePage()
const adoptions = page.props.adoptions || []
const authUser = page.props.auth?.user || null
const form = useForm({})

// Filter only pending adoptions
const pendingAdoptions = computed(() => {
  return adoptions.filter(adoption => 
    adoption.status.toLowerCase() === 'pending'
  )
})

function capitalize(str) {
  if (!str) return ''
  return str.charAt(0).toUpperCase() + str.slice(1)
}

function canManageAdoption(adoption) {
  if (!authUser) return false;
  
  const isPending = adoption.status.toLowerCase() === 'pending';
  const isOwner = authUser.id === adoption.user_id;
  const isAdminOrShelter = authUser.roles?.some(role => 
    role.name === 'Administrator' || role.name === 'Shelter'
  );

  return isPending && (isOwner || isAdminOrShelter);
}

function deleteAdoption(id) {
  if (confirm('Are you sure you want to delete this adoption request?')) {
    form.delete(`/adopt/${id}`, {
      preserveScroll: true,
      onSuccess: () => {
        // Success message will be shown via flash message
      },
      onError: (errors) => {
        // Error message will be shown via flash message
      }
    })
  }
}
</script>
