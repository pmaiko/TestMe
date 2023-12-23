<template>
  <v-dialog
    v-model="dialog"
    persistent
    width="1024"
  >
    <template #activator="{ props }">
      <v-btn
        color="primary"
        size="x-small"
        variant="text"
        v-bind="props"
        class="mt-3"
      >
        {{ $t('registration') }}
      </v-btn>
    </template>
    <v-card>
      <v-form @submit.prevent="onSubmit">
        <v-card-title>
          <span class="text-h5">{{ $t('registration') }}</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col
                cols="12"
              >
                <v-text-field
                  v-model="formData['name']"
                  :error-messages="errors['name']"
                  :label="$t('name') + '*'"
                  required
                />
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="formData['email']"
                  :error-messages="errors['email']"
                  :label="$t('email') + '*'"
                  required
                  hint="example@gmail.com"
                  persistent-hint
                />
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="formData['password']"
                  :error-messages="errors['password']"
                  :label="$t('password') + '*'"
                  type="password"
                  required
                />
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="formData['password_confirmation']"
                  :error-messages="errors['password_confirmation']"
                  :label="$t('passwordConfirmation') + '*'"
                  type="password"
                  required
                />
              </v-col>
            </v-row>
          </v-container>
          <small>{{ $t('indicatesRequiredField') }}</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            type="button"
            color="blue-darken-1"
            variant="text"
            @click="onCloseDialog"
          >
            {{ $t('close') }}
          </v-btn>
          <v-btn
            :loading="loading"
            type="submit"
            color="blue-darken-1"
            variant="text"
          >
            {{ $t('toRegister') }}
          </v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
  </v-dialog>

  <v-dialog
    v-model="successDialog"
    width="auto"
  >
    <v-card>
      <v-card-text>
        {{ $t('newUserDialog') }}
      </v-card-text>
      <v-card-actions>
        <v-btn
          color="primary"
          block
          @click="successDialog = false"
        >
          {{ $t('close') }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script setup>
  const dialog = ref(false)
  const successDialog = ref(false)

  const loading = ref(false)
  const errors = ref({})
  const formData = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
  })

  const onSubmit = async () => {
    try {
      loading.value = true
      errors.value = {}
      await useApi().register(formData)
      successDialog.value = true
      dialog.value = false
    } catch (error) {
      errors.value = _get(error, 'response.data.errors') || {}
      console.log(error)
    } finally {
      loading.value = false
    }
  }

  const onCloseDialog = async () => {
    dialog.value = false
  }
</script>
