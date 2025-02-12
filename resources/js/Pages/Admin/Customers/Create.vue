<template>
    <AdminLayout title="Create Customer">
        <Head title="Create Customer" />
        <div class="p-6 bg-white shadow-md rounded-lg">
            <h1 class="text-2xl font-bold mb-4">Create Customer</h1>

            <!-- Alert Messages -->
            <Alert ref="alertComponent" />

            <form @submit.prevent="submitCustomer">
                <div class="mb-4">
                    <label class="block text-gray-700">Customer Name:</label>
                    <TextInput v-model="form.name" class="border p-2 rounded w-full" placeholder="Enter full name" />
                    <p v-if="errorMessages.name" class="text-red-500 text-sm mt-1">{{ errorMessages.name }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Email Address:</label>
                    <TextInput v-model="form.email" class="border p-2 rounded w-full" placeholder="Enter email" />
                    <p v-if="errorMessages.email" class="text-red-500 text-sm mt-1">{{ errorMessages.email }}</p>
                </div>

                <PrimaryButton type="submit">Create Customer</PrimaryButton>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
    import { ref } from 'vue';
    import { Head, useForm, router } from '@inertiajs/vue3';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';
    import Alert from '@/Components/Alert.vue';

    const form = useForm({
        name: '',
        email: '',
    });

    const alertComponent = ref(null);
    const errorMessages = ref<Record<string, string>>({});

    const submitCustomer = () => {
        form.post(route('admin.customers.store'), {
            onSuccess: () => {
                alertComponent.value?.addAlert("success", "Customer created successfully! Login details sent via email.");
                form.reset();
            },
            onError: (errors) => {
                errorMessages.value = errors;
                alertComponent.value?.addAlert("error", "Failed to create customer. Please check the form.");
            },
        });
    };
</script>
