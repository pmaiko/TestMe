<template>
  <div class="default-page flex-1-0 d-flex flex-column">
    <v-breadcrumbs
      v-if="_get(breadcrumbs, 'length', '')"
      :items="breadcrumbs"
      class="text-no-wrap overflow-x-auto print-hidden"
    >
      <template #divider>
        <v-icon icon="mdi-chevron-right" />
      </template>
    </v-breadcrumbs>
    <slot />
  </div>
</template>
<script setup>
  defineProps({
    breadcrumbs: Array
  })
  // import { ref } from 'vue'
  //
  // const items = ref([
  //   {
  //     title: 'Dashboard',
  //     disabled: false,
  //     href: 'breadcrumbs_dashboard'
  //   },
  //   {
  //     title: 'Link 1',
  //     disabled: false,
  //     href: 'breadcrumbs_link_1'
  //   },
  //   {
  //     title: 'Link 2',
  //     disabled: true,
  //     href: 'breadcrumbs_link_2'
  //   }
  // ])
</script>
