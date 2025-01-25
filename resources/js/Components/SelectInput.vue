<script setup>
const props = defineProps({
    options: {
        type: Array,
        required: true,
        default: () => [],
    },
    placeholder: {
        type: String,
        default: 'Select an option',
    },
    optionLabel: {
        type: String,
        default: 'name', // The key used to display the option
    },
    optionValue: {
        type: String,
        default: 'id', // The key used as the option value
    },
    modelValue: {
        type: [String, Number],
        required: true,
    },
});

const emit = defineEmits(['update:modelValue']);
</script>

<template>
    <select
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-full"
        :value="modelValue"
        @change="$emit('update:modelValue', $event.target.value)"
    >
        <option value="" disabled>{{ placeholder }}</option>
        <option
            v-for="option in options"
            :key="option[optionValue]"
            :value="option[optionValue]"
        >
            {{ option[optionLabel] }}
        </option>
    </select>
</template>
