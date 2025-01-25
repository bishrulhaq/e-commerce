<template>
    <nav v-if="links && links.length > 1" class="flex justify-center mt-4">
        <ul class="flex gap-1">
            <li v-for="(link, i) in links" :key="i">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    as="button"
                    class="px-3 py-2 text-sm border rounded"
                    :class="{
            'bg-gray-200 font-semibold text-gray-700 cursor-default': link.active,
          }"
                    v-html="link.label"
                />
                <button
                    v-else
                    class="px-3 py-2 text-sm border rounded text-gray-400 cursor-not-allowed"
                    v-html="link.label"
                ></button>
            </li>
        </ul>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    links: {
        type: Array,
        default: () => [] // Ensure links defaults to an empty array
    }
})
</script>
