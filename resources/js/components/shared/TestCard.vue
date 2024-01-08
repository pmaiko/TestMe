<template>
  <v-card
    class="h-100 mx-auto d-flex flex-column"
    elevation="6"
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
      <v-btn
        color="success"
        variant="flat"
        class="text-none mx-1 my-2"
        @click="onOpenDialogStart"
      >
        <template #prepend>
          <v-icon icon="mdi mdi-clock-start" />
        </template>
        {{ $t('toStart') }}
      </v-btn>
      <router-link
        v-if="isAdmin"
        :to="{name: 'test-update', params: { test_id: props.id }}"
      >
        <v-btn
          color="secondary"
          variant="flat"
          class="text-none mx-1 my-2"
        >
          <template #prepend>
            <v-icon icon="mdi-pencil" />
          </template>
          {{ $t('edit') }}
        </v-btn>
      </router-link>

      <v-btn
        v-if="isAdmin"
        color="red"
        variant="flat"
        icon=""
        size="small"
        class="text-none mx-1 my-2 ml-auto"
        @click="onOpenDialogDelete"
      >
        <v-icon icon="mdi mdi-trash-can-outline" />
      </v-btn>
    </v-card-actions>

    <v-dialog
      v-model="dialogStart"
      persistent
      width="460"
      max-width="100%"
    >
      <v-card>
        <v-card-title class="text-h5 overflow-visible text-wrap font-weight-bold">
          {{ $t('dialogStart.title') }}
        </v-card-title>
        <v-card-text class="px-4">
          <v-btn-toggle
            v-model="countQuestions"
            color="primary"
            variant="outlined"
            class="border-opacity-25 divide"
          >
            <v-btn
              v-for="count in counts"
              :key="count"
              :value="count"
              class="text-none"
            >
              {{ count }}
            </v-btn>
          </v-btn-toggle>

          <div class="mt-4 text-caption">
            {{ $t('dialogStart.description') }}:
          </div>

          <v-text-field
            v-model="countQuestions"
            :label="$t('dialogStart.placeholder')"
            variant="outlined"
            density="comfortable"
            inputmode="numeric"
            class="mt-4 mb-n4"
          />
        </v-card-text>
        <v-card-actions>
          <v-spacer />

          <v-btn
            color="red"
            variant="text"
            class="text-none"
            @click="onCloseDialogStart"
          >
            {{ $t('dialogStart.no') }}
          </v-btn>

          <router-link
            :to="{name: 'testing', params: { test_id: props.id }, query: { countQuestions: countQuestions || undefined }}"
          >
            <v-btn
              color="success"
              variant="flat"
              class="text-none mx-2 my-2"
            >
              <template #prepend>
                <v-icon icon="mdi mdi-clock-start" />
              </template>
              {{ $t('toStart') }}
            </v-btn>
          </router-link>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog
      v-model="dialogDelete"
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
            @click="onCloseDialogDelete"
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
  const props = defineProps({
    id: [String, Number],
    name: String,
    description: String,
    created_at: String,
    updated_at: String
  })

  const { isAdmin } = useAuth()

  const showSnackbar = inject('showSnackbar', s => {})
  const emit = defineEmits(['update'])

  const dialogDelete = ref(false)
  const dialogStart = ref(false)
  const countQuestions = ref(150)
  const counts = [50, 150, 250, 350, 'all']

  const onOpenDialogDelete = () => {
    dialogDelete.value = true
  }

  const onCloseDialogDelete = () => {
    dialogDelete.value = false
  }

  const onOpenDialogStart = () => {
    dialogStart.value = true
  }

  const onCloseDialogStart = () => {
    dialogStart.value = false
  }

  const loading = ref(false)
  const onDeleteTest = async () => {
    try {
      loading.value = true
      await useApi().testDelete({
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
