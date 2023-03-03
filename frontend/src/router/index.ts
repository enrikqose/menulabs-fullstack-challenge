import { createRouter, createWebHistory } from "vue-router";
import ViewUsers from "@/views/ViewUsers.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: ViewUsers,
    },
  ],
});

export default router;
