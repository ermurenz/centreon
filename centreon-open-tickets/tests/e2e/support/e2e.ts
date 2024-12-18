import 'cypress-wait-until';
import './commands';
import 'cypress-real-events';

before(() => {
  Cypress.config('baseUrl', 'http://127.0.0.1:4000');

  cy.intercept('/waiting-page', {
    headers: { 'content-type': 'text/html' },
    statusCode: 200
  }).visit('/waiting-page');
});

Cypress.on('uncaught:exception', (err) => {
  if (
    err.message.includes('Request failed with status code 401') ||
    err.message.includes('Request failed with status code 403') ||
    err.message.includes('undefined') ||
    err.message.includes('canceled')  ||
    err.message.includes('Document is not focused')
  ) {
    return false;
  }

  return true;
});