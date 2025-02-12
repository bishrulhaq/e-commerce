<template>
    <AdminLayout title="Edit Order">
        <div class="p-6 bg-white shadow-md rounded-lg">
            <h1 class="text-2xl font-bold mb-4">Edit Order #{{ order.id }}</h1>

            <form @submit.prevent="updateOrder">
                <!-- Customer Selection -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold">Customer</label>
                    <DropdownSelect
                        v-model="form.user_id"
                        :options="customers"
                        labelKey="name"
                        valueKey="id"
                        placeholder="Select Customer"
                    />
                    <p v-if="form.errors.user_id" class="text-red-500 text-sm mt-1">
                        {{ form.errors.user_id }}
                    </p>
                </div>

                <!-- Order Status -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold">Status</label>
                    <select v-model="form.status" class="border p-2 rounded w-full">
                        <option v-for="status in statuses" :key="status" :value="status">
                            {{ capitalize(status) }}
                        </option>
                    </select>
                    <p v-if="form.errors.status" class="text-red-500 text-sm mt-1">
                        {{ form.errors.status }}
                    </p>
                </div>

                <!-- Order Items -->
                <div class="mb-4">
                    <h2 class="text-xl font-bold mb-2">Order Items</h2>
                    <div v-for="(item, index) in form.items" :key="item.id" class="mb-2 flex space-x-2">
                        <DropdownSelect
                            v-model="item.product_id"
                            :options="products"
                            labelKey="name"
                            valueKey="id"
                            placeholder="Select Product"
                        />
                        <input v-model.number="item.quantity" type="number" min="1" class="border p-2 rounded w-20" />
                        <p class="text-gray-700">{{ formatCurrency(getProductPrice(item.product_id) * item.quantity) }}</p>
                        <button type="button" @click="removeItem(index)" class="text-red-500">Remove</button>
                    </div>
                    <button type="button" @click="addItem" class="text-blue-500">+ Add Item</button>
                    <p v-if="form.errors.items" class="text-red-500 text-sm mt-1">
                        {{ form.errors.items }}
                    </p>
                </div>

                <!-- Order Payment Method -->
                <select v-model="form.payment_method" class="border p-2 rounded w-full">
                    <option v-for="method in paymentMethods" :key="method" :value="method">
                        {{ capitalize(method.replace('_', ' ')) }}
                    </option>
                </select>

                <!-- Total Price -->
                <div class="mb-4">
                    <h3 class="text-lg font-bold">Total: {{ formatCurrency(computedTotal) }}</h3>
                </div>

                <!-- Action Buttons -->
                <PrimaryButton type="submit">Update Order</PrimaryButton>
                <DangerButton type="button" @click="deleteOrder">Delete Order</DangerButton>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DropdownSelect from '@/Components/DropdownSelect.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    order: Object,
    customers: Array,
    products: Array,
});

const statuses = ['pending', 'processing', 'shipped', 'delivered', 'canceled'];
const paymentMethods = ['card', 'cash', 'pay_on_delivery', 'other'];

const form = useForm({
    user_id: props.order.user.id,
    status: props.order.status,
    payment_method: props.order.payment_method,
    items: props.order.items.map(item => ({
        id: item.id,
        product_id: item.product.id,
        quantity: item.quantity,
    })),
});

// Update Order
const updateOrder = () => {
    form.put(route('admin.orders.update', props.order.id), {
        preserveState: true,
        onSuccess: () => alert('Order updated successfully!'),
        onError: (errors) => console.log(errors),
    });
};

// Delete Order
const deleteOrder = () => {
    if (confirm('Are you sure you want to delete this order?')) {
        form.delete(route('admin.orders.destroy', props.order.id), {
            onSuccess: () => alert('Order deleted successfully!'),
        });
    }
};

// Add New Item
const addItem = () => {
    form.items.push({ product_id: '', quantity: 1 });
};

// Remove Item
const removeItem = (index) => {
    if (form.items.length > 1) {
        form.items.splice(index, 1);
    }
};

// Get Product Price
const getProductPrice = (productId) => {
    const product = props.products.find(p => p.id === productId);
    return product ? product.regular_price : 0;
};

// Compute Total Price
const computedTotal = computed(() => {
    return form.items.reduce((sum, item) => sum + (getProductPrice(item.product_id) * item.quantity), 0);
});

// Format Currency
const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
};

// Capitalize Status
const capitalize = (str) => str.charAt(0).toUpperCase() + str.slice(1);
</script>
