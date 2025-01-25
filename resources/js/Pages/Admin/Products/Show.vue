<template>
    <AdminLayout title="Product Details">
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-xl font-bold mb-2">{{ product.name }}</h2>
            <p class="text-sm text-gray-700 mb-1">Price: {{ product.price }}</p>
            <p class="text-sm text-gray-700">Stock: {{ product.stock }}</p>
            <p class="mt-4 text-gray-600">Description: {{ product.description }}</p>
        </div>
        <div class="mt-4">
            <Link :href="route('admin.products.index')" class="btn">Back</Link>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    product: Object
})
</script>
