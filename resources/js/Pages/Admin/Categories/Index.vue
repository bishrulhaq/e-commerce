<template>
    <AdminLayout title="Categories">
        <!-- Alert Component -->
        <Alert ref="stackedAlerts"/>

        <!-- Add Subcategory Modal -->
        <DialogModal v-model="isModalOpen">
            <template #title>Add Subcategory</template>
            <template #content>
                <div class="flex flex-wrap w-full">
                    <TextInput
                        v-model="form.name"
                        placeholder="Add Subcategory"
                    />
                </div>
            </template>
            <template #footer>
                <PrimaryButton
                    @click="submitSubcategory"
                    :disabled="form.processing"
                >
                    Add
                </PrimaryButton>
                <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
            </template>
        </DialogModal>

        <div class="flex justify-between mb-6">
            <h1 class="text-2xl font-semibold">Categories</h1>
            <Link :href="route('admin.categories.create')">
                <PrimaryButton>Add Category</PrimaryButton>
            </Link>
        </div>

        <div v-if="categories.data.length > 0">
            <div class="flex flex-wrap w-full bg-white shadow rounded-xl px-2">
                <div class="flex flex-wrap w-full p-2" v-for="category in categories.data" :key="category.id">
                    <div class="flex flex-wrap lg:w-2/3 w-full">
                        <div class="flex w-full space-x-2">
                            <SecondaryButton @click="openModal(category)">
                             <span class="w-6 h-6">
                               <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g
                                   stroke-width="0"></g><g stroke-linecap="round" stroke-linejoin="round"></g><g> <circle
                                   cx="4" cy="7" r="2" stroke="#000000" stroke-width="2" stroke-linecap="round"
                                   stroke-linejoin="round"></circle> <circle cx="20" cy="7" r="2" stroke="#000000"
                                                                             stroke-width="2" stroke-linecap="round"
                                                                             stroke-linejoin="round"></circle> <circle
                                   cx="20" cy="17" r="2" stroke="#000000" stroke-width="2" stroke-linecap="round"
                                   stroke-linejoin="round"></circle> <path d="M18 7H6" stroke="#000000" stroke-width="2"
                                                                           stroke-linecap="round"
                                                                           stroke-linejoin="round"></path> <path
                                   d="M7 7V7C8.65685 7 10 8.34315 10 10V15C10 16.1046 10.8954 17 12 17H18"
                                   stroke="#000000" stroke-width="2" stroke-linecap="round"
                                   stroke-linejoin="round"></path> </g></svg>
                            </span>
                            </SecondaryButton>
                            <SecondaryButton
                                :disabled="!category.subcategories.length"
                                @click="toggleCollapse(category.id)">
                            <span class="flex w-5 h-6 items-center justify-center">
                                {{ collapsedCategories[category.id] ? '-' : '+' }}
                            </span>
                            </SecondaryButton>
                            <span>
                              {{ category.name }}
                         </span>
                        </div>
                        <div v-show="collapsedCategories[category.id]" class="flex w-full py-4 px-1">
                            <ul class="p-2 bg-gray-100 w-full rounded-xl">
                                <li
                                    v-for="subcategory in category.subcategories"
                                    :key="subcategory.id"
                                    class="flex justify-between items-center mb-2 border-b"
                                >
                                    <span class="font-semibold px-2">{{ subcategory.name }}</span>
                                    <DangerButton
                                        @click="deleteSubcategory(category.id, subcategory.id)"
                                    >
                                        <svg class="w-6 h-6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                               stroke-linejoin="round"></g>
                                            <g id="SVGRepo_iconCarrier">
                                                <path d="M4 7H20" stroke="#ffffff" stroke-width="2"
                                                      stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path
                                                    d="M6 7V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18V7"
                                                    stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path
                                                    d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z"
                                                    stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </g>
                                        </svg>
                                    </DangerButton>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category Actions -->
                    <div class="flex lg:w-1/3 w-full space-x-2 justify-end">
                        <Link :href="route('admin.categories.edit', category.id)">
                            <SecondaryButton>Edit</SecondaryButton>
                        </Link>
                        <span>
                            <DangerButton @click="deleteCategory(category.id)">Delete</DangerButton>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <Pagination :links="categories.links"/>
        </div>

        <!-- No Categories Message -->
        <div v-else class="text-center text-gray-500 mt-8">
            <p>No categories found.</p>
        </div>
    </AdminLayout>
</template>

<script setup>
import {ref} from 'vue';
import {Link, useForm} from '@inertiajs/vue3';
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Alert from "@/Components/Alert.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Pagination from "@/Components/Pagintation.vue";
import TextInput from "@/Components/TextInput.vue";
import DialogModal from "@/Components/DialogModal.vue";

const props = defineProps({
    categories: Object, // Paginated categories with nested subcategories
});

const stackedAlerts = ref(null); // Reference to the Alert component
const collapsedCategories = ref({}); // State for collapsing subcategories
const isModalOpen = ref(false); // State for modal visibility
const selectedCategory = ref(null); // Track the category for adding subcategories

const form = useForm({
    name: '', // For adding a new subcategory
});

// Toggle subcategory collapse
const toggleCollapse = (categoryId) => {
    collapsedCategories.value[categoryId] = !collapsedCategories.value[categoryId];
};

// Open modal to add a subcategory
const openModal = (category) => {
    selectedCategory.value = category;
    if (category.subcategories.length) {
        collapsedCategories.value[category.id] = true;
    }
    isModalOpen.value = true;
};

// Close the modal
const closeModal = () => {
    isModalOpen.value = false;
    form.reset(); // Clear form inputs
};

// Submit subcategory form
const submitSubcategory = () => {
    if (!selectedCategory.value) return;

    form.post(route('admin.subcategories.store', selectedCategory.value.id), {
        onSuccess: () => {
            stackedAlerts?.value.addAlert('success', 'Subcategory added successfully.');
            closeModal(); // Close the modal
        },
        onError: () => {
            stackedAlerts?.value.addAlert('danger', 'Failed to add the subcategory.');
        },
    });
};

// Delete Subcategory
const deleteSubcategory = (categoryId, subcategoryId) => {
    if (confirm('Are you sure you want to delete this subcategory?')) {
        form.delete(route('admin.subcategories.destroy', [categoryId, subcategoryId]), {
            onSuccess: () => {
                stackedAlerts?.value.addAlert('success', 'Subcategory deleted successfully.');
            },
            onError: () => {
                stackedAlerts?.value.addAlert('danger', 'Failed to delete the subcategory.');
            },
        });
    }
};

// Delete Category
const deleteCategory = (categoryId) => {
    if (confirm('Are you sure you want to delete this category?')) {
        form.delete(route('admin.categories.destroy', categoryId), {
            onSuccess: () => {
                stackedAlerts?.value.addAlert('success', 'Category deleted successfully.');
            },
            onError: () => {
                stackedAlerts?.value.addAlert('danger', 'Failed to delete the category.');
            },
        });
    }
};
</script>
