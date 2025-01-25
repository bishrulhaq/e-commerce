<template>
    <AdminLayout :title="title">
        <Head :title="title" />

      <div class="space-y-4">
        <div class="p-4 bg-white rounded shadow">
          <h2 class="text-lg font-semibold mb-2">Welcome to Admin Dashboard</h2>
          <p class="text-sm text-gray-700">Here is an overview of your store's activity.</p>
        </div>

        <div class="grid grid-cols-3 gap-4">
          <div class="bg-white p-4 shadow rounded">
            <h3 class="text-gray-500 mb-1">Total Users</h3>
            <p class="text-xl font-bold">{{ stats.totalUsers }}</p>
          </div>
          <div class="bg-white p-4 shadow rounded">
            <h3 class="text-gray-500 mb-1">Total Orders</h3>
            <p class="text-xl font-bold">{{ stats.totalOrders }}</p>
          </div>
          <div class="bg-white p-4 shadow rounded">
            <h3 class="text-gray-500 mb-1">Total Products</h3>
            <p class="text-xl font-bold">{{ stats.totalProducts }}</p>
          </div>
        </div>
      </div>
    </AdminLayout>
  </template>

  <script setup>
  import AdminLayout from '@/Layouts/AdminLayout.vue'
  import {Head} from "@inertiajs/vue3";

  const props = defineProps({
    title: String,
    stats: {
      type: Object,
      default: () => ({
        totalUsers: 0,
        totalOrders: 0,
        totalProducts: 0,
      })
    }
  })
  </script>
