<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

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
});

// State for managing previews and errors
const newPreviewImages = ref([]); // Previews for new images
const newImageErrors = ref([]); // Errors for new image validation

// Handle new image upload
function handleNewImageUpload(event) {
    const files = Array.from(event.target.files);

    // Clear previous errors
    newImageErrors.value = [];

    // Validate number of images and sizes
    if (files.length + props.existingImages.length + form.new_images.length > 6) {
        newImageErrors.value.push('You can only upload up to 6 images.');
        return;
    }

    const validFiles = files.filter((file) => {
        if (file.size > 1024 * 1024) {
            newImageErrors.value.push(`${file.name} exceeds the 1MB size limit.`);
            return false;
        }
        return true;
    });

    // Add valid files to form and previews
    form.new_images = [...form.new_images, ...validFiles];
    newPreviewImages.value = [
        ...newPreviewImages.value,
        ...validFiles.map((file) => URL.createObjectURL(file)),
    ];
}

// Remove new image
function removeNewImage(index) {
    form.new_images.splice(index, 1);
    newPreviewImages.value.splice(index, 1);
}

// Remove existing image
function removeExistingImage(id) {
    form.removed_images.push(id);
    const index = props.existingImages.findIndex((img) => img.id === id);
    props.existingImages.splice(index, 1);
}

// Submit the form
function submit() {
    form.put(route('admin.products.update', props.product.id), {
        preserveScroll: true,
    });
}
</script>

<template>
    <AdminLayout title="Edit Product">
        <section>
            <header>
                <h2 class="text-lg font-medium text-gray-900">Edit Product</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Update the details of your product using the form below.
                </p>
            </header>

            <form @submit.prevent="submit" class="mt-6 space-y-6">
                <!-- Product Name -->
                <div>
                    <InputLabel for="name" value="Product Name" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Enter product name"
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <!-- Category -->
                <div>
                    <InputLabel for="category_id" value="Category" />
                    <SelectInput
                        id="category_id"
                        v-model="form.category_id"
                        :options="categories"
                        option-label="name"
                        option-value="id"
                        class="mt-1 block w-full"
                        placeholder="Select a category"
                    />
                    <InputError :message="form.errors.category_id" class="mt-2" />
                </div>

                <!-- Prices -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <InputLabel for="cost_price" value="Cost Price" />
                        <TextInput
                            id="cost_price"
                            v-model="form.cost_price"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full"
                            placeholder="Enter cost price"
                        />
                        <InputError :message="form.errors.cost_price" class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="regular_price" value="Regular Price" />
                        <TextInput
                            id="regular_price"
                            v-model="form.regular_price"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full"
                            placeholder="Enter regular price"
                        />
                        <InputError :message="form.errors.regular_price" class="mt-2" />
                    </div>
                </div>

                <!-- Stock -->
                <div>
                    <InputLabel for="stock" value="Stock Quantity" />
                    <TextInput
                        id="stock"
                        v-model="form.stock"
                        type="number"
                        class="mt-1 block w-full"
                        placeholder="Enter stock quantity"
                    />
                    <InputError :message="form.errors.stock" class="mt-2" />
                </div>

                <!-- Existing Images -->
                <div>
                    <InputLabel for="existing_images" value="Existing Images" />
                    <div v-if="props.existingImages.length > 0" class="mt-4 grid grid-cols-4 gap-4">
                        <div
                            v-for="(image, index) in props.existingImages"
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
                    <InputError :message="form.errors.existing_images" class="mt-2" />
                </div>

                <!-- New Images -->
                <div>
                    <InputLabel for="new_images" value="Add New Images" />
                    <input
                        id="new_images"
                        type="file"
                        multiple
                        @change="handleNewImageUpload"
                        class="block w-full text-gray-900 mt-1"
                        accept="image/*"
                    />
                    <InputError :message="form.errors.new_images" class="mt-2" />
                    <span v-if="newImageErrors.length > 0" class="text-red-500 text-sm mt-2 block">
                        {{ newImageErrors.join(', ') }}
                    </span>
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
                    <InputLabel for="description" value="Description" />
                    <TextAreaInput
                        id="description"
                        v-model="form.description"
                        rows="4"
                        class="mt-1 block w-full"
                        placeholder="Enter product description"
                    />
                    <InputError :message="form.errors.description" class="mt-2" />
                </div>

                <!-- Submit Button -->
                <div class="flex items-center gap-4">
                    <PrimaryButton :disabled="form.processing">Update Product</PrimaryButton>
                    <Link
                        :href="route('admin.products.index')"
                        class="text-gray-500 underline"
                    >
                        Cancel
                    </Link>
                </div>
            </form>
        </section>
    </AdminLayout>
</template>
