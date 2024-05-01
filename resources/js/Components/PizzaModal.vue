<template>
    <div v-show="show" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto flex items-center justify-center">
        <div class="bg-white shadow-md rounded-md w-96 max-w-full mx-auto max-h-full">
            <div class="p-6">
                <div v-if="pizza">
                    <div class="text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ pizza.name }}</h3>
                        <img :src="imageUrl" :alt="pizza.name" class="mx-auto h-24 w-24 rounded-full">
                    </div>
                    <div class="mt-4">
                        <div class="max-h-60 overflow-y-auto">
                            <ul
                                class="divide-y divide-gray-200 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
                                <template v-for="ingredient in sortedIngredients" :key="ingredient.id">
                                    <li class="flex text-center py-2 mx-2">
                                        <Checkbox :checked="pizzaHasIngredient(ingredient)" :value="ingredient.name"
                                            @update:checked="toggleIngredient(ingredient)">
                                        </Checkbox>
                                        <span class="ml-3">{{ ingredient.name }}</span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 text-center">
                <button @click="postOrder"
                    class="mx-2 px-4 py-2 mb-2 bg-green-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">Order:
                    ${{ orderPrice }}</button>
                <button @click="toggleModal"
                    class="mx-2 px-4 py-2 mb-2 bg-red-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-green-300">Cancel</button>
            </div>
        </div>
    </div>

</template>

<script setup>
import { computed, defineProps, ref, watchEffect } from 'vue';
import Checkbox from '@/Components/Checkbox.vue';
import axios from 'axios';

const props = defineProps({
    pizza: Object,
    show: Boolean,
    toggleModal: Function,
    allIngredients: Array,
    handleOrderSuccess: Function,
    handleOrdererror: Function,
});

const editableIngredients = ref([]);

watchEffect(() => {
    if (props.pizza && props.pizza.ingredients) {
        editableIngredients.value = [...props.pizza.ingredients];
    }
});

function sortIngredients(allIngredients, pizzaIngredients) {
    const pizzaIngredientNames = new Set(pizzaIngredients.map(ingredient => ingredient.name));
    return [...allIngredients].sort((a, b) => {
        const aIsInPizza = pizzaIngredientNames.has(a.name);
        const bIsInPizza = pizzaIngredientNames.has(b.name);
        return bIsInPizza - aIsInPizza;
    });
}

const sortedIngredients = computed(() => sortIngredients(props.allIngredients, props.pizza.ingredients));

const imageUrl = computed(() => `/img/pizzas/${props.pizza.name}.jpg`);

const pizzaHasIngredient = (ingredient) => {
    return editableIngredients.value.some(pizzaIngredient => pizzaIngredient.name === ingredient.name);
};

const calculateIngredientsPrice = () => {
    return editableIngredients.value.reduce((total, ingredient) => total + ingredient.price, 0);
};

const calculateOrderPrice = () => {
    const ingredientsPrice = calculateIngredientsPrice();
    const orderPrice = ingredientsPrice * 1.5;
    return orderPrice.toFixed(2);
};

const orderPrice = computed(() => calculateOrderPrice());

const toggleModal = () => {
    props.toggleModal(props.pizza);
};

const toggleIngredient = (ingredient) => {
    const index = editableIngredients.value.findIndex(pizzaIngredient => pizzaIngredient.name === ingredient.name);
    if (index !== -1) {
        editableIngredients.value.splice(index, 1);
    } else {
        editableIngredients.value.push(ingredient);
    }
};

const postOrder = async () => {
    try {
        const response = await axios.post('/', {
            pizzaName: props.pizza.name,
            ingredients: editableIngredients.value.map(ing => ing.name),
        });
        props.handleOrderSuccess(response.data);

    } catch (error) {
        if (error.response) {
            props.handleOrderError(error.response.data.error);
        } else {
            console.error('Error message:', error.message);
        }
    } finally {
        toggleModal();
    }
};
</script>