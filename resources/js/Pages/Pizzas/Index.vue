<template>
    <AppLayout title="Pizzas">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Catalogue
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mt-16 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4 p-4">
                    <div v-for="pizza in pizzas" :key="pizza.id">
                        <PizzaCard :pizza="pizza" :toggleModal="togglePizzaModal" />
                    </div>
                </div>
                <PizzaModal :pizza="selectedPizza" :allIngredients="allIngredients" :show="showPizzaModal"
                    :toggleModal="togglePizzaModal" :handleOrderSuccess="orderSuccess" :handleOrderError="orderError" />

                <OrderModal :show="showOrderModal" :title="orderModalTitle" :message="orderModalMessage" />

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
import OrderModal from '@/Components/OrderModal.vue';

const props = defineProps({
    pizzas: Array,
    allIngredients: Array,
});

const showPizzaModal = ref(false);
const showOrderModal = ref(false);
const selectedPizza = ref(null);
const orderModalTitle = ref('');
const orderModalMessage = ref('');

const togglePizzaModal = (pizza) => {
    selectedPizza.value = pizza;
    showPizzaModal.value = !showPizzaModal.value;
};

const orderSuccess = (data) => {
    orderModalTitle.value = 'Order Success';
    const pizzaName = `<p class="font-bold text-lg mb-2">${data.pizzaName}</p>`;
    const ingredients = data.originalIngredients.map(ingredient => {
        let style = '';
        if (data.removedIngredients.includes(ingredient)) {
            style += 'text-decoration: line-through; color: red;';
        }
        return `<li style="${style}">${ingredient}</li>`;
    });
    data.extraIngredients.forEach(extraIngredient => {
        if (!data.originalIngredients.includes(extraIngredient)) {
            ingredients.push(`<li style="color: green;">${extraIngredient}</li>`);
        }
    });
    const finalPrice = `<p class="font-bold text-right mt-2">Final Price: $${data.price}</p>`;
    orderModalMessage.value = `<div>${pizzaName}<div><ul class="max-h-48 overflow-y-scroll">${ingredients.join('')}</ul></div>${finalPrice}</div>`;
    showOrderModal.value = true;
};

const orderError = (errorMessage) => {
    console.log('entro error');
    orderModalTitle.value = 'Order Error';
    orderModalMessage.value = errorMessage;
    showOrderModal.value = true;
};

</script>