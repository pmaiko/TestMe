<template>
  <v-container
    fluid
    class="login-page fill-height"
  >
    <v-row
      align="center"
      justify="center"
      class="fill-height"
    >
      <v-col
        sm="8"
        md="3"
      >
        <!--<v-alert-->
        <!--  text="успих"-->
        <!--  type="success"-->
        <!--/>-->
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
          <ModalRegister />
        </v-form>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
  import ModalRegister from '~/components/ModalRegister'

  import * as api from '~/api'

  import { ref, reactive } from 'vue'
  import { useAuth } from '~/hooks/useAuth'

  export default {
    name: 'LoginPage',

    components: {
      ModalRegister
    },

    setup () {
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
          const { data } = await api.login(formData)

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

      return {
        formData,
        errors,
        loading,
        onSubmit
      }
    }
  }
</script>
<style lang="scss">

</style>
