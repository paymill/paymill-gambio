#Release Notes
##1.3.1
- Added improved Error Feedback
- Added Changelog

##1.3.0
- Changed the german name of the direct debit payment method to "ELV" from "Elektronisches Lastschriftverfahren"
- Added special handling for maestro credit cards without CVC
- Redesigned Logging
- Payments will no longer be selectable during checkout if there are no Keys available in the Backend
- Fixed muldiple minor bugs

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