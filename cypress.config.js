const { defineConfig } = require('cypress');

module.exports = defineConfig({
  e2e: {
    specPattern: 'C:/Users/opilane/source/repos/testing/googleSearchTest.cy.js',  // Путь к вашим тестовым файлам
    reporter: 'mochawesome',  // Указание репортера Mochawesome
    reporterOptions: {
      reportDir: 'Cypress/reports',  // Папка для сохранения отчетов
      overwrite: false,  // Не перезаписывать старые отчеты
      html: true,  // Включить HTML-отчеты
      json: true,  // Включить JSON-отчеты
    },
  },
});
