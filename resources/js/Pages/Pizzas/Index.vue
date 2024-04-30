<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PizzaCard from '@/Components/PizzaCard.vue';
defineProps({
    pizzas: Array,
})
</script>

<template>
    <AppLayout title="Pizzas">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pizzas
            </h2>
        </template>

        <div >
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mt-16 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4 p-4">
                    <div v-for="pizza in pizzas" :key="pizza.id">
                        <PizzaCard :pizza="pizza" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
