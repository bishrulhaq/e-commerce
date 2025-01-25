<script setup>
import { ref } from "vue";

// Reactive array to manage alert stack
const alerts = ref([]);

// Add an alert to the stack
function addAlert(type, message, duration = 3000) {
    const id = Date.now(); // Unique ID for each alert
    alerts.value.push({ id, type, message });

    // Automatically remove the alert after the specified duration
    setTimeout(() => {
        removeAlert(id);
    }, duration);
}

// Remove an alert from the stack
function removeAlert(id) {
    alerts.value = alerts.value.filter(alert => alert.id !== id);
}

defineExpose({ addAlert });
</script>

<template>
    <div class="fixed top-4 right-4 space-y-4 z-50">
        <div
            v-for="alert in alerts"
            :key="alert.id"
            :class="[
        'flex items-center space-x-4 px-4 py-3 rounded-lg shadow-lg text-white transition-transform',
        {
          'bg-green-500': alert.type === 'success',
          'bg-red-500': alert.type === 'danger',
          'bg-yellow-500': alert.type === 'warning',
          'bg-blue-500': alert.type === 'info',
        },
      ]"
        >
            <span>{{ alert.message }}</span>
            <button @click="removeAlert(alert.id)" class="text-xl font-bold">&times;</button>
        </div>
    </div>
</template>

<style scoped>
/* Optional: Add a fade-in/out effect */
.alert-enter-active,
.alert-leave-active {
    transition: opacity 0.5s, transform 0.3s ease-in-out;
}
.alert-enter-from,
.alert-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
