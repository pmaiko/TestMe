<template>
  <DefaultPage
    :breadcrumbs="breadcrumbs"
  >
    <v-container fluid>
      results-page
    </v-container>
  </DefaultPage>
</template>
<script setup>
  import DefaultPage from '~/components/layout/DefaultPage'

  const tests = ref([])
  const loading = ref(true)

  const getTests = async () => {
    try {
      loading.value = true
      const { data } = await useApi().getTestsWithResults()
      tests.value = data
    } catch (error) {
      console.error(error)
    } finally {
      loading.value = false
    }
  }

  const getTestResults = async () => {
    try {
      loading.value = true
      const { data } = await useApi().getTestResults(2)
      tests.value = data
    } catch (error) {
      console.error(error)
    } finally {
      loading.value = false
    }
  }

  onMounted(() => {
    getTests()
    getTestResults()
  })

  const breadcrumbs = computed(() => [
    {
      title: $t('menu.cabinet'),
      disabled: true,
      href: '/cabinet'
    },
    {
      title: $t('menu.results'),
      disabled: true,
      href: '/cabinet/test'
    }
  ])
</script>
<style lang="scss">
</style>

