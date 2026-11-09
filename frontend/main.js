import { createApp } from 'vue'
import Suche from './Suche.vue'

// Jeder Kasten, den der Shortcode ausgibt, bekommt seine eigene kleine Vue-App.
document.querySelectorAll('[data-immo-kit-search]').forEach((el) => {
  const props = JSON.parse(el.dataset.immoKitSearch) // { restUrl, titel } aus PHP
  createApp(Suche, props).mount(el)
})
