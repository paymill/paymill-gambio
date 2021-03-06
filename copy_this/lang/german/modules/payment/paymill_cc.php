<?php
define("MODULE_PAYMENT_PAYMILL_CC_STATUS_TITLE", "Aktivieren");
define("MODULE_PAYMENT_PAYMILL_CC_FASTCHECKOUT_TITLE", "Fast Checkout erlauben");
define("MODULE_PAYMENT_PAYMILL_CC_FASTCHECKOUT_DESC", "Sofern Aktiviert, werden die Daten Ihrer Kunden f&uuml;r k&uuml;nftige K&auml;ufe von PAYMILL gespeichert und erneut zur Verf&uuml;gung gestellt. Der Kunde muss seine Daten nur 1 mal eintragen. Diese L&ouml;sung ist PCI Konform.");
define("MODULE_PAYMENT_PAYMILL_CC_SORT_ORDER_TITLE", "Reihenfolge");
define("MODULE_PAYMENT_PAYMILL_CC_SORT_ORDER_DESC", "Anzeigeposition im Checkout");
define("MODULE_PAYMENT_PAYMILL_CC_PRIVATEKEY_TITLE", "Private Key");
define("MODULE_PAYMENT_PAYMILL_CC_PRIVATEKEY_DESC", "Ihren Private Key k&ouml;nnen Sie dem PAYMILL Cockpit entnehmen.");
define("MODULE_PAYMENT_PAYMILL_CC_PUBLICKEY_TITLE", "Public Key");
define("MODULE_PAYMENT_PAYMILL_CC_PUBLICKEY_DESC", "Ihren Public Key k&ouml;nnen Sie dem PAYMILL Cockpit entnehmen.");
define("MODULE_PAYMENT_PAYMILL_CC_TRANSACTION_ORDER_STATUS_ID_TITLE", "API Ergebnisse");
define("MODULE_PAYMENT_PAYMILL_CC_TRANSACTION_ORDER_STATUS_ID_DESC", "API Ergebnisse in diesem Bestellstatus f&uuml;r Bestellungen speichern.");
define("MODULE_PAYMENT_PAYMILL_CC_LOGGING_TITLE", "Logging aktivieren");
define("MODULE_PAYMENT_PAYMILL_CC_LOGGING_DESC", "Sofern Aktiviert, werden Informationen &uuml;ber den Ablauf der Bestellungensabwicklung ins Log geschrieben.");
define("MODULE_PAYMENT_PAYMILL_CC_ORDER_STATUS_ID_TITLE", "Bestellungstatus");
define("MODULE_PAYMENT_PAYMILL_CC_TRANS_ORDER_STATUS_ID_TITLE", "API Ergebnisse");
define("MODULE_PAYMENT_PAYMILL_CC_TRANS_ORDER_STATUS_ID_DESC", "API Ergebnisse in diesem Bestellstatus f&uuml;r Bestellungen speichern.");
define("MODULE_PAYMENT_PAYMILL_CC_ALLOWED_TITLE", "Erlaubte L&auml;nder");
define("MODULE_PAYMENT_PAYMILL_CC_ALLOWED_DESC", "Wenn keine Auswahl getroffen wurde, werden alle L&auml;nder erlaubt.");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_PUBLIC_TITLE", "Kreditkarte");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_OWNER", "Karteninhaber");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_NUMBER", "Kartennummer");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_EXPIRY", "G&uuml;ltig bis");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_CVC", "CVC");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_CVC_TOOLTIP", "Hinter dem CVV-Code bzw. CVC verbirgt sich ein Sicherheitsmerkmal von Kreditkarten, &uuml;blicherweise handelt es sich dabei um eine drei- bis vierstelligen Nummer. Der CVV-Code befindet sich auf VISA-Kreditkarten. Der gleiche Code ist auch auf MasterCard-Kreditkarten zu finden, hier allerdings unter dem Namen CVC. Die Abk&uuml;rzung CVC steht dabei f&uuml;r Card Validation Code. Bei VISA wird der Code als Card Verification Value-Code bezeichnet. &Auml;hnlich wie bei Mastercard und VISA gibt es auch bei Diners Club, Discover und JCB eine dreistellige Nummer, die meist auf der R&uuml;ckseite der Karte zu finden ist. Bei Maestro-Karten gibt es mit und ohne dreistelligen CVV. Wird eine Maestro-Karte ohne CVV verwendet kann einfach 000 eingetragen werden. American Express verwendet die CID (Card Identification Number). Dabei handelt es sich um eine vierstellige Nummer, die meist auf der Vorderseite der Karte, rechts oberhalb der Kartennummer zu finden ist.");
define('MODULE_PAYMENT_PAYMILL_CC_AMEX_TITLE', 'American Express');
define('MODULE_PAYMENT_PAYMILL_CC_AMEX_DESC', '');
define('MODULE_PAYMENT_PAYMILL_CC_VISA_TITLE', 'Visa');
define('MODULE_PAYMENT_PAYMILL_CC_VISA_DESC', '');
define('MODULE_PAYMENT_PAYMILL_CC_UNIONPAY_TITLE', 'Unionpay');
define('MODULE_PAYMENT_PAYMILL_CC_UNIONPAY_DESC', '');
define('MODULE_PAYMENT_PAYMILL_CC_MASTERCARD_TITLE', 'Mastercard');
define('MODULE_PAYMENT_PAYMILL_CC_MASTERCARD_DESC', '');
define('MODULE_PAYMENT_PAYMILL_CC_MAESTRO_TITLE', 'Maestro');
define('MODULE_PAYMENT_PAYMILL_CC_MAESTRO_DESC', '');
define('MODULE_PAYMENT_PAYMILL_CC_JCB_TITLE', 'JCB');
define('MODULE_PAYMENT_PAYMILL_CC_JCB_DESC', '');
define('MODULE_PAYMENT_PAYMILL_CC_DISCOVER_TITLE', 'Discover');
define('MODULE_PAYMENT_PAYMILL_CC_DISCOVER_DESC', '');
define('MODULE_PAYMENT_PAYMILL_CC_DINERSCLUB_TITLE', 'Dinersclub');
define('MODULE_PAYMENT_PAYMILL_CC_DINERSCLUB_DESC', '');
define('MODULE_PAYMENT_PAYMILL_CC_CARTEBLEUE_TITLE', 'Carte Bleue');
define('MODULE_PAYMENT_PAYMILL_CC_CARTEBLEUE_DESC', '');
define('MODULE_PAYMENT_PAYMILL_CC_DANKORT_TITLE', 'Dankort');
define('MODULE_PAYMENT_PAYMILL_CC_DANKORT_DESC', '');
define('MODULE_PAYMENT_PAYMILL_CC_CARTASI_TITLE', 'Carta Si');
define('MODULE_PAYMENT_PAYMILL_CC_CARTASI_DESC', '');
define("PAYMILL_10001", "General undefined response.");
define("PAYMILL_10002", "Still waiting on something.");
define("PAYMILL_20000", "General success response.");
define("PAYMILL_40000", "General problem with data.");
define("PAYMILL_40001", "General problem with payment data.");
define("PAYMILL_40100", "Problem with credit card data.");
define("PAYMILL_40101", "Problem with cvv.");
define("PAYMILL_40102", "Card expired or not yet valid.");
define("PAYMILL_40103", "Limit exceeded.");
define("PAYMILL_40104", "Card invalid.");
define("PAYMILL_40105", "Expiry date not valid.");
define("PAYMILL_40106", "Credit card brand required.");
define("PAYMILL_40200", "Problem with bank account data.");
define("PAYMILL_40201", "Bank account data combination mismatch.");
define("PAYMILL_40202", "User authentication failed.");
define("PAYMILL_40300", "Problem with 3d secure data.");
define("PAYMILL_40301", "Currency / amount mismatch");
define("PAYMILL_40400", "Problem with input data.");
define("PAYMILL_40401", "Amount too low or zero.");
define("PAYMILL_40402", "Usage field too long.");
define("PAYMILL_40403", "Currency not allowed.");
define("PAYMILL_50000", "General problem with backend.");
define("PAYMILL_50001", "Country blacklisted.");
define("PAYMILL_50100", "Technical error with credit card.");
define("PAYMILL_50101", "Error limit exceeded.");
define("PAYMILL_50102", "Card declined by authorization system.");
define("PAYMILL_50103", "Manipulation or stolen card.");
define("PAYMILL_50104", "Card restricted");
define("PAYMILL_50105", "Invalid card configuration data.");
define("PAYMILL_50200", "Technical error with bank account.");
define("PAYMILL_50201", "Card blacklisted.");
define("PAYMILL_50300", "Technical error with 3D secure.");
define("PAYMILL_50400", "Decline because of risk issues.");
define("PAYMILL_50500", "General timeout.");
define("PAYMILL_50501", "Timeout on side of the acquirer.");
define("PAYMILL_50502", "Risk management transaction timeout");
define("PAYMILL_50600", "Duplicate transaction.");
define("PAYMILL_FIELD_INVALID_CARD_NUMBER", "Bitte geben Sie eine g&uuml;ltige Kreditkartennummer ein.");
define("PAYMILL_FIELD_INVALID_CARD_EXP", "Ung&uuml;ltiges Ablaufdatum");
define("PAYMILL_FIELD_INVALID_CARD_CVC", "Ung&uuml;ltige CVC");
define("PAYMILL_FIELD_INVALID_CARD_HOLDER", "Bitte geben Sie einen Inhabernamen an.");
define("PAYMILL_INTERNAL_SERVER_ERROR", "The communication with the psp failed.");
define("PAYMILL_INVALID_PUBLIC_KEY", "The public key is invalid.");
define("PAYMILL_INVALID_PAYMENT_DATA", "Paymentmethod, card type currency or country not authorized");
define("PAYMILL_UNKNOWN_ERROR", "Unknown Error");
define("PAYMILL_3DS_CANCELLED", "3-D Secure process has been canceled by the user");
define("PAYMILL_FIELD_INVALID_CARD_EXP_YEAR", "Invalid Expiry Year");
define("PAYMILL_FIELD_INVALID_CARD_EXP_MONTH", "Invalid Expiry Month");
define("PAYMILL_FIELD_INVALID_AMOUNT_INT", "Missing amount for 3-D Secure");
define("PAYMILL_FIELD_INVALID_AMOUNT", "Missing amount for 3-D Secure");
define("PAYMILL_FIELD_INVALID_CURRENCY", "Invalid currency for 3-D Secure");
define("MODULE_PAYMENT_PAYMILL_CC_WEBHOOKS_TITLE", "Webhooks aktivieren");
define("MODULE_PAYMENT_PAYMILL_CC_WEBHOOKS_DESC", "Gutschriften aus dem Paymill Cockpit automatisch mit meinem Shop synchronisieren");
define("MODULE_PAYMENT_PAYMILL_CC_WEBHOOKS_LINK_CREATE", "Webhooks anlegen");
define("MODULE_PAYMENT_PAYMILL_CC_WEBHOOKS_LINK_REMOVE", "Webhooks entfernen");
define("MODULE_PAYMENT_PAYMILL_CC_WEBHOOKS_LINK", "Webhooks anlegen");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_JANUARY", "Januar");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_FEBRUARY", "Februar");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_MARCH", "M&auml;rz");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_APRIL", "April");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_MAY", "Mai");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_JUNE", "Juni");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_JULY", "Juli");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_AUGUST", "August");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_SEPTEMBER", "September");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_OCTOBER", "Oktober");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_NOVEMBER", "November");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_DECEMBER", "Dezember");
define("MODULE_PAYMENT_PAYMILL_CC_ZONE_TITLE", "Erlaubte Steuerzonen");
define("MODULE_PAYMENT_PAYMILL_CC_ZONE_DESC", "Bitte geben Sie die Zonen einzeln an und trennen Sie diese durch Kommas (z.B. US, UK (Lassen Sie das Feld leer um alle Zonen zu erlauben.))");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_EXPIRY_INVALID", "Ung&uuml;ltiges Ablaufdatum");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_CARDNUMBER_INVALID", "Bitte geben Sie eine g&uuml;ltige Kreditkartennummer ein.");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_CVC_INVALID", "Ung&uuml;ltige CVC");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_OWNER_INVALID", "Bitte geben Sie einen Inhabernamen an.");
define("PAYMILL_0", "W&auml;hrend Ihrer Zahlung ist ein Fehler aufgetreten.");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_TITLE", "PAYMILL Kreditkarte");
define("TEXT_INFO_API_VERSION", "API Version");
define("MODULE_PAYMENT_PAYMILL_CC_STATUS_DESC", "");
define("MODULE_PAYMENT_PAYMILL_CC_ORDER_STATUS_ID_DESC", "");
define("MODULE_PAYMENT_PAYMILL_CC_ACCEPTED_CARDS", "Accepted Credit Cards");
define('PAYMILL_REFUND_BUTTON_TEXT', 'Bestellung erstatten');
define('PAYMILL_REFUND_SUCCESS', 'Bestellung erfolgreich erstattet.');
define('PAYMILL_REFUND_ERROR', 'Bestellung nicht erfolgreich erstattet.');
?>