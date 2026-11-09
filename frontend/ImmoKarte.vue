<script setup>
import { computed } from 'vue'

const props = defineProps({
  immobilie: { type: Object, required: true },
  gemerkt: { type: Boolean, default: false },
})
const emit = defineEmits(['merken'])

const preisText = computed(() => {
  const euro = new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 })
    .format(props.immobilie.preis)
  return props.immobilie.art === 'miete' ? `${euro} / Monat` : euro
})
</script>

<template>
  <li class="karte">
    <span class="karte__art">{{ immobilie.art === 'miete' ? 'Miete' : 'Kauf' }}</span>
    <h3 class="karte__titel">{{ immobilie.titel }}</h3>
    <p class="karte__ort">{{ immobilie.ort }}</p>
    <p class="karte__daten">{{ immobilie.zimmer }} Zimmer · {{ immobilie.flaeche }} m²</p>
    <p class="karte__preis">{{ preisText }}</p>
    <button
      type="button"
      class="karte__merken"
      :aria-pressed="gemerkt"
      @click="emit('merken', immobilie.id)"
    >
      {{ gemerkt ? '★ Gemerkt' : '☆ Merken' }}
    </button>
  </li>
</template>

<style scoped>
.karte { border: 1px solid #e2e2e2; border-radius: 10px; padding: 16px; display: flex; flex-direction: column; gap: 4px; }
.karte__art { align-self: flex-start; font-size: 12px; background: #eef3ff; color: #2443a5; padding: 2px 8px; border-radius: 999px; }
.karte__titel { margin: 6px 0 0; font-size: 17px; }
.karte__ort, .karte__daten { margin: 0; color: #555; font-size: 14px; }
.karte__preis { margin: 8px 0; font-weight: 600; font-size: 18px; }
.karte__merken { align-self: flex-start; padding: 6px 12px; border: 1px solid #2443a5; background: #fff; color: #2443a5; border-radius: 6px; cursor: pointer; }
.karte__merken[aria-pressed='true'] { background: #2443a5; color: #fff; }
</style>
