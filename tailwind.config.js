/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        customYellow: '#f9dc5c',
        customBrownYellow: '#c36f09',
      }
    },
  },
  plugins: [],
}
