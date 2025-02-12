<template>
    <DialogModal :model-value="isOpen" @update:model-value="emitClose">
        <template #title>Add Subcategory</template>
        <template #content>
            <div class="flex flex-col w-full space-y-4">
                <TextInput
                    v-model="form.name"
                    placeholder="Enter Subcategory Name"
                    @input="generateSlug"
                />
                <p class="text-sm text-gray-500">Slug: {{ slug }}</p>
            </div>
        </template>
        <template #footer>
            <PrimaryButton
                @click="submit"
                :disabled="form.processing"
            >
                Add
            </PrimaryButton>
            <SecondaryButton @click="close">Cancel</SecondaryButton>
        </template>
    </DialogModal>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import DialogModal from '@/Components/DialogModal.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import axios from 'axios';
import { slugify } from '@/utils/slugify';

// Props
const props = defineProps({
    isOpen: Boolean,
    category: Object,
});

// Emit events
const emit = defineEmits(['close', 'submitted']);

// Reactive state
const slug = ref('');
const form = useForm({
    name: '',
});

// Watch for changes in the name input and dynamically generate a slug
watch(() => form.name, async (newName) => {
    generateSlug(newName);
});

// Function to generate a unique slug
const generateSlug = async (name) => {
    if (!name) {
        slug.value = '';
        return;
    }

    let baseSlug = slugify(name);
    slug.value = baseSlug;

    try {
        const response = await axios.post(route('admin.subcategories.checkSlug'), {
            name,
            category_id: props.category.id,
        });

        if (response.data.exists) {
            slug.value = `${baseSlug}-${response.data.count}`;
        }
    } catch (error) {
        console.error('Error checking slug:', error);
    }
};

// Close the modal
const close = () => {
    emit('close');
    form.reset();
    slug.value = '';
};

// Emit close event when DialogModal's model-value is updated
const emitClose = (value) => {
    if (!value) close();
};

// Submit the form
const submit = () => {
    form.post(route('admin.subcategories.store', props.category.id), {
        onSuccess: () => {
            emit('submitted');
            close();
        },
        onError: () => {
            console.error('Failed to add subcategory.');
        },
    });
};
</script>
