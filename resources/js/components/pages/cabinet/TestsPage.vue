<template>
  <div class="tests-page">
    <v-container fluid>
      <v-row>
        <v-col
          v-for="(test, index) in tests"
          :key="index"
          md="4"
          sm="6"
          xs="12"
        >
          <v-card
            class="mx-auto"
          >
            <v-card-item>
              <div>
                <div class="text-overline mb-1">
                  {{ useFormattedDate(_get(test, 'created_at', '')).formattedDateTime }}
                </div>
                <div class="text-h5 mb-1 font-weight-bold">
                  {{ _get(test, 'name', '') }}
                </div>
                <div class="text-caption">
                  {{ _get(test, 'description', '') }}
                </div>
              </div>
            </v-card-item>

            <v-card-actions>
              <v-btn
                color="primary"
                variant="flat"
                class="text-capitalize"
              >
                <template #prepend>
                  <v-icon icon="mdi-pencil"></v-icon>
                </template>
                {{ $t('change') }}
              </v-btn>
              <v-btn
                color="success"
                variant="flat"
                class="text-capitalize"
              >
                {{ $t('toStart') }}
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script setup>
  import * as api from '~/api'
  import { ref } from 'vue'
  import { useFormattedDate } from '~/hooks/useDate'

  const tests = ref([])
  try {
    const { data } = await api.tests()
    tests.value = data
  } catch (error) {
    console.error(error)
  }
</script>
<style lang="scss">

</style>
