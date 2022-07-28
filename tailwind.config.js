/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/views/*.blade.php',
    './resources/views/**/*.blade.php',
    './storage/framework/views/*.php',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [require('@tailwindcss/forms')],
}
