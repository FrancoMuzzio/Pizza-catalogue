<template>
    <div class="pizza-card">
        <h3>{{ pizza.name }}</h3>
        <p>Price: ${{ pizza.price }}</p>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    pizza: Object,
});
</script>

<!-- <style scoped>
  .pizza-card {
    /* Estilos de la tarjeta de la pizza */
  }
  </style> -->