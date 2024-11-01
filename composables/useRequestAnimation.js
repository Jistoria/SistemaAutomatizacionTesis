// composables/useRequestAnimation.js
import { ref, computed } from 'vue';

export function useRequestAnimation() {
  const isAnimating = ref(false); // Estado de la animación (abierta/cerrada)
  const animationType = ref('spinner'); // Tipo de animación predeterminado

  // Función para abrir la animación con un tipo específico
  const openAnimation = (type = 'spinner') => {
    animationType.value = type;
    isAnimating.value = true;
  };

  // Función para cerrar la animación
  const closeAnimation = () => {
    isAnimating.value = false;
  };

  // Computed para obtener el tipo de animación actual
  const currentAnimationType = computed(() => animationType.value);

  return {
    isAnimating,
    currentAnimationType,
    openAnimation,
    closeAnimation,
  };
}
