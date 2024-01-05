<template>
  <v-btn
    size="small"
    icon=""
    variant="outlined"
    class="d-block ml-1"
    :class="[{'text-green-lighten-4': !inFavorite}, {'text-green': inFavorite}]"
    @click="onToggle"
  >
    <v-icon
      v-if="inFavorite"
      aria-label="add to favorites"
    >
      mdi-heart
    </v-icon>
    <v-icon
      v-else
      aria-label="delete to favorites"
    >
      mdi-heart-outline
    </v-icon>
  </v-btn>
</template>
<script setup>
  const props = defineProps({
    questionId: [String, Number]
  })

  const showSnackbar = inject('showSnackbar', s => {})

  const store = useStore()

  const loading = ref(false)

  const inFavorite = computed(() => {
    return store.getters['favorites/favoritesIds']?.includes(props.questionId) || false
  })

  const onToggle = async () => {
    if (!loading.value) {
      if (!inFavorite.value) {
        await addToFavorite()
      } else {
        await deleteFromFavorite()
      }

      await store.dispatch('favorites/fetch')
    }
  }

  const addToFavorite = async () => {
    try {
      loading.value = true
      const { data } = await useApi().addFavorite({
        id: props.questionId
      })
      showSnackbar(_get(data, 'data.message', ''))
    } catch (event) {
      showSnackbar($t('error'))
    } finally {
      loading.value = false
    }
  }

  const deleteFromFavorite = async () => {
    try {
      loading.value = true
      const { data } = await useApi().deleteFavorite({
        id: props.questionId
      })
      showSnackbar(_get(data, 'data.message', ''))
    } catch (event) {
      showSnackbar($t('error'))
    } finally {
      loading.value = false
    }
  }
</script>
