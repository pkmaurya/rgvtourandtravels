<template>
  <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-gray-900">Earning Statistics</h3>
        <select class="text-sm border-gray-200 rounded-lg text-gray-500 focus:ring-indigo-500 focus:border-indigo-500">
            <option>This Week</option>
            <option>Last Week</option>
        </select>
    </div>
    <div class="relative h-64 w-full">
         <Line v-if="chartData" :data="chartData" :options="chartOptions" />
         <div v-else class="flex items-center justify-center h-full text-gray-400">Loading chart...</div>
    </div>
  </div>
</template>

<script setup>
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
} from 'chart.js'
import { Line } from 'vue-chartjs'

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
)

const props = defineProps({
    chartData: {
        type: Object,
        default: () => null
    }
})

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
      legend: {
          display: false
      },
      tooltip: {
          mode: 'index',
          intersect: false,
      }
  },
  scales: {
      y: {
          grid: {
              borderDash: [4, 4],
              drawBorder: false
          },
          ticks: {
              callback: function(value) {
                  return '$' + value;
              }
          }
      },
      x: {
          grid: {
              display: false
          }
      }
  }
}
</script>
