<template>
    <AdminLayout title="Manage Products">

        <Alert ref="stackedAlerts"/>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Products</h1>

            <Link :href="route('admin.products.create')">
                <SecondaryButton>Add Product</SecondaryButton>
            </Link>
        </div>

        <!-- Filters Section -->
        <div class="mb-6">
            <form @submit.prevent="applyFilters" class="flex items-center space-x-4">
                <TextInput ref="searchInput" v-model="filters.search" type="text"
                           placeholder="Search by name..."
                           ></TextInput>
                <DropdownSelect
                    v-model="filters.category"
                    :options="categories"
                    placeholder="Select a Category"
                    valueKey="id"
                    labelKey="name"
                />
                <SecondaryButton type="submit">Apply Filters</SecondaryButton>
                <SecondaryButton @click="resetFilters">Reset</SecondaryButton>
            </form>
        </div>

        <!-- Products Table -->
        <div v-if="products.data.length > 0">
            <table class="w-full bg-white shadow rounded-xl">
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
                    <td class="border px-4 py-2 space-x-2">
                        <PrimaryButton @click="editProduct(product.id)">Edit</PrimaryButton>
                        <DangerButton @click="deleteProduct(product.id)">Delete</DangerButton>
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
import Pagination from "@/Components/Pagination.vue";
import { Link, useForm } from '@inertiajs/vue3'
import DangerButton from "@/Components/DangerButton.vue";
import Alert from "@/Components/Alert.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import DropdownSelect from "@/Components/DropdownSelect.vue";

// Reference to `StackedAlerts` component
const stackedAlerts = ref(null);

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

function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        filters.delete(route('admin.products.destroy', id), {
            onSuccess: () => {
                stackedAlerts?.value.addAlert("success", "Product deleted successfully!");
            },
            onError: () => {
                stackedAlerts?.value.addAlert("danger", "Failed to delete the product.");
            },
        });
    }
}

function editProduct(id){
    filters.get(route('admin.products.edit', id));
}
</script>

