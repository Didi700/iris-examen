/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class', // ✅ AJOUT DU DARK MODE
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'iris-yellow': {
          DEFAULT: '#FDB913',
          600: '#E49D0A',
        },
        'iris-blue': {
          DEFAULT: '#0066CC',
          600: '#0052A3',
        },
        'iris-black': {
          DEFAULT: '#1A1A1A',
          900: '#1A1A1A',
        },
        'iris-brown': '#8B5A2B',
      },
    },
  },
  plugins: [],
}