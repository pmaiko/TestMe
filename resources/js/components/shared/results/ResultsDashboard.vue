<template>
  <v-row>
    <v-col
      v-for="(item, index) in dashboardData"
      :key="index"
      cols="6"
      md="3"
    >
      <v-card
        :color="item.color"
        variant="tonal"
        min-height="150"
        class="h-100 d-flex flex-column"
      >
        <v-card-item class="h-100">
          <v-card-title
            class="text-wrap mb-1"
            :class="[
              {'text-h4': item.type !== 'time'},
              {'text-h5': item.type === 'time'}
            ]"
          >
            {{ item.value }}
          </v-card-title>
          <v-card-subtitle class="text-wrap text-subtitle-1">
            {{ item.label }}
          </v-card-subtitle>
        </v-card-item>

        <!--<v-card-text>-->
        <!--  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.-->
        <!--</v-card-text>-->
      </v-card>
    </v-col>
  </v-row>
</template>
<script setup>
  const dashboardData = ref(null)
  const testId = useRoute().params.testId

  onMounted(() => {
    getResultsDashboard()
  })

  const getResultsDashboard = async () => {
    try {
      const { data: { data } } = await useApi().getResultsDashboard(testId)
      const colors = {
        count: 'cyan',
        time: 'purple',
        countQuestions: 'orange',
        countSuccesses: 'green',
        countErrors: 'red',
        countMisses: '',
        percentage: 'teal'
      }
      dashboardData.value = data.map((item) => {
        const [tmp] = Object.entries(item)
        const [key, value] = tmp
        return {
          ...value,
          type: key,
          color: colors[key] || 'blue-grey'
        }
      })
    } catch (event) {

    } finally {
      //
    }
  }
</script>
