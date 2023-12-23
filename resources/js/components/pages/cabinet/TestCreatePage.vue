<template>
  <DefaultPage
    :breadcrumbs="breadcrumbs"
  >
    <v-container
      fluid
      class="tests-page h-100"
    >
      <TestForm
        :title="$t('newTest')"
        :buttonText="$t('createTest')"
        :errors="errors"
        :loading="loading"
        @submit="onSubmit"
      />
    </v-container>
  </DefaultPage>
</template>
<script setup>
  import DefaultPage from '~/components/layout/DefaultPage'
  import TestForm from '~/components/shared/TestForm'

  const router = useRouter()
  const errors = ref({})
  const loading = ref(false)

  const onSubmit = async (formData) => {
    try {
      loading.value = true
      errors.value = {}
      await useApi().testCreate(formData)
      await router.push({
        name: 'tests'
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
      title: $t('newTest'),
      disabled: true,
      to: { name: 'test-create' }
    }
  ])
</script>
