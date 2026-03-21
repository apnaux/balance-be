import { defineStore } from "pinia";
import { ref } from "vue";

export const useModalManager = defineStore('modal-manager', () => {
  const stack = ref([]);

  const push = (id) => {
    stack.value.push(id);
  };

  const pop = () => {
    stack.value.pop();
  }

  const getZIndex = (id) => {
    return 60 + (stack.value.indexOf(id) * 10)
  }

  return {stack, push, pop, getZIndex}
});
