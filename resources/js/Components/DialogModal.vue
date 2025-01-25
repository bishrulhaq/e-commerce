<script setup>
import { ref } from 'vue';

const props = defineProps({
    modelValue: Boolean,
});

const emit = defineEmits(['update:modelValue']);
const closeModal = () => emit('update:modelValue', false);
</script>

<template>
    <div v-if="modelValue" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-4">
            <header class="text-lg font-semibold mb-4">
                <slot name="title" />
            </header>
            <section>
                <slot name="content" />
            </section>
            <footer class="mt-4 flex justify-end space-x-2">
                <slot name="footer" />
            </footer>
        </div>
    </div>
</template>
