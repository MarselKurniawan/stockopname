module.exports = {
  content: [
    "./views/**/*.php", // Sesuaikan dengan struktur file PHP Anda
    "./node_modules/preline/**/*.js", // Pastikan memindai file Preline JS
  ],
  theme: {
    extend: {},
  },
  plugins: [require("preline/plugin")],
};
