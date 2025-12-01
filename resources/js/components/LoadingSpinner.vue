<template>
    <div class="flex flex-col items-center justify-center py-10">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        <p v-if="text" class="mt-4 text-gray-500 text-sm font-medium animate-pulse">
            {{ text }}
        </p>
    </div>
</template>

<script setup>
defineProps({
    text: {
        type: String,
        default: 'Carregando...'
    }
});
</script>
