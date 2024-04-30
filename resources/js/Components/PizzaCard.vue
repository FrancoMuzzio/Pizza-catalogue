<template>
  <div class="max-w-sm rounded overflow-hidden shadow-lg">
    <div class="h-48 overflow-hidden flex items-center justify-center">
      <img :src="`/img/pizzas/${pizza.name}.jpg`" :alt="pizza.name"
        class="w-full min-h-full object-cover object-center">
    </div>
    <div class="px-6 py-4 h-40 flex flex-col justify-between">
      <div>
        <div class="font-bold text-xl mb-2">{{ pizza.name }}</div>
        <p class="text-gray-700 text-base">
          {{ formattedIngredients }}
        </p>
      </div>
      <p class="font-bold text-gray-700 text-base">
        ${{ pizza.price }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { defineProps, computed } from 'vue';

const props = defineProps({
  pizza: Object,
});

const joinIngredients = (ingredients) => {
  const last = ingredients.slice(-1)[0];
  const initial = ingredients.slice(0, -1);
  return initial.map(i => i.name).join(', ') + (initial.length ? ' and ' : '') + last.name;
};

const formattedIngredients = computed(() => {
  if (props.pizza.ingredients.length === 0) return '';
  return joinIngredients(props.pizza.ingredients);
});
</script>
