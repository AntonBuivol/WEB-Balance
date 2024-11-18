# Cypress End-to-End Tests for web-balance E-commerce Site

This repository contains end-to-end tests using **Cypress** for testing various features of the web-balance e-commerce site, including login, add-to-cart functionality, and checkout.

---

## 1. Login and Add to Cart Test

### Description
This test ensures that a user can log in with valid credentials, add random products to the cart, and complete the checkout process.

### Test Steps
1. **Navigate to Home Page**: The test starts by visiting the homepage of the web-balance website.
2. **Login**: The test clicks the login button, enters valid credentials (`username: maks`, `password: 123456`), and submits the login form.
3. **Verify Login**: After login, the test checks if the user is successfully logged in by verifying the presence of the user balance on the page (`#user-balance`).
4. **Add 5 Random Products to Cart**: The test selects 5 random products from the available items on the homepage and adds them to the shopping cart.
5. **Proceed to Checkout**: After adding the items to the cart, the test clicks the checkout button and fills out the required information (name, address, phone number).
6. **Submit Order**: The test completes the checkout process by submitting the order form.
7. **Verify Checkout**: The test ensures that the page title after submitting the order is as expected and that the user successfully completes the checkout.

### Expected Outcome
- The user is successfully logged in.
- 5 random products are added to the cart.
- The user can proceed to the checkout page and complete the order.

---

## 2. Checkout Test

### Description
This test ensures that users can proceed from the cart to checkout, providing the necessary details (name, address, phone number) and successfully completing the checkout process.

### Test Steps
1. **Login**: User logs in using valid credentials (`username: maks`, `password: 123456`).
2. **Navigate to Cart**: After logging in, the user clicks the cart button and is redirected to the cart page.
3. **Fill Checkout Form**: The user fills out the checkout form with name, address, and phone number.
4. **Submit Checkout Form**: The user submits the form and confirms the order.
5. **Verify Checkout Completion**: The test verifies that after submission, the user is redirected to a confirmation page or receives a successful message.

### Expected Outcome
- The user can successfully fill out the checkout form and submit the order.
- The user is redirected to a confirmation page or sees a success message.

---

## 3. Invalid Login Test

### Description
This test checks how the site responds to invalid login attempts using incorrect credentials. It ensures that the login fails and appropriate error messages are shown.

### Test Steps
1. **Navigate to Login Page**: The test visits the login page.
2. **Enter Invalid Credentials**: The test inputs invalid credentials (`username: invalidUser`, `password: wrongPassword`).
3. **Submit Login Form**: The test submits the login form.
4. **Verify Login Failure**: The test checks for the presence of an error message indicating that the login attempt was unsuccessful.

### Expected Outcome
- The login attempt fails.
- An error message is displayed to the user, indicating that the credentials are incorrect.

---

## 4. Add Item to Cart Test

### Description
This test ensures that when a user adds a product to the shopping cart, the product is successfully added and can be seen in the cart.

### Test Steps
1. **Navigate to Home Page**: The test starts by visiting the homepage of the H&M website.
2. **Select Product**: The test selects a product from the product list.
3. **Add to Cart**: The test clicks the "Add to Cart" button for the selected product.
4. **Verify Cart Update**: The test verifies that the cart has been updated and the selected product is now present in the cart.

### Expected Outcome
- The selected product is successfully added to the cart.
- The cart is updated and displays the added product.

---

### Summary

This repository contains comprehensive Cypress tests designed to ensure that the core functionalities of the H&M e-commerce site work as expected. These tests cover:

- **Login functionality** with valid and invalid credentials.
- **Add-to-cart functionality** including random selection of products.
- **Checkout process** with necessary form submissions and successful order completion.

These tests are designed to simulate a real user's experience and validate critical workflows on the website, ensuring that any changes to the site do not break its core functionality.

---

### Running the Tests

To run these tests, follow the instructions in the [installation section](#installation).

Once the tests are complete, you can view detailed reports in the `cypress/reports` folder. These reports include both HTML and JSON formats, generated using the Mochawesome reporter.

---

## Conclusion

By running these tests, you ensure that the H&M e-commerce website remains functional and user-friendly, particularly for core actions like login, adding products to the cart, and completing the checkout process.

## Table of tests
| **Date**        | **Test Case ID** | **Test Description**                          | **Expected Result**                       | **Actual Result**                         | **Status**  | **Notes**         |
|-----------------|------------------|-----------------------------------------------|------------------------------------------|-------------------------------------------|-------------|-------------------|
| 2024-11-18      | login            | User login functionality                     | User should be logged in successfully    | User logged in without issues             | Pass        |                   |
| 2024-11-18      | add 5 items to cart            | Add item to the cart                         | Item should appear in the cart           | Item added successfully                   | Pass        |                   |
| 2024-11-18      | checkout           | Checkout process with valid payment details   | Payment should be processed successfully | Payment processed without errors          | Pass        |                   |





