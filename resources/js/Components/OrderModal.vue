<template>
    <div v-show="show" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto flex items-center justify-center">
        <div class="bg-white shadow-md rounded-md w-96 max-w-full mx-auto max-h-full">
            <div class="p-6">
                <div class="text-center">
                    <h3 class="text-xl leading-6 font-medium text-gray-900">{{ title }}</h3>
                </div>
            </div>
            <div class="mx-4">
                <div v-html="message"></div>
            </div>
            <div class="mt-6 text-center">
                <button @click="show = !show"
                    class="mx-2 px-4 py-2 mb-2 bg-green-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">Close</button>
            </div>
        </div>
    </div>

</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    show: Boolean,
    title: String,
    message: String,
});


</script>