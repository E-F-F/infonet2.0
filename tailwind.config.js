/** @type {import('tailwindcss').Config} */
module.exports = {
  // These paths are used by Tailwind to scan your files for CSS classes
  // and generate the final optimized CSS.
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/**/*.blade.php', // Scans all your Blade views
    './resources/**/*.js',       // Scans your JavaScript files for classes
    './node_modules/flowbite/**/*.js', // Necessary for Flowbite's JS components
  ],
  theme: {
    extend: {
      // This is where you define your custom fonts or other theme extensions
      fontFamily: {
        sans: [
          'Instrument Sans',
          'ui-sans-serif',
          'system-ui',
          'sans-serif',
          'Apple Color Emoji',
          'Segoe UI Emoji',
          'Segoe UI Symbol',
          'Noto Color Emoji'
        ],
      },
    },
  },
  plugins: [
    // Include the Flowbite plugin here to integrate its components with Tailwind
    require('flowbite/plugin')
  ],
};