<template>
    <AdminLayout title="Edit Product">
        <div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">Edit Product</h2>

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

                <!-- Existing Images -->
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Existing Images</label>
                    <div v-if="existingImages.length > 0" class="mt-4 grid grid-cols-4 gap-4">
                        <div
                            v-for="(image, index) in existingImages"
                            :key="image.id"
                            class="relative"
                        >
                            <img :src="`/storage/${image.image_path}`" class="rounded shadow max-h-32 object-cover" />
                            <button
                                type="button"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1"
                                @click="removeExistingImage(image.id)"
                            >
                                ✕
                            </button>
                        </div>
                    </div>
                    <span v-if="form.errors.existing_images" class="text-red-500 text-sm">{{ form.errors.existing_images }}</span>
                </div>

                <!-- New Images -->
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Add New Images</label>
                    <input
                        type="file"
                        multiple
                        @change="handleNewImageUpload"
                        class="input"
                        accept="image/*"
                    />
                    <span v-if="form.errors.new_images" class="text-red-500 text-sm">{{ form.errors.new_images }}</span>
                    <span v-if="newImageErrors.length > 0" class="text-red-500 text-sm">{{ newImageErrors.join(', ') }}</span>
                    <div v-if="newPreviewImages.length" class="mt-4 grid grid-cols-4 gap-4">
                        <div
                            v-for="(image, index) in newPreviewImages"
                            :key="index"
                            class="relative"
                        >
                            <img :src="image" class="rounded shadow max-h-32 object-cover" />
                            <button
                                type="button"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1"
                                @click="removeNewImage(index)"
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
                    <button type="submit" class="btn btn-primary">Update Product</button>
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
    product: Object,
    categories: Array,
    existingImages: Array,
});

// Form and validation state
const form = useForm({
    name: props.product.name,
    category_id: props.product.category_id,
    cost_price: props.product.cost_price,
    regular_price: props.product.regular_price,
    sale_price: props.product.sale_price,
    stock: props.product.stock,
    description: props.product.description,
    new_images: [], // For new image uploads
    removed_images: [], // For tracking removed existing images
})

// State for managing previews and errors
const newPreviewImages = ref([]) // Previews for new images
const newImageErrors = ref([]) // Errors for new image validation

// Handle new image upload
function handleNewImageUpload(event) {
    const files = Array.from(event.target.files)

    // Clear previous errors
    newImageErrors.value = []

    // Validate number of images and sizes
    if (files.length + existingImages.length + form.new_images.length > 6) {
        newImageErrors.value.push('You can only upload up to 6 images.')
        return
    }

    const validFiles = files.filter((file) => {
        if (file.size > 1024 * 1024) {
            newImageErrors.value.push(`${file.name} exceeds the 1MB size limit.`)
            return false
        }
        return true
    })

    // Add valid files to form and previews
    form.new_images = [...form.new_images, ...validFiles]
    newPreviewImages.value = [
        ...newPreviewImages.value,
        ...validFiles.map((file) => URL.createObjectURL(file)),
    ]
}

// Remove new image
function removeNewImage(index) {
    form.new_images.splice(index, 1)
    newPreviewImages.value.splice(index, 1)
}

// Remove existing image
function removeExistingImage(id) {
    form.removed_images.push(id)
    const index = existingImages.findIndex((img) => img.id === id)
    existingImages.splice(index, 1)
}

// Submit the form
function submit() {
    form.put(route('admin.products.update', props.product.id))
}
</script>
