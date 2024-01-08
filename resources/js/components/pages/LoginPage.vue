<template>
  <DefaultPage>
    <v-container
      fluid
      class="flex-1-0 d-flex flex-column"
    >
      <v-row
        align="center"
        justify="center"
        class="h-100"
      >
        <v-col
          sm="8"
          md="3"
        >
          <p class="text-h5 text-center mb-4">
            {{ $t('enterToSystem') }}
          </p>
          <v-form @submit.prevent="onSubmit">
            <v-text-field
              v-model="formData['email']"
              :label="$t('email')"
              :error-messages="errors['email']"
              name="email"
              type="email"
            />
            <v-text-field
              v-model="formData['password']"
              :label="$t('password')"
              :error-messages="errors['password']"
              name="password"
              type="password"
            />

            <v-btn
              :loading="loading"
              type="submit"
              color="green"
              size="x-large"
              class="w-100"
            >
              {{ $t('enter') }}
            </v-btn>
            <TheModalRegister />
          </v-form>
        </v-col>
      </v-row>
    </v-container>
  </DefaultPage>
</template>
<script setup>
  import DefaultPage from '~/components/layout/DefaultPage'
  import TheModalRegister from '~/components/layout/TheModalRegister'

  const { setToken, setUser, redirectToCabinet } = useAuth()

  const formData = reactive({
    email: null,
    password: null
  })

  const errors = ref({})
  const loading = ref(false)

  const onSubmit = async () => {
    try {
      loading.value = true
      errors.value = {}
      const { data } = await useApi().login(formData)

      setToken(data.token)
      setUser(data.user)
      redirectToCabinet()
    } catch (error) {
      console.error(error)
      errors.value = _get(error, 'response.data.errors') || {}
    } finally {
      loading.value = false
    }
  }
</script>
<style lang="scss">

</style>
