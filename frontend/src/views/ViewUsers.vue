<template>
  <main class="max-w-7xl mx-auto p-5 h-full flex flex-col">
    <VModal
      v-model="weatherDetailsModal.show"
      :title="weatherDetailsModal.title"
    >
      <div
        v-if="weatherDetailsModal.loading"
        class="flex justify-center item-center py-4"
      >
        <VIcon icon="spinner" class="text-gray-400 w-12 h-12 animate-spin" />
      </div>
      <div v-else>
        <div>
          <div class="flex items-center">
            <VIcon v-if="currentWeather" :icon="currentWeather.icon" class="w-16 h-16" />
            <span class="text-5xl flex ml-5">
              {{ Math.round(currentWeather?.temp ?? 0) }}°<span
                class="text-xl font-normal mt-0.5"
                >F</span
              >
            </span>
          </div>
          <span class="block mt-3 text-lg">{{
            currentWeather?.description
          }}</span>
        </div>
        <div class="grid grid-cols-2 gap-x-5 text-lg text-gray-700 mt-2">
          <div
            v-for="item in [
              {
                label: 'Feels Like',
                value: `${Math.round(currentWeather?.feelsLike ?? 0)}°F`,
              },
              { label: 'Pressure', value: `${currentWeather?.pressure} mb` },
              { label: 'Humidity', value: `${currentWeather?.humidity}%` },
              { label: 'Visibility', value: `${currentWeather?.visibility} m` },
              { label: 'Wind', value: `${currentWeather?.windSpeed} mph` },
              { label: 'Cloud Cover', value: `${currentWeather?.clouds}%` },
            ]"
            :key="item.label"
            class="border-b flex py-2"
          >
            <span class="grow">{{ item.label }}</span
            ><span>{{ item.value }}</span>
          </div>
        </div>
      </div>
    </VModal>
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-4 px-1">
      Users
    </h1>
    <div ref="listContainerEl" class="overflow-auto px-1 grow">
      <UserList :users="users" @item-clicked="handleUserClicked" />
      <div
        ref="loadingEl"
        v-if="!hasMoreUsers"
        class="flex justify-center item-center py-4"
      >
        <VIcon icon="spinner" class="text-gray-400 w-12 h-12 animate-spin" />
      </div>
    </div>
  </main>
</template>

<script setup lang="ts">
import UserList from "@/modules/user/components/UserList.vue";
import { computed, onMounted, ref } from "vue";
import type { CurrentWeather, UserOverview } from "@/modules/user/types";
import {
  getCurrentWeather,
  getUsers,
  getUsersWeatherOverview,
} from "@/services/users";
import VIcon from "@/common/components/VIcon.vue";
import VModal from "@/common/components/VModal.vue";

const users = ref<UserOverview[]>([]);

const hasMoreUsers = ref(false);
let usersCursor: string | null = null;

const listContainerEl = ref<HTMLDivElement | null>(null);
const loadingEl = ref<HTMLDivElement | null>(null);
let observer: IntersectionObserver | null = null;

const weatherDetailsModal = ref({
  show: false,
  title: "",
  loading: true,
});

const currentWeather = ref<CurrentWeather | null>(null);

async function handleUserClicked(user: UserOverview) {
  weatherDetailsModal.value = {
    show: true,
    loading: true,
    title: user.fullName,
  };
  currentWeather.value = await getCurrentWeather(user.id);
  weatherDetailsModal.value.loading = false;
}

async function loadMoreUsers() {
  observer?.disconnect();

  const result = await getUsers(usersCursor);

  getUsersWeatherOverview(result.users.map((user) => user.id)).then(
    (weatherOverviews) => {
      weatherOverviews.forEach((weatherOverview) => {
        const user = users.value.find(
          (user) => user.id == weatherOverview.userId
        );
        if (user) {
          user.status = weatherOverview.status;
          user.temperature = weatherOverview.temp;
          user.statusIcon = weatherOverview.icon;
          user.loading = false;
        }
      });
    }
  );

  users.value = [...users.value, ...result.users];
  usersCursor = result.cursor;

  if (!result.cursor) {
    hasMoreUsers.value = true;
  } else {
    if (loadingEl.value) {
      observer?.observe(loadingEl.value);
    }
  }
}

onMounted(() => {
  observer = new IntersectionObserver(
    (entries: IntersectionObserverEntry[]) => {
      if (entries[0].isIntersecting) {
        loadMoreUsers();
      }
    },
    {
      root: listContainerEl.value,
      rootMargin: "0px",
      threshold: 0.4,
    }
  );

  if (loadingEl.value !== null) {
    observer?.observe(loadingEl.value);
  }
});
</script>
