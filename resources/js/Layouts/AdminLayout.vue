<template>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-60 bg-gray-800 text-white p-4">
            <nav class="space-y-2">
                <Link :href="route('admin.dashboard')" class="block py-2 px-2 hover:bg-gray-700">
                    Dashboard
                </Link>
                <Link :href="route('admin.products.index')" class="block py-2 px-2 hover:bg-gray-700">
                    Products
                </Link>
                <Link :href="route('admin.profile.edit')" class="block py-2 px-2 hover:bg-gray-700">
                    Profile
                </Link>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 bg-gray-100">
            <header class="mb-4 flex justify-between items-center">
                <h1 class="text-xl font-bold">{{ title }}</h1>
                <Link :href="route('logout')" method="post" as="button" class="text-red-600">
                    Logout
                </Link>
            </header>
            <slot />
        </main>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    title: {
        type: String,
        default: 'Admin'
    }
})
</script>

<style scoped>

</style>
