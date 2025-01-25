<template>
    <AdminLayout title="Manage Products">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Products</h1>
            <Link :href="route('admin.products.create')" class="btn btn-primary">
                Add Product
            </Link>
        </div>

        <!-- Filters Section -->
        <div class="mb-6">
            <form @submit.prevent="applyFilters" class="flex items-center space-x-4">
                <input
                    ref="searchInput"
                    v-model="filters.search"
                    type="text"
                    placeholder="Search by name..."
                    class="input"
                />
                <select v-model="filters.category" class="input">
                    <option value="">All Categories</option>
                    <option
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <button type="button" class="btn btn-secondary" @click="resetFilters">
                    Reset
                </button>
            </form>
        </div>

        <!-- Products Table -->
        <div v-if="products.data.length > 0">
            <table class="w-full bg-white shadow rounded">
                <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Category</th>
                    <th class="px-4 py-2">Price</th>
                    <th class="px-4 py-2">Stock</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="product in products.data" :key="product.id">
                    <td class="border px-4 py-2">{{ product.id }}</td>
                    <td class="border px-4 py-2">{{ product.name }}</td>
                    <td class="border px-4 py-2">{{ product.category.name }}</td>
                    <td class="border px-4 py-2">
                        {{ product.sale_price ? product.sale_price : product.regular_price }}
                    </td>
                    <td class="border px-4 py-2">{{ product.stock }}</td>
                    <td class="border px-4 py-2">
                        <Link :href="route('admin.products.edit', product.id)" class="text-blue-500 mr-2">Edit</Link>
                        <Link
                            as="button"
                            method="delete"
                            :href="route('admin.products.destroy', product.id)"
                            class="text-red-500"
                        >
                            Delete
                        </Link>
                    </td>
                </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <Pagination :links="products.links" />
        </div>

        <!-- No Products Message -->
        <div v-else class="text-center text-gray-500 mt-8">
            <p>No products found.</p>
        </div>
    </AdminLayout>
</template>
<script setup>
import { ref, onMounted, watch } from 'vue'
import debounce from 'lodash/debounce'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Pagintation.vue'
import { Link, useForm } from '@inertiajs/vue3'

// Props passed from the server
const props = defineProps({
    products: Object,
    categories: Array, // Categories for the filter dropdown
})

// Initialize filters with query parameters
const filters = useForm({
    search: new URLSearchParams(window.location.search).get('search') || '', // Retain search query
    category: new URLSearchParams(window.location.search).get('category') || '',
})

// Ref for the search input
const searchInput = ref(null)

// Function to safely focus the search input
function focusSearchInput() {
    setTimeout(() => {
        searchInput.value?.focus()
    }, 50) // Delay to allow DOM updates
}

// Focus the input field after the page loads
onMounted(() => {
    focusSearchInput()
})

// Debounce the search function
const debouncedSearch = debounce(() => {
    filters.get(route('admin.products.index'), {
        preserveState: true, // Keep current state (e.g., filters)
        onSuccess: () => focusSearchInput(), // Refocus the search input
    })
}, 300)

// Watch the search field for changes
watch(() => filters.search, debouncedSearch)

function applyFilters() {
    filters.get(route('admin.products.index'), {
        preserveState: true, // Keep current state
        onSuccess: () => focusSearchInput(),
    })
}

function resetFilters() {
    filters.reset()
    filters.get(route('admin.products.index'), {
        preserveState: false, // Reset to default state
        onSuccess: () => focusSearchInput(),
    })
}
</script>

