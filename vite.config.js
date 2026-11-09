import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// Baut frontend/main.js zu EINER Datei dist/immo-kit.js (+ dist/immo-kit.css),
// die WordPress mit wp_enqueue_script laden kann.
export default defineConfig({
  plugins: [vue()],
  define: {
    'process.env.NODE_ENV': JSON.stringify('production'),
  },
  build: {
    outDir: 'dist',
    emptyOutDir: true,
    lib: {
      entry: 'frontend/main.js',
      name: 'ImmoKit',
      formats: ['iife'],
      fileName: () => 'immo-kit.js',
    },
    rollupOptions: {
      output: { assetFileNames: 'immo-kit.[ext]' },
    },
  },
})
