<template>
    <AppLayout title="Pizzas">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Catalogue
            </h2>
        </template>

        <div >
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mt-16 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4 p-4">
                    <div v-for="pizza in pizzas" :key="pizza.id">
                        <PizzaCard :pizza="pizza" :toggleModal="toggleModal" />
                    </div>
                </div>
                <PizzaModal :pizza="selectedPizza" :allIngredients="allIngredients" :show="showModal" :toggleModal="toggleModal"/>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { defineProps, ref } from 'vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PizzaCard from '@/Components/PizzaCard.vue';
import PizzaModal from '@/Components/PizzaModal.vue';

const props = defineProps({
    pizzas: Array,
    allIngredients: Array,
});

const showModal = ref(false);
const selectedPizza = ref(null);

const toggleModal = (pizza) => {
    selectedPizza.value = pizza;
    showModal.value = !showModal.value;
};

</script>