<template>
  <Teleport to="#modal" v-if="modelValue">
    <div
      class="absolute top-0 left-0 bg-black/20 w-full h-full flex items-center justify-center"
    >
      <div class="bg-white rounded-md shadow-lg mx-5 overflow-auto w-full max-w-2xl">
        <div class="p-2.5 flex items-center border-b">
          <span class="grow font-semibold ml-2 text-lg">{{ title }}</span>
          <button type="button" class="p-1 hover:bg-gray-200 rounded-md" @click="$emit('update:modelValue', false)">
            <VIcon icon="close" class="w-6 h-6 text-gray-600" />
          </button>
        </div>
        <div class="p-5 w-full">
          <slot></slot>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
import VIcon from "@/common/components/VIcon.vue";

defineProps<{ title: string; modelValue: boolean }>();

defineEmits<{
  (e: "update:modelValue", value: boolean): void;
}>();
</script>
