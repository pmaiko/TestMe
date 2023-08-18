<template>
  <v-app>
    <v-app-bar
      title="TestMe"
      icon="13123"
      color="red"
      scroll-behavior="elevate fade-image inverted"
      image="https://picsum.photos/1920/1080?random"
    >
      <v-app-bar-nav-icon />
    </v-app-bar>

    <v-main>
      <router-view />
    </v-main>
  </v-app>
</template>
<script>
  export default {
    setup: () => ({
      title: 'How To Install Vue 3 in Laravel 8 From Scratch'
    })
  }
</script>
