<template>
  <v-card
    class="h-100 mx-auto d-flex flex-column"
  >
    <v-card-item>
      <div>
        <div class="text-overline mb-1">
          {{ useFormattedDate(created_at).formattedDateTime }}
        </div>
        <div class="text-h5 mb-1 font-weight-bold">
          {{ name }}
        </div>
        <div class="text-caption">
          {{ description }}
        </div>
      </div>
    </v-card-item>

    <v-card-actions
      class="flex-wrap mt-auto"
    >
      <router-link
        v-if="isAdmin"
        :to="{name: 'test-update', params: { test_id: props.id }}"
      >
        <v-btn
          color="primary"
          variant="flat"
          class="text-none mx-2 my-2"
        >
          <template #prepend>
            <v-icon icon="mdi-pencil" />
          </template>
          {{ $t('edit') }}
        </v-btn>
      </router-link>
      <router-link :to="{name: 'testing', params: { test_id: props.id }}">
        <v-btn
          color="success"
          variant="flat"
          class="text-none mx-2 my-2 mr-auto"
        >
          <template #prepend>
            <v-icon icon="mdi mdi-clock-start" />
          </template>
          {{ $t('toStart') }}
        </v-btn>
      </router-link>

      <v-btn
        v-if="isAdmin"
        color="red"
        variant="flat"
        class="text-none mx-2 my-2"
        @click="onOpenDialog"
      >
        <template #prepend>
          <v-icon icon="mdi mdi-delete-empty-outline" />
        </template>
        {{ $t('delete') }}
      </v-btn>
    </v-card-actions>

    <v-dialog
      v-model="dialog"
      persistent
      width="360"
      max-width="100%"
    >
      <v-card>
        <v-card-title class="text-h5 overflow-visible text-wrap">
          {{ $t('reallyWantDeleteTest.title') }} - <span class="font-weight-bold">{{ name }}</span>?
        </v-card-title>
        <v-card-text>
          {{ $t('reallyWantDeleteTest.description') }}
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            color="green-darken-1"
            variant="text"
            @click="onCloseDialog"
          >
            {{ $t('reallyWantDeleteTest.no') }}
          </v-btn>
          <v-btn
            :loading="loading"
            color="red-darken-1"
            variant="text"
            class="font-weight-bold"
            @click="onDeleteTest"
          >
            {{ $t('reallyWantDeleteTest.yes') }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>
<script setup>
  import * as api from '~/api'
  import { useFormattedDate } from '~/hooks/useDate'
  import { useAuth } from '~/hooks/useAuth'
  import { ref, inject } from 'vue'
  import { useI18n } from 'vue3-i18n'
  const { t: $t } = useI18n()

  const { isAdmin } = useAuth()

  const props = defineProps({
    id: [String, Number],
    name: String,
    description: String,
    // eslint-disable-next-line vue/prop-name-casing
    created_at: String,
    // eslint-disable-next-line vue/prop-name-casing
    updated_at: String
  })
  const showSnackbar = inject('showSnackbar', s => {})
  const emit = defineEmits(['update'])

  const dialog = ref(false)
  const onOpenDialog = () => {
    dialog.value = true
  }
  const onCloseDialog = () => {
    dialog.value = false
  }

  const loading = ref(false)
  const onDeleteTest = async () => {
    try {
      loading.value = true
      await api.testDelete({
        id: props.id
      })
      emit('update')
      showSnackbar($t('deleted'))
    } catch (error) {
      console.error(error)
    } finally {
      loading.value = false
    }
  }
</script>
