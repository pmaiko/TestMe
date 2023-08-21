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
                :label="$t('name') + '*'"
                required
              />
            </v-col>
            <v-col cols="12">
              <v-text-field
                :label="$t('email') + '*'"
                required
                hint="example@gmail.com"
                persistent-hint
              />
            </v-col>
            <v-col cols="12">
              <v-text-field
                :label="$t('password') + '*'"
                type="password"
                required
              />
            </v-col>
            <v-col cols="12">
              <v-text-field
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
          color="blue-darken-1"
          variant="text"
          @click="dialog = false"
        >
          {{ $t('close') }}
        </v-btn>
        <v-btn
          color="blue-darken-1"
          variant="text"
          @click="dialog = false"
        >
          {{ $t('toRegister') }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
  import { ref } from 'vue'

  export default {
    setup () {
      const dialog = ref(false)

      return {
        dialog
      }
    }
  }
</script>
