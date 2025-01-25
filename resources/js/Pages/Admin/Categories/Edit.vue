<script setup>
import {useForm} from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    category: Object,
});

const form = useForm({
    name: props.category.name,
});

const submit = () => {
    form.put(route('admin.categories.update', props.category.id));
};
</script>

<template>
    <AdminLayout title="Edit Category">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Edit Category</h2>
            <p class="mt-1 text-sm text-gray-600">
                Update the category details.
            </p>
        </header>
        <form @submit.prevent="submit" class="mt-6 space-y-6">
            <div>
                <label for="name" class="block font-medium text-gray-700">Category Name</label>
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Enter category name"
                />
                <InputError :message="form.errors.name" class="mt-2"/>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Update Category</PrimaryButton>
                <Link :href="route('admin.categories.index')" class="text-gray-500 underline">Cancel</Link>
            </div>
        </form>
    </AdminLayout>
</template>
