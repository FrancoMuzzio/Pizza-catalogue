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
import axios from 'axios';

const props = defineProps({
  pizza: Object,
  toggleModal: Function,
});

const toogleModal = () => {
  props.toggleModal(props.pizza);
};

const postOrder = async () => {
    try {
        const response = await axios.post('/ruta-a-tu-controlador', {
            pizza: props.pizza,
            ingredients: editableIngredients.value.map(ing => ing.name),
        });
        alert(`Order success: ${response.data.message}`);
        // Aquí puedes optar por cerrar el modal después de un éxito
        // toggleModal(); // Descomenta esta línea si quieres cerrar el modal automáticamente
    } catch (error) {
        if (error.response) {
            // La petición fue hecha y el servidor respondió con un código de estado
            // que cae fuera del rango de 2xx
            console.error('Error response:', error.response.data);
            alert(`Order error: ${error.response.data.error}`);
        } else {
            // Algo sucedió en la configuración de la solicitud que provocó un Error
            console.error('Error message:', error.message);
        }
    }
};


const joinIngredients = (ingredients) => {
  const last = ingredients.slice(-1)[0];
  const initial = ingredients.slice(0, -1);
  return initial.map(i => i.name).join(', ') + (initial.length ? ' and ' : '') + last.name;
};

const formattedIngredients = computed(() => {
  if (!props.pizza || props.pizza.ingredients.length === 0) return '';
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
