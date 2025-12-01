<template>
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">
            {{ question.text }}
        </h2>

        <div class="space-y-3">
            <button
                v-for="(option, index) in question.options"
                :key="index"
                @click="$emit('select', index)"
                class="w-full text-left px-4 py-3 border-2 rounded-lg transition-all duration-200 hover:bg-blue-50"
                :class="selectedOption === index
                    ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-200'
                    : 'border-gray-200 text-gray-700'"
            >
                <div class="flex items-center">
                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center mr-3 flex-shrink-0"
                         :class="selectedOption === index ? 'border-blue-500 bg-blue-500' : 'border-gray-300'">
                        <div v-if="selectedOption === index" class="w-2.5 h-2.5 bg-white rounded-full"></div>
                    </div>

                    <span>{{ option.text }}</span>
                </div>
            </button>
        </div>
    </div>
</template>

<script setup>
defineProps({
    question: {
        type: Object,
        required: true
    },
    selectedOption: {
        type: Number,
        default: null
    }
});

defineEmits(['select']);
</script>
