<template>
    <AdminLayout title="Manage Orders">
        <div class="p-6 bg-white shadow-md rounded-lg">
            <h1 class="text-2xl font-bold mb-4">Orders</h1>

            <div v-if="orders.data.length > 0">
                <table class="w-full bg-white shadow rounded-xl">
                    <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Customer</th>
                        <th class="px-4 py-2">Total</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="order in orders.data" :key="order.id">
                        <td class="border px-4 py-2">{{ order.id }}</td>
                        <td class="border px-4 py-2">{{ order.user.name }}</td>
                        <td class="border px-4 py-2">{{ formatCurrency(order.total) }}</td>
                        <td class="border px-4 py-2">
                                <span class="px-2 py-1 rounded text-white" :class="statusClass(order.status)">
                                    {{ capitalize(order.status) }}
                                </span>
                        </td>
                        <td class="border px-4 py-2 space-x-2">
                            <Link :href="route('admin.orders.edit', order.id)">
                                <PrimaryButton>Edit</PrimaryButton>
                            </Link>
                            <DangerButton @click="deleteOrder(order.id)">Delete</DangerButton>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <Pagination :links="orders.links" />
            </div>

            <div v-else class="text-center text-gray-500 mt-8">
                <p>No orders found.</p>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    orders: Object,
});

const deleteOrder = (id) => {
    if (confirm('Are you sure you want to delete this order?')) {
        useForm().delete(route('admin.orders.destroy', id), {
            onSuccess: () => alert('Order deleted successfully!'),
        });
    }
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
};

const capitalize = (str) => str.charAt(0).toUpperCase() + str.slice(1);

// Styling for different statuses
const statusClass = (status) => {
    switch (status) {
        case 'pending': return 'bg-yellow-500';
        case 'processing': return 'bg-blue-500';
        case 'shipped': return 'bg-purple-500';
        case 'delivered': return 'bg-green-500';
        case 'canceled': return 'bg-red-500';
        default: return 'bg-gray-500';
    }
};
</script>
