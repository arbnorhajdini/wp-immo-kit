<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue'
import ImmoKarte from './ImmoKarte.vue'

const props = defineProps({
  restUrl: { type: String, required: true },
  titel: { type: String, default: 'Immobilien finden' },
})

// Was der Besucher eingibt
const filter = reactive({ ort: '', art: '', maxPreis: '', minZimmer: '' })

const immobilien = ref([])
const laedt = ref(false)
const fehler = ref('')
const merkliste = ref([])

const trefferText = computed(() =>
  laedt.value ? 'Suche läuft …' : `${immobilien.value.length} Immobilien gefunden`
)

async function laden() {
  laedt.value = true
  fehler.value = ''
  try {
    // URL-Objekt, damit es mit /wp-json/... und mit ?rest_route=... funktioniert
    const url = new URL(props.restUrl, window.location.origin)
    for (const [schluessel, wert] of Object.entries(filter)) {
      if (wert !== '' && wert !== null) url.searchParams.set(schluessel, wert)
    }
    const antwort = await fetch(url)
    if (!antwort.ok) throw new Error(antwort.status)
    immobilien.value = await antwort.json()
  } catch {
    fehler.value = 'Die Suche ist gerade nicht erreichbar.'
  } finally {
    laedt.value = false
  }
}

// Bei jeder Änderung neu suchen, aber erst nach 300 ms Pause (Debounce)
let timer
watch(filter, () => {
  clearTimeout(timer)
  timer = setTimeout(laden, 300)
})

onMounted(laden)

function umschalten(id) {
  merkliste.value = merkliste.value.includes(id)
    ? merkliste.value.filter((x) => x !== id)
    : [...merkliste.value, id]
}
</script>

<template>
  <section class="ik">
    <h2 class="ik__titel">{{ titel }}</h2>

    <form class="ik__filter" @submit.prevent="laden">
      <label>Ort
        <input v-model.trim="filter.ort" type="text" placeholder="z. B. Aachen" />
      </label>
      <label>Art
        <select v-model="filter.art">
          <option value="">egal</option>
          <option value="kauf">Kaufen</option>
          <option value="miete">Mieten</option>
        </select>
      </label>
      <label>Max. Preis (€)
        <input v-model.number="filter.maxPreis" type="number" min="0" step="50" />
      </label>
      <label>Zimmer ab
        <select v-model="filter.minZimmer">
          <option value="">egal</option>
          <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
        </select>
      </label>
    </form>

    <p class="ik__info" aria-live="polite">
      {{ trefferText }}
      <span v-if="merkliste.length"> · {{ merkliste.length }} gemerkt</span>
    </p>
    <p v-if="fehler" class="ik__fehler" role="alert">{{ fehler }}</p>

    <ul v-else class="ik__liste">
      <ImmoKarte
        v-for="immo in immobilien"
        :key="immo.id"
        :immobilie="immo"
        :gemerkt="merkliste.includes(immo.id)"
        @merken="umschalten"
      />
    </ul>
  </section>
</template>

<style scoped>
.ik { font-family: system-ui, sans-serif; max-width: 960px; margin: 0 auto; }
.ik__titel { margin: 0 0 16px; }
.ik__filter { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; }
.ik__filter label { display: flex; flex-direction: column; gap: 4px; font-size: 14px; }
.ik__filter input, .ik__filter select { padding: 8px; border: 1px solid #c8c8c8; border-radius: 6px; font: inherit; }
.ik__info { margin: 16px 0; color: #555; }
.ik__fehler { color: #b00020; }
.ik__liste { list-style: none; padding: 0; display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px; }
</style>
