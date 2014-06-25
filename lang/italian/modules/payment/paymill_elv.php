<?php
define("MODULE_PAYMENT_PAYMILL_ELV_TEXT_PUBLIC_TITLE", "Addebito diretto");
define("MODULE_PAYMENT_PAYMILL_ELV_STATUS_TITLE", "Attivare");
define("MODULE_PAYMENT_PAYMILL_ELV_TRANSACTION_ORDER_STATUS_ID_TITLE", "Stato dell'ordine della transazione");
define("MODULE_PAYMENT_PAYMILL_ELV_TRANSACTION_ORDER_STATUS_ID_DESC", "Inserire le informazioni di transazione in questo livello di stato dell'ordine.");
define("MODULE_PAYMENT_PAYMILL_ELV_FASTCHECKOUT_TITLE", "Abilitare pagamento veloce");
define("MODULE_PAYMENT_PAYMILL_ELV_FASTCHECKOUT_DESC", "Abilitando la funzione, i dati dei suoi clienti saranno archiviati da PAYMILL e resi nuovamente disponibili per futuri acquisti. Il cliente dovr&agrave; inserire i propri dati una sola volta. Questa soluzione &egrave; conforme agli standard PCI.");
define("MODULE_PAYMENT_PAYMILL_ELV_WEBHOOKS_TITLE", "Abilita webhook");
define("MODULE_PAYMENT_PAYMILL_ELV_WEBHOOKS_DESC", "Sincronizza automaticamente i miei Rimborsi dal cockpit di PAYMILL con il mio negozio");
define("MODULE_PAYMENT_PAYMILL_ELV_WEBHOOKS_LINK_CREATE", "Crea webhook");
define("MODULE_PAYMENT_PAYMILL_ELV_WEBHOOKS_LINK_REMOVE", "Rimuovi webhook");
define("MODULE_PAYMENT_PAYMILL_ELV_SORT_ORDER_TITLE", "Sequenza");
define("MODULE_PAYMENT_PAYMILL_ELV_SORT_ORDER_DESC", "Posizione di visualizzazione durante il pagamento.");
define("MODULE_PAYMENT_PAYMILL_ELV_PRIVATEKEY_TITLE", "Chiave privata");
define("MODULE_PAYMENT_PAYMILL_ELV_PRIVATEKEY_DESC", "Pu&ograve; trovare la sua chiave privata nel pannello di controllo di PAYMILL.");
define("MODULE_PAYMENT_PAYMILL_ELV_PUBLICKEY_TITLE", "Chiave pubblica");
define("MODULE_PAYMENT_PAYMILL_ELV_PUBLICKEY_DESC", "Pu&ograve; trovare la sua chiave pubblica nel pannello di controllo di PAYMILL.");
define("MODULE_PAYMENT_PAYMILL_ELV_LOGGING_TITLE", "Attivare la registrazione.");
define("MODULE_PAYMENT_PAYMILL_ELV_LOGGING_DESC", "Abilitando la funzione, le informazioni riguardanti lo stato di avanzamento dell'elaborazione dell'ordine verranno scritte nel registro.");
define("MODULE_PAYMENT_PAYMILL_ELV_ORDER_STATUS_ID_TITLE", "Stato dell'ordine della transazione");
define("MODULE_PAYMENT_PAYMILL_ELV_ORDER_STATUS_ID_DESC", "Inserire le informazioni di transazione in questo livello di stato dell'ordine.");
define("MODULE_PAYMENT_PAYMILL_ELV_ZONE_TITLE", "Zone supportate");
define("MODULE_PAYMENT_PAYMILL_ELV_ZONE_DESC", "Inserire individualmente le zone supportate per l'utilizzo di questo modulo (ad es. USA, UK (lasciare lo spazio vuoto per inserire tutte le zone))");
define("MODULE_PAYMENT_PAYMILL_ELV_ALLOWED_TITLE", "Paesi accettati");
define("MODULE_PAYMENT_PAYMILL_ELV_ALLOWED_DESC", "Se non &egrave; stato selezionato nulla, saranno accettati tutti i paesi");
define("MODULE_PAYMENT_PAYMILL_ELV_TRANS_ORDER_STATUS_ID_TITLE", "Stato dell'ordine della transazione");
define("MODULE_PAYMENT_PAYMILL_ELV_TRANS_ORDER_STATUS_ID_DESC", "Inserire le informazioni di transazione in questo livello di stato dell'ordine.");
define("MODULE_PAYMENT_PAYMILL_ELV_TEXT_ACCOUNT", "Numero conto");
define("MODULE_PAYMENT_PAYMILL_ELV_TEXT_BANKCODE", "Codice bancario");
define("MODULE_PAYMENT_PAYMILL_ELV_TEXT_ACCOUNT_HOLDER", "Titolare conto");
define("MODULE_PAYMENT_PAYMILL_ELV_TEXT_ACCOUNT_HOLDER_INVALID", "Inserire il nome del titolare del conto di addebito diretto");
define("MODULE_PAYMENT_PAYMILL_ELV_TEXT_ACCOUNT_INVALID", "Inserire un numero di conto di addebito diretto valido");
define("MODULE_PAYMENT_PAYMILL_ELV_TEXT_BANKCODE_INVALID", "Inserire un codice di addebito bancario diretto valido.");
define("MODULE_PAYMENT_PAYMILL_ELV_SEPA_TITLE", "Mostra SEPA da");
define("MODULE_PAYMENT_PAYMILL_ELV_SEPA_DESC", "Attualmente sono supportati solamente i dati bancari provenienti dalla Germania");
define("MODULE_PAYMENT_PAYMILL_ELV_TEXT_BIC", "BIC");
define("MODULE_PAYMENT_PAYMILL_ELV_TEXT_IBAN", "IBAN");
define("MODULE_PAYMENT_PAYMILL_ELV_TEXT_IBAN_INVALID", "Inserire un numero iban valido");
define("MODULE_PAYMENT_PAYMILL_ELV_TEXT_BIC_INVALID", "Inserire un codice bic valido.");
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
define("PAYMILL_INTERNAL_SERVER_ERROR", "The communication with the psp failed.");
define("PAYMILL_INVALID_PUBLIC_KEY", "The public key is invalid.");
define("PAYMILL_INVALID_PAYMENT_DATA", "Paymentmethod, card type currency or country not authorized");
define("PAYMILL_UNKNOWN_ERROR", "Unknown Error");
define("PAYMILL_FIELD_INVALID_AMOUNT_INT", "Missing amount for 3-D Secure");
define("PAYMILL_FIELD_INVALID_AMOUNT", "Missing amount for 3-D Secure");
define("PAYMILL_FIELD_INVALID_CURRENCY", "Invalid currency for 3-D Secure");
define("PAYMILL_FIELD_INVALID_ACCOUNT_NUMBER", "Invalid Account Number");
define("PAYMILL_FIELD_INVALID_ACCOUNT_HOLDER", "Invalid Account Holder");
define("PAYMILL_FIELD_INVALID_BANK_CODE", "Invalid bank code");
define("PAYMILL_FIELD_INVALID_IBAN", "Invalid IBAN");
define("PAYMILL_FIELD_INVALID_BIC", "Invalid BIC");
define("PAYMILL_FIELD_INVALID_COUNTRY", "Invalid country for sepa transactions");
define("PAYMILL_FIELD_INVALID_BANK_DATA", "Invalid bank data");
define("PAYMILL_0", "Si &egrave; verificato un errore durante l'elaborazione del pagamento.");
define("MODULE_PAYMENT_PAYMILL_ELV_TEXT_TITLE", "PAYMILL Addebito diretto");
define("TEXT_INFO_API_VERSION", "API Version");
define("MODULE_PAYMENT_PAYMILL_ELV_STATUS_DESC", "");
define("SEPA_DRAWN_TEXT", "The direct debit is drawn to the following date: ");
define("MODULE_PAYMENT_PAYMILL_ELV_PRENOTIFICATION_DAYS_TITLE", "Days until the debit");
define("MODULE_PAYMENT_PAYMILL_ELV_PRENOTIFICATION_DAYS_DESC", "");
?>