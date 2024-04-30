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

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div v-for="pizza in pizzas" :key="pizza.id">
                        <PizzaCard :pizza="pizza" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
