PAYMILL - Gambio Gx2
====================

Gambio (Version Gx2) Plugin for PAYMILL credit card and elv payments

# Update Note

To update the Gambio PAYMILL plugin you must reinstall the plugin to ensure 
that all needed tables are created.

## Your Advantages
* PCI DSS compatibility
* Payment means: Credit Card (Visa, Visa Electron, Mastercard, Maestro, Diners, Discover, JCB, AMEX, China Union Pay), Direct Debit (ELV)
* Optional fast checkout configuration allowing your customers not to enter their payment detail over and over during checkout
* Improved payment form with visual feedback for your customers
* Supported Languages: German, English, Spanish, French, Italian, Portuguese
* Backend Log with custom View accessible from your shop backend

# Installation

Download the following zip file and upload the extracted files in the root directory of your Gambio shop.

https://github.com/paymill/paymill-gambio/archive/master.zip

# Configuration

Afterwards enable PAYMILL in your shop backend and insert your test or live keys.

# In case of errors

In case of any errors check the PAYMILL log entry in the plugin config and 
contact the PAYMILL support (support@paymill.de).

# Notes about the payment process

The payment is processed when an order is placed in the shop frontend.

Fast Checkout: Fast checkout can be enabled by selecting the option in the PAYMILL Basic Settings. If any customer completes a purchase while the option is active this customer will not be asked for data again. Instead a reference to the customer data will be saved allowing comfort during checkout.
