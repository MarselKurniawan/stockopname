module.exports = {
  darkMode: "class",
  content: [
    "./**/*.php", // Semua file PHP di dalam proyek
    "./node_modules/preline/**/*.js", // Semua file JavaScript di direktori preline
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@preline/plugin'), // Plugin Preline
    require('@preline/overlay'), // Plugin Preline
    require('@preline/combobox'), // Plugin Preline
    require('@preline/theme-switch'), // Plugin Preline
  ],
};
