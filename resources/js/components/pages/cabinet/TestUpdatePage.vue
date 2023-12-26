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

          <v-divider
            id="divider"
            class="my-8"
          />

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
                  class="mt-8"
                >
                  <v-row
                    justify="space-between"
                  >
                    <v-col
                      cols="12"
                      md="9"
                      class="mb-n8"
                    >
                      <v-text-field
                        v-model="params.search"
                        :clearable="true"
                        :label="$t('searchByQuestionsAndAnswers')"
                        :loading="getTestLoading2"
                      />
                    </v-col>
                    <v-col
                      cols="12"
                      md="3"
                      class="mb-n8"
                    >
                      <v-select
                        v-if="order"
                        v-model="params.order"
                        :items="order.items"
                        itemTitle="label"
                        itemValue="value"
                        :label="$t('sort')"
                        :loading="getTestLoading2"
                      />
                    </v-col>
                  </v-row>
                </v-col>
                <v-col
                  cols="12"
                >
                  <div class="mx-n2">
                    <v-chip
                      variant="flat"
                      class="ma-2"
                    >
                      total: <span class="font-weight-bold">{{ _get(paginationMeta, 'total', '') }}</span>
                    </v-chip>

                    <v-chip
                      variant="flat"
                      color="primary"
                      class="ma-2"
                    >
                      length: <span class="font-weight-bold">{{ _get(items, 'length', '') }}</span>
                    </v-chip>

                    <v-chip
                      variant="flat"
                      color="secondary"
                      class="ma-2"
                    >
                      page: <span class="font-weight-bold">{{ _get(params, 'page', '') }}</span>
                    </v-chip>

                    <v-chip
                      variant="flat"
                      color="red"
                      class="ma-2"
                    >
                      perPage: <span class="font-weight-bold">{{ _get(paginationMeta, 'perPage', '') }}</span>
                    </v-chip>

                    <v-chip
                      variant="flat"
                      color="green"
                      class="ma-2"
                    >
                      lastPage: <span class="font-weight-bold">{{ _get(paginationMeta, 'lastPage', '') }}</span>
                    </v-chip>
                  </div>
                </v-col>
                <v-col
                  cols="12"
                >
                  <TestQuestions
                    v-if="_get(items, 'length', '')"
                    :questions="items"
                    @update="getTest"
                  />
                  <VEmpty v-else>
                    {{ $t('emptyQuestions') }}
                  </VEmpty>
                </v-col>
              </v-row>

              <v-pagination
                v-if="paginationMeta && paginationMeta.lastPage > 1"
                v-model="params.page"
                :length="paginationMeta.lastPage"
                class="mt-8"
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
  import { cloneDeep, debounce } from 'lodash'
  import searchTextHL from 'search-text-highlight'

  import DefaultPage from '~/components/layout/DefaultPage'
  import TestForm from '~/components/shared/TestForm'
  import TestQuestions from '~/components/shared/TestQuestions'
  import VEmpty from '~/components/UI/VEmpty'
  import VLoader from '~/components/UI/VLoader'

  const route = useRoute()

  const showSnackbar = inject('showSnackbar', s => {})
  const loading = ref(false)
  const errors = ref({})
  const getTestLoading = ref(false)
  const getTestLoading2 = ref(false)
  const fieldsData = ref(null)
  const testData = ref(null)
  const items = computed(() => {
    const questions = _get(testData.value, 'questions.items', [])
    if (params.search) {
      return questions.map((question) => {
        const newQuestion = cloneDeep(question)
        newQuestion.question = searchTextHL.highlight(newQuestion.question, params.search, { hlClass: 'bg-red-lighten-4' })
        newQuestion.answers = newQuestion.answers?.map(answer => {
          answer.answer = searchTextHL.highlight(answer.answer, params.search, { hlClass: 'bg-red-lighten-4' })
          return answer
        })
        return newQuestion
      })
    }
    return questions
  })
  const sorts = computed(() => {
    return _get(testData.value, 'questions.sorts')
  })
  const order = computed(() => {
    return _get(sorts.value, 'order', '') || null
  })
  const paginationMeta = computed(() => {
    return _get(testData.value, 'questions.pagination.meta', '') || null
  })
  const params = reactive({
    search: null,
    order: 'desc',
    page: 1
  })

  watch(() => params.search, async () => {
    params.page = 1
    await getTestDeb()
  })
  watch(() => params.page, async () => {
    document.getElementById('divider').scrollIntoView()
    await getTest()
  })
  watch(() => params.order, async () => {
    await getTest()
  })
  watch(() => order.value, () => {
    params.order = _get(order.value, 'items', []).find(item => item.current)?.value || null
  })

  const getTest = async () => {
    try {
      getTestLoading2.value = true
      const { data } = await useApi().test(route.params.test_id, {
        ...params
      })
      const response = data.data

      fieldsData.value = {
        name: response.name,
        description: response.description
      }
      testData.value = response
    } catch (error) {
      console.error(error)
    } finally {
      getTestLoading2.value = false
    }
  }
  const getTestDeb = debounce(() => {
    getTest()
  }, 300)

  onMounted(async () => {
    getTestLoading.value = true
    getTest().finally(() => {
      getTestLoading.value = false
    })
  })

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
