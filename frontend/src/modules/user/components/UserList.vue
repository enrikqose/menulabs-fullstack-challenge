<template>
  <div class="space-y-2">
    <UserListItem @click="$emit('itemClicked', user)" v-for="user in users" :key="user.id" :user="user"/>
  </div>
</template>

<script setup lang="ts">
import type { UserOverview } from "@/modules/user/types";
import UserListItem from "@/modules/user/components/UserListItem.vue";

defineProps<{ users: UserOverview[] }>();
defineEmits<{
  (e: "itemClicked", value: UserOverview): void;
}>();

</script>