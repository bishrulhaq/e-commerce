<template>
    <div class="flex min-h-screen max-h-full bg-gray-100">


        <aside class="w-64 bg-gray-800 text-white">
            <div class="p-6">
                <div class="flex shrink-0 items-center">
                    <Link :href="route('admin.dashboard')">
                        <ApplicationLogo
                            class="block h-9 w-auto fill-current text-white"
                        />
                    </Link>
                </div>

            </div>
            <nav class="mt-6 ">
                <ul>
                    <li>
                        <Link
                            :href="route('admin.dashboard')"
                            class="block py-2 px-6 hover:bg-gray-700"
                        >
                            Dashboard
                        </Link>
                    </li>
                    <li>
                        <Link
                            :href="route('admin.products.index')"
                            class="block py-2 px-6 hover:bg-gray-700"
                        >
                            Products
                        </Link>
                    </li>
                    <li>
                        <Link
                            :href="route('admin.categories.index')"
                            class="block py-2 px-6 hover:bg-gray-700"
                        >
                            Categories
                        </Link>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Navbar -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <h1 class="text-xl font-bold">Admin Dashboard</h1>

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button
                                @click="toggleDropdown"
                                class="flex items-center space-x-2 focus:outline-none"
                            >
                                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g stroke-width="0"></g>
                                    <g stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g>
                                        <circle cx="12" cy="9" r="3" stroke="#1C274C" stroke-width="1.5"></circle>
                                        <path
                                            d="M17.9691 20C17.81 17.1085 16.9247 15 11.9999 15C7.07521 15 6.18991 17.1085 6.03076 20"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"></path>
                                        <path
                                            d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"></path>
                                    </g>
                                </svg>
                                <span>Admin</span>
                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19 9l-7 7-7-7"
                                    ></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                v-show="dropdownOpen"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-20"
                            >
                                <Link
                                    :href="route('admin.profile.edit')"
                                    class="block px-4 py-2 text-gray-800 hover:bg-gray-200"
                                >
                                    Profile
                                </Link>
                                <form
                                    method="POST"
                                    :action="route('logout')"
                                    @submit.prevent="logout"
                                >
                                    <button
                                        type="submit"
                                        class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-200"
                                    >
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                <slot/>
            </main>
        </div>
    </div>
</template>

<script setup>
import {ref} from "vue";
import {useForm, Link} from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const dropdownOpen = ref(false);

// Toggle dropdown visibility
function toggleDropdown() {
    dropdownOpen.value = !dropdownOpen.value;
}

// Logout functionality
function logout() {
    useForm().post(route("logout"));
}
</script>

<style>
/* Optional transition for dropdown */
[aria-expanded="true"] {
    transition: opacity 0.2s ease-in-out;
}
</style>
