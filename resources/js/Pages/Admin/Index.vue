<template>
    <AppLayout>
        <div class="container mx-auto min-h-[calc(100vh-200px)] flex-items-center justify-center p-2 sm:p-4">
            <h2 class="text-xl sm:text-2xl text-center font-semibold mb-4 sm:mb-6">
                Admin <span class="text-primary">Dashboard</span>
            </h2>

            <div class="bg-white p-3 sm:p-6 rounded-lg shadow-md border-1 border-primary overflow-x-auto">
                <h2 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4">
                    Manage <span class="text-primary">Users</span>
                </h2>

                <table class="min-w-full table-auto bg-white border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 sm:py-3 px-2 sm:px-4 text-left text-[10px] sm:text-sm font-medium text-gray-500">ID</th>
                            <th class="py-2 sm:py-3 px-2 sm:px-4 text-left text-[10px] sm:text-sm font-medium text-gray-500">Name</th>
                            <th class="py-2 sm:py-3 px-2 sm:px-4 text-left text-[10px] sm:text-sm font-medium text-gray-500">Email</th>
                            <th class="py-2 sm:py-3 px-2 sm:px-4 text-left text-[10px] sm:text-sm font-medium text-gray-500">Roles</th>
                            <th class="py-2 sm:py-3 px-2 sm:px-4 text-left text-[10px] sm:text-sm font-medium text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 cursor-pointer">
                        <tr v-for="user in users" :key="user.id" class="hover:bg-gray-100">
                            <td class="py-2 sm:py-3 px-2 sm:px-4 text-[10px] sm:text-sm text-gray-700">{{ user.id }}</td>
                            <td class="py-2 sm:py-3 px-2 sm:px-4 text-[10px] sm:text-sm text-gray-700">{{ user.name }}</td>
                            <td class="py-2 sm:py-3 px-2 sm:px-4 text-[10px] sm:text-sm text-gray-700">{{ user.email }}</td>
                            <td class="py-2 sm:py-3 px-2 sm:px-4 text-[10px] sm:text-sm text-gray-700">
                                <select
                                    v-model="roleSelections[user.id]"
                                    class="border rounded px-1 sm:px-2 py-0.5 sm:py-1 text-[10px] sm:text-sm cursor-pointer"
                                    @change="confirmRoleChange(user)"
                                >
                                    <option v-for="role in roles" :key="role.id" :value="role.name">
                                        {{ capitalize(role.name) }}
                                    </option>
                                </select>
                            </td>
                            <td class="py-2 sm:py-3 px-2 sm:px-4 text-[10px] sm:text-sm text-gray-700">
                                <button
                                    @click="confirmDelete(user)"
                                    title="Delete User"
                                    class="transition-all hover:scale-150 duration-300 cursor-pointer"
                                >
                                    <img src="/icon/trash-solid.svg" alt="Delete" class="w-4 h-4 sm:w-5 sm:h-5" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Confirmation Modal -->
            <div v-if="modal.open" class="modal fixed inset-0 flex items-center justify-center z-50" @click.self="closeModal">
                <div class="modal-content bg-white p-3 sm:p-6 rounded-lg shadow-xl text-center max-w-[280px] sm:max-w-sm w-full mx-2">
                    <p class="mb-3 sm:mb-4 text-sm sm:text-lg text-gray-800" v-html="modal.message"></p>
                    <div class="flex space-x-2 sm:space-x-4">
                        <button
                            @click="modal.confirm"
                            class="bg-primary text-white px-2 sm:px-4 py-1.5 sm:py-2 rounded-md hover:bg-white hover:text-primary border-1 border-primary hover:scale-105 transition-all duration-300 ease-in-out cursor-pointer text-[10px] sm:text-sm"
                        >
                            Yes
                        </button>
                        <button
                            @click="closeModal"
                            class="bg-white text-gray-700 px-2 sm:px-4 py-1.5 sm:py-2 rounded-md hover:text-primary border-1 hover:border-primary hover:scale-105 transition-all duration-300 ease-in-out cursor-pointer text-[10px] sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

const page = usePage();
const users = ref(page.props.users || []);
const roles = ref(page.props.roles || []);

// Track selected roles for each user
const roleSelections = reactive({});
users.value.forEach((user) => {
    roleSelections[user.id] = user.roles[0]?.name || "";
});

// Modal state
const modal = reactive({
    open: false,
    message: "",
    confirm: () => {},
});

// Capitalize helper
const capitalize = (str) => str.charAt(0).toUpperCase() + str.slice(1);

// Modal helpers
function showModal(message, confirmCallback) {
    modal.message = message;
    modal.confirm = () => {
        confirmCallback();
        closeModal();
    };
    modal.open = true;
}
function closeModal() {
    modal.open = false;
    modal.message = "";
    modal.confirm = () => {};
}

// Delete user
function confirmDelete(user) {
    showModal(
        `Are you sure you want to delete <span style="color: #49b451;">${user.name}</span>?`,
        () => {
            router.delete(`/admin/users/${user.id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    router.visit('/admin/users', { preserveScroll: true });
                }
            });
        }
    );
}

// Change role
function confirmRoleChange(user) {
    const selectedRole = roleSelections[user.id];
    showModal(
        `Change <span style="color: #49b451;">${
            user.name
        }'s</span> role to <span style="color: #49b451;">${capitalize(
            selectedRole
        )}</span>?`,
        () => {
            router.post(`/admin/users/${user.id}/role`, {
                role: selectedRole,
            });
        }
    );
}
</script>

<style scoped>
.modal {
    background-color: rgba(0, 0, 0, 0.4);
}
</style>
