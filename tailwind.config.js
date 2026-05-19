/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./*.{html,js}", "./!(build|dist|.*)/**/*.{html,js}"],
  theme: {
    extend: {
      colors: {
        "grays-black": "#000",
        "miscellaneous-keyboards-glyphs-primary": "#595959",
      },
    },
    screens: {
      mq450: {
        raw: "screen and (min-width: 751px) and (max-width: 750px)",
      },
      mq1050: {
        raw: "screen and (min-width: 1051px) and (max-width: 1050px)",
      },
      lg: {
        raw: "screen and (min-width: 1051px) and (max-width: 1200px)",
      },
    },
  },
  corePlugins: {
    preflight: false,
  },
};
