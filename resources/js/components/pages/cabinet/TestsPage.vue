<template>
  <DefaultPage
    :breadcrumbs="breadcrumbs"
  >
    <v-container
      fluid
      class="tests-page h-100"
    >
      <v-row v-if="!loading">
        <v-col
          v-if="!_get(tests, 'length', '')"
          cols="12"
        >
          <VEmpty>
            {{ $t('emptyTests') }}
          </VEmpty>
        </v-col>
        <v-col
          v-else
        >
          <v-row v-if="isAdmin">
            <router-link :to="{ name: 'test-create' }">
              <v-btn
                color="blue"
                variant="flat"
                class="text-none mx-2 my-2"
              >
                <template #prepend>
                  <v-icon icon="mdi mdi-plus" />
                </template>
                {{ $t('createTest') }}
              </v-btn>
            </router-link>
          </v-row>
          <v-row class="flex-wrap">
            <v-col
              v-for="(test, index) in tests"
              :key="index"
              lg="4"
              md="6"
              cols="12"
              class="h-auto"
            >
              <TestCard
                v-bind="test"
                @update="getTests"
              />
            </v-col>
          </v-row>
        </v-col>
      </v-row>
      <VLoader
        v-else
      />
    </v-container>
  </DefaultPage>
</template>
<script setup>
  import DefaultPage from '~/components/layout/DefaultPage'
  import VLoader from '~/components/UI/VLoader'
  import VEmpty from '~/components/UI/VEmpty'
  import TestCard from '~/components/shared/TestCard'

  const { isAdmin } = useAuth()

  const tests = ref([])
  const loading = ref(true)

  const getTests = async () => {
    try {
      loading.value = true
      const { data } = await useApi().tests()
      tests.value = data
    } catch (error) {
      console.error(error)
    } finally {
      loading.value = false
    }
  }

  onMounted(() => {
    getTests()
  })

  const breadcrumbs = computed(() => [
    {
      title: $t('menu.cabinet'),
      disabled: true,
      to: '/cabinet'
    },
    {
      title: $t('menu.tests'),
      disabled: true,
      to: '/cabinet/test'
    }
  ])
</script>
<style lang="scss">

</style>
