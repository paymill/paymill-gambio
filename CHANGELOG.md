#Release Notes

##1.7.0
 * add brand logos with on/off setting per brand for the selection page and over the payment form
 * payment form fields now don't autocomplete the content, also the cvc field is now a password field
 * build a payment form which accept normal elv and sepa payments
 * sepa collecting date is now configurable and displayed on the order and invoice mail

##1.6.0
* Added Language Support for german, english, french, italian, spanish and portuguese
* Added improved early pan detection
* Added iban validation

##1.5.0
* Added SEPA Payment Form for german Direct Debit
* Added WebHooks. WebHooks will automatically synch your shops order states on refund or chargeback events
* Updated Fast Checkout
* Removed Paymill Label
* Fixed Log view

##1.4.0
- Fixed a bug causing crashes on installation if no earlier version has been installed
- Added Version number to the backend configuration
- Added additional validation for fast checkout
- Removed PAYMILL label
- Implemented optional SEPA direct debit form. only payments from germany are supported
- Disabled SEPA Option

##1.3.1
- Added improved Error Feedback
- Added Changelog

##1.3.0
- Changed the german name of the direct debit payment method to "ELV" from "Elektronisches Lastschriftverfahren"
- Added special handling for maestro credit cards without CVC
- Redesigned Logging
- Payments will no longer be selectable during checkout if there are no Keys available in the Backend
- Fixed multiple minor bugs

##1.2.1
- Fixed a bug regarding credit card icon behavior

##1.2.0
- Added Admin Log
- Reworked the way error urls got build
- Added "allowed Zones" option
- Moved payment form to the last checkout step

##1.0.7 & 1.0.6
- Added new css styling and credit card icons
- Fixed a bug regarding the missing cancel dialog during 3-D Secure
- Fixed a bug causing the module to log with or without the log option to be active
- Removed files no longer in use

##1.0.5
- Fixed several minor bugs

##1.0.4
- Made the Plugin multilingual
- Moved paymill files in paymill folder except payment files
- Path fixed for new paymill folder
- Removed redundant Bridge URL and API Url options
- Updated abstract paymill class, to work with new lib
- Reworked the Checkoutprocess
- Fixed a bug regarding the total Amount

##1.0.3
- Fixed a bug regarding 3-D Secure
- Added missing shipping tax to token amount

##1.0.2
- Cleaned up file structure.

##1.0.1
- Initial release