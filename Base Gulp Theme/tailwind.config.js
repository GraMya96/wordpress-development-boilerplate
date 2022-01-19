module.exports = {
  mode: 'jit',
  darkMode: 'media',
  content: [ // very important for production
    "./**/*.{html,php}",
    "./*.{html,php}"
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
