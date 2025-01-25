<template>
    <AdminLayout title="Create Product">
        <div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">Create Product</h2>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Name -->
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Product Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="input"
                        placeholder="Enter product name"
                    />
                    <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</span>
                </div>

                <!-- Category -->
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Category</label>
                    <select v-model="form.category_id" class="input">
                        <option value="">Select a category</option>
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                    <span v-if="form.errors.category_id" class="text-red-500 text-sm">{{ form.errors.category_id }}</span>
                </div>

                <!-- Prices -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Cost Price</label>
                        <input
                            v-model="form.cost_price"
                            type="number"
                            step="0.01"
                            class="input"
                            placeholder="Enter cost price"
                        />
                        <span v-if="form.errors.cost_price" class="text-red-500 text-sm">{{ form.errors.cost_price }}</span>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Regular Price</label>
                        <input
                            v-model="form.regular_price"
                            type="number"
                            step="0.01"
                            class="input"
                            placeholder="Enter regular price"
                        />
                        <span v-if="form.errors.regular_price" class="text-red-500 text-sm">{{ form.errors.regular_price }}</span>
                    </div>
                </div>

                <!-- Stock -->
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Stock Quantity</label>
                    <input
                        v-model="form.stock"
                        type="number"
                        class="input"
                        placeholder="Enter stock quantity"
                    />
                    <span v-if="form.errors.stock" class="text-red-500 text-sm">{{ form.errors.stock }}</span>
                </div>

                <!-- Images -->
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Product Images</label>
                    <input
                        type="file"
                        multiple
                        @change="handleImageUpload"
                        class="input"
                        accept="image/*"
                    />
                    <span v-if="form.errors.images" class="text-red-500 text-sm">{{ form.errors.images }}</span>
                    <span v-if="imageErrors.length > 0" class="text-red-500 text-sm">{{ imageErrors.join(', ') }}</span>
                    <div v-if="previewImages.length" class="mt-4 grid grid-cols-4 gap-4">
                        <div
                            v-for="(image, index) in previewImages"
                            :key="index"
                            class="relative"
                        >
                            <img :src="image" class="rounded shadow max-h-32 object-cover" />
                            <button
                                type="button"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1"
                                @click="removeImage(index)"
                            >
                                ✕
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Description</label>
                    <textarea
                        v-model="form.description"
                        rows="4"
                        class="input"
                        placeholder="Enter product description"
                    ></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <Link :href="route('admin.products.index')" class="btn btn-secondary">
                        Cancel
                    </Link>
                    <button type="submit" class="btn btn-primary">Create Product</button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    categories: Array, // Categories for the dropdown
})

// Reactive form data
const form = useForm({
    name: '',
    category_id: '',
    cost_price: '',
    regular_price: '',
    sale_price: '',
    stock: '',
    description: '',
    images: [],
})

// Preview images for display
const previewImages = ref([])
const imageErrors = ref([]) // For custom image validation errors

function handleImageUpload(event) {
    const files = Array.from(event.target.files)

    // Clear previous errors
    imageErrors.value = []

    // Validate number of images
    if (files.length + form.images.length > 6) {
        imageErrors.value.push('You can only upload up to 6 images.')
        return
    }

    // Validate image sizes
    const validFiles = files.filter((file) => {
        if (file.size > 1024 * 1024) {
            imageErrors.value.push(`${file.name} exceeds the 1MB size limit.`)
            return false
        }
        return true
    })

    // Add valid files to the form
    form.images = [...form.images, ...validFiles]

    // Update preview images
    previewImages.value = [
        ...previewImages.value,
        ...validFiles.map((file) => URL.createObjectURL(file)),
    ]
}

function removeImage(index) {
    form.images.splice(index, 1)
    previewImages.value.splice(index, 1)
}

// Submit the form
function submit() {
    form.post(route('admin.products.store'), {
        forceFormData: true, // Ensure the request is sent as FormData for file upload
    })
}
</script>
