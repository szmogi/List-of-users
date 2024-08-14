/** @type {import('tailwindcss').Config} */
module.exports = { 
  content: [
      './public/**/*.html',
    './src/**/*.{js,jsx,ts,tsx,vue}',
  ],
  theme: {
    extend: {
      colors: {
        'blueBaseColor': '#557A95',  
        'blue-lg-color' : '#7395AE',
        'grayColor':'#5D5C61',
        'gray-lg-Color':'#379683',
        'grayBrow':'#B1A296',    
            
      },
    },
  },
  plugins: [
      require('flowbite/plugin')
  ],
}
