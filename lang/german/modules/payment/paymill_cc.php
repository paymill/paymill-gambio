<?php
define('TEXT_INFO_API_VERSION', 'API Version');
define('MODULE_PAYMENT_PAYMILL_CC_ACCEPTED_CARDS', 'Akzeptierte Kreditkarten');
define('MODULE_PAYMENT_PAYMILL_CC_STATUS_TITLE', 'Kreditkartenmodul aktivieren');
define('MODULE_PAYMENT_PAYMILL_CC_STATUS_DESC', '');
define('MODULE_PAYMENT_PAYMILL_CC_FASTCHECKOUT_TITLE', 'Fast Checkout');
define('MODULE_PAYMENT_PAYMILL_CC_FASTCHECKOUT_DESC', '');
define('MODULE_PAYMENT_PAYMILL_CC_LABEL_TITLE', 'Zeige Paymill Label');
define('MODULE_PAYMENT_PAYMILL_CC_LABEL_DESC', '');
define('MODULE_PAYMENT_PAYMILL_CC_SORT_ORDER_TITLE', 'Anzeigereihenfolge');
define('MODULE_PAYMENT_PAYMILL_CC_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');
define('MODULE_PAYMENT_PAYMILL_CC_PRIVATEKEY_TITLE', 'Geheimer API Key');
define('MODULE_PAYMENT_PAYMILL_CC_PRIVATEKEY_DESC', '');
define('MODULE_PAYMENT_PAYMILL_CC_PUBLICKEY_TITLE', '&Ouml;ffentlicher API Key');
define('MODULE_PAYMENT_PAYMILL_CC_PUBLICKEY_DESC', '');
define('MODULE_PAYMENT_PAYMILL_CC_TRANSACTION_ORDER_STATUS_ID_TITLE', 'Transaction Order Status');
define('MODULE_PAYMENT_PAYMILL_CC_TRANSACTION_ORDER_STATUS_ID_DESC', '');
define('MODULE_PAYMENT_PAYMILL_CC_LOGGING_TITLE', 'Logging aktivieren');
define('MODULE_PAYMENT_PAYMILL_CC_LOGGING_DESC', '');
define('MODULE_PAYMENT_PAYMILL_CC_ORDER_STATUS_ID_TITLE', 'Bestellstatus');
define('MODULE_PAYMENT_PAYMILL_CC_ORDER_STATUS_ID_DESC', 'Setzt den Bestellstatus f&uuml;r erfolgreiche Zahlungen');
define('MODULE_PAYMENT_PAYMILL_CC_ZONE_TITLE', 'Erlaubt f&uuml;r Steuer Zonen');
define('MODULE_PAYMENT_PAYMILL_CC_ZONE_DESC', 'F&uuml;r alle Steuer Zonen leer lassen');
define('MODULE_PAYMENT_PAYMILL_CC_ALLOWED_TITLE' , 'Erlaubt f&uuml;r Zonen');
define('MODULE_PAYMENT_PAYMILL_CC_ALLOWED_DESC' , 'F&uuml;r alle Zonen leer lassen');
define('MODULE_PAYMENT_PAYMILL_CC_TRANS_ORDER_STATUS_ID_TITLE', 'API Ergebnisse');
define('MODULE_PAYMENT_PAYMILL_CC_TRANS_ORDER_STATUS_ID_DESC', 'API Ergebnisse in diesem Bestellstatus f&uuml;r Bestellungen speichern.');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_TITLE','Paymill Kreditkartenzahlung');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_PUBLIC_TITLE','Kreditkartenzahlung');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_OWNER','Kreditkarteninhaber');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_NUMBER','Kreditkarten-Nummer');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_EXPIRY','G&uuml;ltigkeitsdatum');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_CVC','CVC-Code');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_SAVED','Sichere Kreditkartenzahlung powered by');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_EXPIRY_INVALID','Das Gültigkeitsdatum ihrer Kreditkarte ist ungültig. Bitte korrigieren Sie Ihre Angaben.');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_CARDNUMBER_INVALID','Die Kreditkarten-Nummer, die Sie angegeben haben, ist ungültig. Bitte korrigieren Sie Ihre Angaben.');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_CVC_INVALID','Das Formularfeld CVC ist ein Pflichtfeld.');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_OWNER_INVALID','Das Formularfeld Kreditkarteninhaber ist ein Pflichtfeld.');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_JANUARY','Januar');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_FEBRUARY','Februar');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_MARCH','M&auml;rz');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_APRIL','April');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_MAY','Mai');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_JUNE','Juni');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_JULY','Juli');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_AUGUST','August');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_SEPTEMBER','September');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_OCTOBER','Oktober');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_NOVEMBER','November');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_DECEMBER','Dezember');
define('MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_CVC_TOOLTIP', 'Hinter dem CVV-Code bzw. CVC verbirgt sich ein Sicherheitsmerkmal von Kreditkarten, üblicherweise handelt es sich dabei um eine drei- bis vierstelligen Nummer. Der CVV-Code befindet sich auf VISA-Kreditkarten. Der gleiche Code ist auch auf MasterCard-Kreditkarten zu finden, hier allerdings unter dem Namen CVC. Die Abkürzung CVC steht dabei für Card Validation Code. Bei VISA wird der Code als Card Verification Value-Code bezeichnet. Ähnlich wie bei Mastercard und VISA gibt es auch bei Diners Club, Discover und JCB eine dreistellige  Nummer, die meist auf der Rückseite der Karte zu finden ist. Bei Maestro-Karten gibt es mit und ohne dreistelligen CVV. Wird eine Maestro-Karte ohne CVV verwendet kann einfach 000 eingetragen werden. American Express verwendet die CID (Card Identification Number). Dabei handelt es sich um eine vierstellige Nummer, die meist auf der Vorderseite der Karte, rechts oberhalb der Kartennummer zu finden ist.');
define('PAYMILL_10001', 'Genereller Fehler bitte wenden Sie sich an den Support.');
define('PAYMILL_10002', 'Wir warten noch immer auf etwas.');
define('PAYMILL_20000', 'General success response.');
define('PAYMILL_40000', 'Generelles Problem mit den Daten.');
define('PAYMILL_40001', 'Es gibt ein Problem mit den Payment Daten.');
define('PAYMILL_40100', 'Es existieren Probleme mit der Kreditkarte. Nähere Details können nicht übergeben werden.');
define('PAYMILL_40101', 'Der CVV ist nicht korrekt.');
define('PAYMILL_40102', 'Die Kreditkarte ist abgelaufen oder noch gültig.');
define('PAYMILL_40103', 'Das Umsatzimit der Kreditkarte wurde mit dieser Transaktion überschritten oder ist bereits überschritten.');
define('PAYMILL_40104', 'Die Kreditkarte ist ungültig');
define('PAYMILL_40105', 'Das Kreditkartenablaufdatum ist nicht korrekt.');
define('PAYMILL_40106', 'Kreditkarten-Anbieter ist erforderlich.');
define('PAYMILL_40200', 'Probleme mit den Konto Daten.');
define('PAYMILL_40201', 'Daten stimmen nicht mit dem Bank-Account überein.');
define('PAYMILL_40202', 'Die Benutzer-Authentifizierung ist fehlgeschlagen.');
define('PAYMILL_40300', 'Es gibt es Problem mit den 3DSecure Daten.');
define('PAYMILL_40301', 'Währung oder Betrag stimmen nicht überein.');
define('PAYMILL_40400', 'Es gibt ein Problem mit den Eingabe Daten.');
define('PAYMILL_40401', 'Der Betrag ist zu niedrig oder null.');
define('PAYMILL_40402', 'Der Verwendungszweck ist zu lang.');
define('PAYMILL_40403', 'Die Währung ist nicht für den Kunden konfigurierten.');
define('PAYMILL_50000', 'Generelles Problem mit dem Backend.');
define('PAYMILL_50001', 'Die Kreditkarte ist auf einer Schwarzen Liste.');
define('PAYMILL_50100', 'Technisches Problem mit der Kreditkarte.');
define('PAYMILL_50101', 'Limit überschritten.');
define('PAYMILL_50102', 'Diese Karte wurde ohne weitere Gründe abgelehnt.');
define('PAYMILL_50103', 'Diese Karte wurde wegen Kartenmanipulationen abgelehnt.');
define('PAYMILL_50104', 'Die Transaktion wurde vom Authorisierungs-System abgelehnt (Karte durch Bank eingeschränkt).');
define('PAYMILL_50105', 'Die Konfiguration ist ungültig.');
define('PAYMILL_50200', 'Technischer Fehler mit dem Bankkonto.');
define('PAYMILL_50201', 'Dieser Kundenaccount ist auf einer Schwarzen Liste.');
define('PAYMILL_50300', 'Es gibt einen teschnischen Fehler mit 3-D Secure.');
define('PAYMILL_50400', 'Ablehnung aufgrund von Risiko Problemen.');
define('PAYMILL_50500', 'Generelle Zeitüberschreitung.');
define('PAYMILL_50501', 'Die Schnittstelle zum Acquirer reagiert nicht daher bekommen wir keine Antwort ob die Transaktion erfolgreich durchgelaufen ist.');
define('PAYMILL_50502', 'Es gibt eine Zeitüberschreitung bei der Risiko-Management Transaktion.');
define('PAYMILL_50600', 'Doppelte Transaktion.');
?>
