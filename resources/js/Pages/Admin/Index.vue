<template>
    <AppLayout>
        <div class="container mx-auto p-4">
            <h2 class="text-2xl text-center font-semibold mb-6">
                Admin <span class="text-primary">Dashboard</span>
            </h2>

            <div
                class="bg-white p-6 rounded-lg shadow-md border-1 border-primary"
            >
                <h2 class="text-xl font-semibold mb-4">
                    Manage <span class="text-primary">Users</span>
                </h2>

                <table class="min-w-full table-auto bg-white border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th
                                class="py-3 px-4 text-left text-sm font-medium text-gray-500"
                            >
                                ID
                            </th>
                            <th
                                class="py-3 px-4 text-left text-sm font-medium text-gray-500"
                            >
                                Name
                            </th>
                            <th
                                class="py-3 px-4 text-left text-sm font-medium text-gray-500"
                            >
                                Email
                            </th>
                            <th
                                class="py-3 px-4 text-left text-sm font-medium text-gray-500"
                            >
                                Roles
                            </th>
                            <th
                                class="py-3 px-4 text-left text-sm font-medium text-gray-500"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 cursor-pointer">
                        <tr
                            v-for="user in users"
                            :key="user.id"
                            class="hover:bg-gray-100"
                        >
                            <td class="py-3 px-4 text-sm text-gray-700">
                                {{ user.id }}
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-700">
                                {{ user.name }}
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-700">
                                {{ user.email }}
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-700">
                                <select
                                    v-model="roleSelections[user.id]"
                                    class="border rounded px-2 py-1 text-sm cursor-pointer"
                                    @change="confirmRoleChange(user)"
                                >
                                    <option
                                        v-for="role in roles"
                                        :key="role.id"
                                        :value="role.name"
                                    >
                                        {{ capitalize(role.name) }}
                                    </option>
                                </select>
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-700">
                                <button
                                    @click="confirmDelete(user)"
                                    title="Delete User"
                                    class="transition-all hover:scale-150 duration-300 cursor-pointer"
                                >
                                    <img
                                        src="/icon/trash-solid.svg"
                                        alt="Delete"
                                        class="w-5 h-5"
                                    />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Confirmation Modal -->
            <div
                v-if="modal.open"
                class="modal fixed inset-0 flex items-center justify-center z-50"
                @click.self="closeModal"
            >
                <div
                    class="modal-content bg-white p-6 rounded-lg shadow-xl text-center max-w-sm w-full"
                >
                    <p
                        class="mb-4 text-lg text-gray-800"
                        v-html="modal.message"
                    ></p>
                    <div class="flex space-x-4">
                        <button
                            @click="modal.confirm"
                            class="bg-primary text-white px-4 py-2 rounded-md hover:bg-white hover:text-primary border-1 border-primary hover:scale-105 transition-all duration-300 ease-in-out cursor-pointer"
                        >
                            Yes
                        </button>
                        <button
                            @click="closeModal"
                            class="bg-white text-gray-700 px-4 py-2 rounded-md hover:text-primary border-1 hover:border-primary hover:scale-105 transition-all duration-300 ease-in-out cursor-pointer"
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
