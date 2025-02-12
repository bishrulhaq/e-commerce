<template>
    <AdminLayout title="Manage Customers">
        <Head title="Manage Customers" />

        <!-- Alert Messages -->
        <Alert ref="alertComponent" />

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Customers</h1>
            <Link :href="route('admin.customers.create')">
            <PrimaryButton>Add Customer</PrimaryButton>
            </Link>
        </div>

        <!-- Search Customers -->
        <div class="mb-4">
            <input v-model="searchQuery" @input="searchCustomers" type="text" placeholder="Search customers..." class="border p-2 rounded w-full" />
        </div>

        <div v-if="customers.data.length > 0">
            <table class="w-full bg-white shadow rounded-xl">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="customer in customers.data" :key="customer.id">
                    <td class="border px-4 py-2">{{ customer.id }}</td>
                    <td class="border px-4 py-2">{{ customer.name }}</td>
                    <td class="border px-4 py-2">{{ customer.email }}</td>
                    <td class="border px-4 py-2 space-x-2">
                        <PrimaryButton @click="resetPassword(customer.id)">Reset Password</PrimaryButton>
                        <DangerButton @click="deleteCustomer(customer.id)">Delete</DangerButton>
                    </td>
                </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <Pagination :links="customers.links" />
        </div>

        <!-- No Customers Found Message -->
        <div v-else class="text-center text-gray-500 mt-8">
            <p>No customers found.</p>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
    import { ref, watch } from 'vue';
    import { Head, router, useForm } from '@inertiajs/vue3';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import Pagination from '@/Components/Pagination.vue';
    import { Link } from '@inertiajs/vue3';
    import Alert from '@/Components/Alert.vue';
    import PrimaryButton from "@/Components/PrimaryButton.vue";
    import DangerButton from "@/Components/DangerButton.vue";

    const props = defineProps({
        customers: Object,
    });

    const searchQuery = ref('');
    const alertComponent = ref(null);

    // Function to search customers
    const searchCustomers = () => {
        router.get(route('admin.customers.index'), { search: searchQuery.value }, { preserveState: true });
    };

    // Function to reset customer password
    const resetPassword = (id: number) => {
        if (confirm('Are you sure you want to reset the password for this customer?')) {
            router.post(route('admin.customers.reset', id), {}, {
                onSuccess: () => alertComponent.value?.addAlert("success", "Password reset successfully! Email sent to customer."),
                onError: () => alertComponent.value?.addAlert("error", "Failed to reset password."),
            });
        }
    };

    // Function to delete a customer
    const deleteCustomer = (id: number) => {
        if (confirm('Are you sure you want to delete this customer?')) {
            router.delete(route('admin.customers.destroy', id), {
                onSuccess: () => alertComponent.value?.addAlert("success", "Customer deleted successfully."),
                onError: () => alertComponent.value?.addAlert("error", "Failed to delete customer."),
            });
        }
    };
</script>
