<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';

const form = useForm({
    name: '',
});

const submit = () => {
    form.post(route('admin.categories.store'));
};
</script>

<template>
    <AdminLayout title="Create Category">
        <section>
            <header>
                <h2 class="text-lg font-medium text-gray-900">Create Category</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Add a new category for your store.
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
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <div class="flex items-center gap-4">
                    <PrimaryButton :disabled="form.processing">Create Category</PrimaryButton>
                    <Link
                        :href="route('admin.categories.index')"
                        class="text-gray-500 underline"
                    >
                        Cancel
                    </Link>
                </div>
            </form>
        </section>
    </AdminLayout>
</template>
