<script lang="ts" setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import axios from 'axios';

import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DropdownSelect from '@/Components/DropdownSelect.vue';
import InputError from '@/Components/InputError.vue';

interface Product {
    id: number;
    name: string;
    price: number;
}

interface Customer {
    id: number;
    name: string;
}

interface PageProps {
    products: Product[];
}

const props = defineProps<PageProps>();

// Initialize the form using Inertia's useForm helper
const form = useForm({
    user_id: '',
    payment_method: '',
    items: [{ product_id: '', quantity: 1 }],
});

// Local state for customer search and suggestions
const customers = ref<Customer[]>([]);
const searchQuery = ref('');
const paymentMethods = ref(['card', 'cash', 'pay_on_delivery', 'other']);

// Search for customers as the user types
const searchCustomers = async () => {
    if (searchQuery.value.length > 2) {
        try {
            const response = await axios.get(route('admin.customers.search'), {
                params: { query: searchQuery.value },
            });
            customers.value = response.data;
        } catch (error) {
            console.error('Error fetching customers:', error);
            customers.value = [];
        }
    } else {
        customers.value = [];
    }
};

const addItem = () => {
    form.items.push({ product_id: '', quantity: 1 });
};

const removeItem = (index: number) => {
    if (form.items.length > 1) {
        form.items.splice(index, 1);
    }
};

const submitOrder = () => {
    form.post(route('admin.orders.store'), {
        onSuccess: () => {
            alert('Order created successfully!');
        },
        onError: () => {
            // Log the errors to see what keys are returned
            console.log('Validation errors:', form.errors);
        },
    });
};

const capitalize = (str) => str.charAt(0).toUpperCase() + str.slice(1);
</script>

<template>
    <AdminLayout title="Create Order">
        <Head title="Create Order" />

        <div class="p-6 bg-white shadow-md rounded-lg">
            <h1 class="text-2xl font-bold mb-4">Create Order</h1>
            <form @submit.prevent="submitOrder">
                <!-- Customer Search Section -->
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1">Customer Name:</label>
                    <input
                        v-model="searchQuery"
                        @input="searchCustomers"
                        type="text"
                        class="border p-2 rounded w-full"
                        placeholder="Search customer..."
                    />
                    <!-- Display error if the customer (user_id) is missing/invalid -->
                    <InputError :message="form.errors.user_id" class="mt-1" />
                    <!-- Customer suggestions list -->
                    <ul v-if="customers.length" class="border bg-white shadow-md rounded mt-2">
                        <li
                            v-for="customer in customers"
                            :key="customer.id"
                            @click="() => { form.user_id = customer.id; searchQuery = customer.name; customers = [] }"
                            class="cursor-pointer p-2 hover:bg-gray-100"
                        >
                            {{ customer.name }}
                        </li>
                    </ul>
                </div>

                <!-- Order Payment Method -->
                <select v-model="form.payment_method" class="border p-2 rounded w-full">
                    <option v-for="method in paymentMethods" :key="method" :value="method">
                        {{ capitalize(method.replace('_', ' ')) }}
                    </option>
                </select>

                <!-- Order Items Section -->
                <div class="mb-4">
                    <h2 class="text-xl font-bold mb-2">Order Items</h2>
                    <div v-for="(item, index) in form.items" :key="index" class="mb-2 flex flex-col space-y-1">
                        <div class="flex space-x-2 items-center">
                            <DropdownSelect
                                v-model="item.product_id"
                                :options="props.products"
                                labelKey="name"
                                valueKey="id"
                                placeholder="Select Product"
                                class="flex-1"
                            />
                            <input
                                v-model.number="item.quantity"
                                type="number"
                                min="1"
                                class="border p-2 rounded w-20"
                                placeholder="Qty"
                            />
                            <button type="button" @click="removeItem(index)" class="text-red-500">
                                Remove
                            </button>
                        </div>
                        <!-- Display errors for product_id and quantity for this order item -->
                        <InputError :message="form.errors[`items.${index}.product_id`]" />
                        <InputError :message="form.errors[`items.${index}.quantity`]" />
                    </div>
                    <button type="button" @click="addItem" class="text-blue-500">
                        + Add Item
                    </button>
                </div>

                <!-- Submit Button -->
                <PrimaryButton type="submit" :disabled="form.processing">
                    Create Order
                </PrimaryButton>
            </form>
        </div>
    </AdminLayout>
</template>
