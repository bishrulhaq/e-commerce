<script setup>
import { ref, onMounted } from 'vue';

// Props
const props = defineProps({
    options: {
        type: Array,
        required: true,
        default: () => [], // Array of objects [{ id, name, slug }]
    },
    placeholder: {
        type: String,
        default: 'Select an option', // Default placeholder
    },
    valueKey: {
        type: String,
        default: 'id', // Key to use as the value (e.g., 'id', 'slug')
    },
    labelKey: {
        type: String,
        default: 'name', // Key to use for displaying options
    },
});

// v-model support
const modelValue = defineModel({
    type: [String, Number], // Supports String or Number values
    required: true,
});

// Ref for the select input
const selectInput = ref(null);

// Auto-focus support
onMounted(() => {
    if (selectInput.value.hasAttribute('autofocus')) {
        selectInput.value.focus();
    }
});

// Expose the focus method
defineExpose({ focus: () => selectInput.value.focus() });
</script>

<template>
    <select
        v-model="modelValue"
        ref="selectInput"
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
    >
        <option value="" disabled>{{ placeholder }}</option>
        <option
            v-for="option in options"
            :key="option[valueKey]"
        :value="option[valueKey]"
        >
        {{ option[labelKey] }}
        </option>
    </select>
</template>
