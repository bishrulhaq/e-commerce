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
    categories: Array, // Categories for the dropdown
});

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
});

// Preview images for display
const previewImages = ref([]);
const imageErrors = ref([]); // For custom image validation errors

function handleImageUpload(event) {
    const files = Array.from(event.target.files);

    // Clear previous errors
    imageErrors.value = [];

    // Validate number of images
    if (files.length + form.images.length > 6) {
        imageErrors.value.push('You can only upload up to 6 images.');
        return;
    }

    // Validate image sizes
    const validFiles = files.filter((file) => {
        if (file.size > 1024 * 1024) {
            imageErrors.value.push(`${file.name} exceeds the 1MB size limit.`);
            return false;
        }
        return true;
    });

    // Add valid files to the form
    form.images = [...form.images, ...validFiles];

    // Update preview images
    previewImages.value = [
        ...previewImages.value,
        ...validFiles.map((file) => URL.createObjectURL(file)),
    ];
}

function removeImage(index) {
    form.images.splice(index, 1);
    previewImages.value.splice(index, 1);
}

// Submit the form
function submit() {
    form.post(route('admin.products.store'), {
        forceFormData: true, // Ensure the request is sent as FormData for file upload
    });
}
</script>

<template>
    <AdminLayout title="Create Product">
        <section>
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Create Product
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Add a new product to your store by filling out the form below.
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

                <!-- Images -->
                <div>
                    <InputLabel for="images" value="Product Images" />
                    <input
                        id="images"
                        type="file"
                        multiple
                        @change="handleImageUpload"
                        class="block w-full text-gray-900 mt-1"
                        accept="image/*"
                    />
                    <InputError :message="form.errors.images" class="mt-2" />
                    <span
                        v-if="imageErrors.length > 0"
                        class="text-red-500 text-sm mt-2 block"
                    >
                        {{ imageErrors.join(', ') }}
                    </span>
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
                    <PrimaryButton :disabled="form.processing">Create Product</PrimaryButton>
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
