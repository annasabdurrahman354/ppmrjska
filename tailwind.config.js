/** @type {import('tailwindcss').Config} */
import preset from './vendor/filament/support/tailwind.config.preset'

export default {
  presets: [preset],
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    './app/Filament/**/*.php',
    './resources/views/filament/**/*.blade.php',
    './vendor/filament/**/*.blade.php',
    './vendor/bezhansalleh/filament-exceptions/resources/views/**/*.blade.php',
    './vendor/bezhansalleh/filament-panel-switch/resources/views/panel-switch-menu.blade.php',
    './vendor/awcodes/filament-quick-create/resources/**/*.blade.php',

  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
