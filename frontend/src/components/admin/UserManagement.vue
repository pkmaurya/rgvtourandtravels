<template>
    <div class="bg-white shadow rounded-xl overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-900">Registered Users</h3>
            <!-- Search or other controls could go here -->
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 shrink-0">
                                    <img v-if="user.profile_image" :src="user.profile_image" class="h-10 w-10 rounded-full object-cover" alt="">
                                    <div v-else class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                        {{ user.first_name.charAt(0) }}{{ user.last_name.charAt(0) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ user.first_name }} {{ user.middle_name }} {{ user.last_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ user.email }}</div>
                             <div class="text-sm text-gray-500">{{ user.phone }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                :class="user.role === 'admin' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                                {{ user.role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button @click="openEditModal(user)" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</button>
                            <button @click="deleteUser(user.id)" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                     <tr v-if="users.length === 0">
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No users found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Edit Modal -->
        <Teleport to="body">
            <div v-if="showModal" class="fixed z-[9999] inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" aria-hidden="true" @click="closeModal"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-[10000]">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-xl font-bold text-gray-900 mb-4" id="modal-title">Edit User</h3>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                                        <input v-model="form.first_name" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2 border">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                                        <input v-model="form.middle_name" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2 border">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                    <input v-model="form.last_name" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input v-model="form.email" type="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                                    <input v-model="form.phone" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Role</label>
                                    <select v-model="form.role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2 border">
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                            <button @click="updateUser" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Save Changes
                            </button>
                            <button @click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'

const users = ref([])
const showModal = ref(false)
const form = ref({
    id: null,
    first_name: '',
    middle_name: '',
    last_name: '',
    email: '',
    phone: '',
    role: 'user'
})

const fetchUsers = async () => {
    try {
        const response = await api.get('/users')
        users.value = response.data.data || []
    } catch (error) {
        console.error("Failed to fetch users", error)
    }
}

const deleteUser = async (id) => {
    if(confirm("Are you sure you want to delete this user?")) {
        try {
            await api.delete(`/users/${id}`)
            fetchUsers()
        } catch (error) {
             console.error("Failed to delete user", error)
             alert("Failed to delete user")
        }
    }
}

const openEditModal = (user) => {
    form.value = { ...user }
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    form.value = { id: null, first_name: '', middle_name: '', last_name: '', email: '', phone: '', role: 'user' }
}

const updateUser = async () => {
    try {
        await api.put(`/users/${form.value.id}`, form.value)
        closeModal()
        fetchUsers()
    } catch (error) {
        console.error("Failed to update user", error)
        alert("Failed to update user")
    }
}

onMounted(() => {
    fetchUsers()
})
</script>
