<template>
  <DefaultPage
    :breadcrumbs="breadcrumbs"
  >
    <v-container
      fluid
      class="h-100"
    >
      <v-row v-if="!getTestLoading">
        <v-col
          v-if="!testData"
          cols="12"
        >
          <VEmpty>
            {{ $t('error') }}
          </VEmpty>
        </v-col>
        <v-col
          v-else
          cols="12"
        >
          <TestForm
            :title="$t('editTest')"
            :buttonText="$t('updateTest')"
            :errors="errors"
            :loading="loading"
            :fieldsData="fieldsData"
            @submit="onSubmit"
          />

          <v-divider class="my-8" />

          <v-row>
            <v-col
              cols="12"
              class="px-sm-8"
            >
              <p class="text-h6 text-uppercase">
                {{ $t('questions') }}
              </p>
              <v-row>
                <v-col
                  cols="12"
                >
                  <router-link
                    :to="{ name: 'test-update-question-create', params: { test_id: route.params.test_id } }"
                  >
                    <v-btn
                      color="blue"
                      variant="flat"
                      class="text-none mt-4"
                    >
                      <template #prepend>
                        <v-icon icon="mdi mdi-plus" />
                      </template>
                      {{ $t('createQuestion') }}
                    </v-btn>
                  </router-link>
                </v-col>
                <v-col
                  cols="12"
                >
                  <TestQuestions
                    v-if="_get(testData, 'questions.length', '')"
                    :questions="_get(testData, 'questions', [])"
                    @update="getTest"
                  />
                  <VEmpty v-else>
                    {{ $t('emptyQuestions') }}
                  </VEmpty>
                </v-col>
              </v-row>
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
  import TestForm from '~/components/shared/TestForm'
  import TestQuestions from '~/components/shared/TestQuestions'
  import VEmpty from '~/components/UI/VEmpty'
  import VLoader from '~/components/UI/VLoader'

  const route = useRoute()

  const fieldsData = ref(null)
  const testData = ref(null)
  const getTestLoading = ref(false)
  const getTest = async () => {
    try {
      getTestLoading.value = true
      const { data } = await useApi().test(route.params.test_id)
      fieldsData.value = {
        name: data.name,
        description: data.description
      }
      testData.value = data
    } catch (error) {
      console.error(error)
    } finally {
      getTestLoading.value = false
    }
  }
  onMounted(() => {
    getTest()
  })

  const showSnackbar = inject('showSnackbar', s => {})
  const loading = ref(false)
  const errors = ref({})
  const onSubmit = async (formData) => {
    try {
      loading.value = true
      errors.value = {}
      const { data } = await useApi().testUpdate({
        id: route.params.test_id,
        ...formData
      })
      showSnackbar(_get(data, 'message', ''))
    } catch (error) {
      showSnackbar($t('error'))
      errors.value = _get(error, 'response.data.errors') || {}
      console.error(error)
    } finally {
      loading.value = false
    }
  }

  const breadcrumbs = computed(() => [
    {
      title: $t('menu.cabinet'),
      disabled: true,
      to: '/cabinet'
    },
    {
      title: $t('menu.tests'),
      disabled: false,
      to: { name: 'tests' }
    },
    {
      title: $t('editTest'),
      disabled: true,
      to: { name: 'test-update', params: { test_id: route.params.test_id } }
    }
  ])
</script>
