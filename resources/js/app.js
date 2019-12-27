require('./bootstrap');
window.Noty = require('noty');

// Make all functions inside 'vinylShop.js' that start with 'export' accessible inside the HTML pages
window.vinylShop = require('./vinylShop');
// Run the hello() function
vinylShop.hello();
