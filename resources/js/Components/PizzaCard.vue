<template>
  <div class="max-w-sm rounded overflow-hidden shadow-lg" @click="toogleModal">
    <div class="h-48 overflow-hidden flex items-center justify-center">
      <img :src="imageUrl" :alt="pizza.name" class="w-full min-h-full object-cover object-center">
    </div>
    <div class="px-6 py-4 h-40 flex flex-col justify-between">
      <div>
        <div class="font-bold text-xl mb-2">{{ pizza.name }}</div>
        <p class="text-gray-700 text-base line-clamp-2">
          {{ formattedIngredients }}
        </p>
      </div>
      <p class="font-bold text-gray-700 text-base">
        ${{ orderPrice }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { defineProps, computed } from 'vue';

const props = defineProps({
  pizza: Object,
  toggleModal: Function,
});

const toogleModal = () => {
  props.toggleModal(props.pizza);
};

const joinIngredients = (ingredients) => {
  const last = ingredients.slice(-1)[0];
  const initial = ingredients.slice(0, -1);
  return initial.map(i => i.name).join(', ') + (initial.length ? ' and ' : '') + last.name;
};

const formattedIngredients = computed(() => {
  if (props.pizza.ingredients.length === 0) return '';
  return joinIngredients(props.pizza.ingredients);
});

const imageUrl = computed(() => `/img/pizzas/${props.pizza.name}.jpg`);

const calculateIngredientsPrice = () => {
    return props.pizza.ingredients.reduce((total, ingredient) => {
        return total + ingredient.price;
    }, 0);
};

const calculateOrderPrice = () => {
    const ingredientsPrice = calculateIngredientsPrice();
    const orderPrice = ingredientsPrice * 1.5;
    return orderPrice.toFixed(2);
};

const orderPrice = computed(() => {
    return calculateOrderPrice();
});

</script>
