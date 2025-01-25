<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Pagination from '@/Components/Pagintation.vue';
import Alert from '@/Components/Alert.vue';

const props = defineProps({
    category: Object, // Parent category
    subcategories: Object, // Paginated subcategories
});

// Initialize form with Inertia.js
const form = useForm({
    name: '',
});

// Reference to the alert component
const stackedAlerts = ref(null);

// Add a new subcategory
const addSubcategory = () => {
    form.post(route('admin.subcategories.store', props.category.id), {
        onSuccess: () => {
            stackedAlerts?.value.addAlert('success', 'Subcategory added successfully.');
            form.reset(); // Reset form on success
        },
        onError: () => {
            stackedAlerts?.value.addAlert('danger', 'Failed to add the subcategory.');
        },
    });
};

// Delete a subcategory
const deleteSubcategory = (id) => {
    if (confirm('Are you sure you want to delete this subcategory?')) {
        form.delete(route('admin.subcategories.destroy', [category.id, id]), {
            onSuccess: () => {
                stackedAlerts?.value.addAlert('success', 'Subcategory deleted successfully.');
            },
            onError: () => {
                stackedAlerts?.value.addAlert('danger', 'Failed to delete the subcategory.');
            },
        });
    }
};
</script>

<template>
    <AdminLayout :title="`Subcategories for ${category.name}`">
        <!-- Alerts -->
        <Alert ref="stackedAlerts" />

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Subcategories for {{ category.name }}</h1>
        </div>

        <!-- Add Subcategory Form -->
        <form @submit.prevent="addSubcategory" class="mb-6 flex items-center space-x-4">
            <TextInput
                id="name"
                v-model="form.name"
                type="text"
                placeholder="Subcategory Name"
                class="flex-1"
            />
            <PrimaryButton :disabled="form.processing">Add Subcategory</PrimaryButton>
        </form>

        <!-- Subcategories Table -->
        <div v-if="subcategories.data.length > 0">
            <table class="w-full bg-white shadow rounded-xl">
                <thead>
                <tr>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="subcategory in subcategories.data" :key="subcategory.id" class="border-t">
                    <td class="px-4 py-2">{{ subcategory.name }}</td>
                    <td class="px-4 py-2">
                        <DangerButton @click="deleteSubcategory(subcategory.id)">Delete</DangerButton>
                    </td>
                </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <Pagination :links="subcategories.links" />
        </div>

        <!-- No Subcategories Message -->
        <div v-else class="text-center text-gray-500 mt-8">
            <p>No subcategories found.</p>
        </div>
    </AdminLayout>
</template>
