<template>
  <VTitle class="print-hidden">
    {{ props.title }}
  </VTitle>
  <v-row
    class="print-hidden"
  >
    <v-col
      sm="6"
      cols="12"
    >
      <v-form @submit.prevent="onSubmit">
        <v-text-field
          v-model="formData['name']"
          :label="$t('name') + '*'"
          :error-messages="errors['name']"
          variant="underlined"
          color="primary"
          name="name"
          type="text"
          class="mb-4"
        />
        <v-textarea
          v-model="formData['description']"
          :label="$t('description')"
          :error-messages="errors['description']"
          variant="underlined"
          color="primary"
          name="description"
          type="text"
          class="mb-4"
        />

        <v-btn
          :loading="loading"
          type="submit"
          color="green"
          size="x-large"
        >
          {{ props.buttonText }}
        </v-btn>
      </v-form>
    </v-col>
  </v-row>
</template>
<script setup>
  import VTitle from '~/components/UI/VTitle'

  const props = defineProps({
    title: String,
    buttonText: String,
    errors: Object,
    loading: Boolean,
    fieldsData: Object
  })
  const emit = defineEmits(['submit'])

  const formData = reactive({
    name: _get(props.fieldsData, 'name', ''),
    description: _get(props.fieldsData, 'description', '')
  })

  const onSubmit = () => {
    emit('submit', formData)
  }
</script>
