<template>
  <div
    class="border border-gray-200 hover:border-gray-300 p-5 rounded-lg bg-white hover:bg-gray-50 cursor-pointer flex items-center"
  >
    <div class="font-medium grow">
      {{ user.fullName }}
    </div>
    <div class="flex items-center">
      <div class="flex items-center min-w-[136px]">
        <div v-if="user.loading" class="w-7 h-7 bg-gray-200 rounded animate-pulse"></div>
        <VIcon v-else :icon="user.statusIcon" class="w-7 h-7" />
        <div v-if="user.loading" class="w-20 h-5 ml-2 bg-gray-200 rounded animate-pulse"></div>
        <span v-else class="block ml-2">{{ user.status }}</span>
      </div>
      <div class="font-medium ml-5 min-w-[70px] text-right">
        <div v-if="user.loading" class="w-10 h-5 bg-gray-200 rounded animate-pulse"></div>
        <span v-else>{{user.temperature}}°<span class="text-sm font-normal align-text-top">F</span></span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { UserOverview } from "@/modules/user/types";
import VIcon from "@/common/components/VIcon.vue";

defineProps<{ user: UserOverview }>();
</script>
