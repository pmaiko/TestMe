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

  import * as api from '~/api'
  import { ref, onMounted, computed } from 'vue'
  import { useRoute } from 'vue-router'
  import { useI18n } from 'vue3-i18n'
  const { t: $t } = useI18n()

  const route = useRoute()

  const fieldsData = ref(null)
  const testData = ref(null)
  const getTestLoading = ref(false)
  const getTest = async () => {
    try {
      getTestLoading.value = true
      const { data } = await api.test(route.params.test_id)
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

  const loading = ref(false)
  const errors = ref({})
  const onSubmit = async (formData) => {
    try {
      loading.value = true
      errors.value = {}
      await api.testUpdate({
        id: route.params.test_id,
        ...formData
      })
    } catch (error) {
      console.error(error)
      errors.value = _get(error, 'response.data.errors') || {}
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
