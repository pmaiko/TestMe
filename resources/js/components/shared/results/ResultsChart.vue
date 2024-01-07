<template>
  <LineChart
    :chartData="chartData"
    :options="options"
  />
</template>

<script setup>
  import colors from 'vuetify/lib/util/colors'

  import { LineChart } from 'vue-chart-3'
  import { Chart, registerables } from 'chart.js'

  import 'chartjs-adapter-moment'
  import moment from 'moment'
  moment.locale('uk')

  Chart.register(...registerables)

  const props = defineProps({
    attempts: Array
  })

  const attempts = computed(() => {
    return props.attempts
  })

  const labels = computed(() => {
    return attempts.value.map((item) => {
      return new Date(item.updatedAt).getTime()
    })
  })

  const countSuccesses = computed(() => {
    return attempts.value.map((item) => {
      return item.countSuccesses || 0
    })
  })

  const countErrors = computed(() => {
    return attempts.value.map((item) => {
      return item.countErrors || 0
    })
  })

  const countMisses = computed(() => {
    return attempts.value.map((item) => {
      return item.countMisses || 0
    })
  })

  const percentage = computed(() => {
    return attempts.value.map((item) => {
      return item.percentage || 0
    })
  })

  const countQuestions = computed(() => {
    return Math.max(...attempts.value.map((item) => {
      return +item.countQuestions || 0
    }))
  })

  const chartData = computed(() => {
    return {
      labels: labels.value,
      datasets: [
        {
          label: $t('countSuccesses'),
          borderColor: colors.green.base,
          backgroundColor: colors.green.base,
          tension: 0.1,
          borderWidth: 2,
          pointRadius: 3,
          data: countSuccesses.value
        },
        {
          label: $t('countErrors'),
          borderColor: colors.red.base,
          backgroundColor: colors.red.base,
          tension: 0.1,
          borderWidth: 2,
          pointRadius: 3,
          data: countErrors.value
        },
        {
          label: $t('countMisses'),
          borderColor: colors.grey.base,
          backgroundColor: colors.grey.base,
          tension: 0.1,
          borderWidth: 2,
          pointRadius: 3,
          data: countMisses.value
        },
        {
          label: $t('percentage'),
          borderColor: colors.teal.base,
          backgroundColor: colors.teal.base,
          tension: 0.1,
          borderWidth: 2,
          pointRadius: 3,
          data: percentage.value
        }
      ]
    }
  })

  const options = ref({
    responsive: true,
    plugins: {
      legend: {
        position: 'top'
      }
    },

    interaction: {
      intersect: false,
      mode: 'index'
    },
    scales: {
      x: {
        type: 'time',
        time: {
          unit: 'day',
          minUnit: 'day',
          displayFormats: {
            'millisecond': 'D MMMM',
            'second': 'D MMMM',
            'minute': 'D MMMM',
            'hour': 'D MMMM',
            'day': 'D MMMM',
            'week': 'D MMMM',
            'quarter': 'D MMMM',
            'month': 'D MMMM'
          },
          tooltipFormat: 'H:mm D, MMMM, YYYY' // Luxon format tokens
        },
        ticks: {
          autoSkip: true,
          padding: 3,
          minRotation: 0,
          maxRotation: 0,
          align: 'center',
          colors: 'red'
        },
        grid: {
          drawOnChartArea: true,
          drawTicks: true
        }
      },
      y: {
        beginAtZero: true,
        offset: false,
        grace: 2,
        suggestedMin: countQuestions,
        ticks: {
          autoSkip: true,
          stepSize: 20,
          font: {
            size: 10,
            lineHeight: 1.2
          }
        }
      }
    }
  })
</script>
