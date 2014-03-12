<?php
define("MODULE_PAYMENT_PAYMILL_CC_STATUS_TITLE", "Activar");
define("MODULE_PAYMENT_PAYMILL_CC_FASTCHECKOUT_TITLE", "Activar la compra r&aacute;pida");
define("MODULE_PAYMENT_PAYMILL_CC_FASTCHECKOUT_DESC", "Si est&aacute; activado, los datos de tus clientes ser&aacute;n almacenados por PAYMILL y estar&aacute;n disponibles de nuevo para futuras compras. El cliente simplemente tendr&aacute; que introducir sus datos una vez. Esta soluci&oacute;n cumple con la normativa del PCI.");
define("MODULE_PAYMENT_PAYMILL_CC_SORT_ORDER_TITLE", "Secuencia");
define("MODULE_PAYMENT_PAYMILL_CC_SORT_ORDER_DESC", "Posici&oacute;n durante el proceso de compra.");
define("MODULE_PAYMENT_PAYMILL_CC_PRIVATEKEY_TITLE", "Clave privada");
define("MODULE_PAYMENT_PAYMILL_CC_PRIVATEKEY_DESC", "Puedes encontrar tu clave privada en el Cockpit de PAYMILL");
define("MODULE_PAYMENT_PAYMILL_CC_PUBLICKEY_TITLE", "Clave p&uacute;blica");
define("MODULE_PAYMENT_PAYMILL_CC_PUBLICKEY_DESC", "Puede encontrar su clave p&uacute;blica en el Cockpit de PAYMILL");
define("MODULE_PAYMENT_PAYMILL_CC_TRANSACTION_ORDER_STATUS_ID_TITLE", "Estado de la Transacci&oacute;n");
define("MODULE_PAYMENT_PAYMILL_CC_TRANSACTION_ORDER_STATUS_ID_DESC", "Incluya informaci&oacute;n de la transacci&oacute;n en este nivel de estado del pedido");
define("MODULE_PAYMENT_PAYMILL_CC_LOGGING_TITLE", "Activar el registro");
define("MODULE_PAYMENT_PAYMILL_CC_LOGGING_DESC", "Si est&aacute; activado, la informaci&oacute;n en relaci&oacute;n con el progreso del proceso del pedido se escribir&aacute; en el registro.");
define("MODULE_PAYMENT_PAYMILL_CC_ORDER_STATUS_ID_TITLE", "Estado del pedido");
define("MODULE_PAYMENT_PAYMILL_CC_TRANS_ORDER_STATUS_ID_TITLE", "Estado de la Transacci&oacute;n");
define("MODULE_PAYMENT_PAYMILL_CC_TRANS_ORDER_STATUS_ID_DESC", "Incluya informaci&oacute;n de la transacci&oacute;n en este nivel de estado del pedido");
define("MODULE_PAYMENT_PAYMILL_CC_ALLOWED_TITLE", "Pa&iacute;ses aceptados");
define("MODULE_PAYMENT_PAYMILL_CC_ALLOWED_DESC", "Si no se ha seleccionado nada, se aceptan todos los pa&iacute;ses.");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_PUBLIC_TITLE", "Tarjeta de cr&eacute;dito");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_OWNER", "Titular de la tarjeta");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_NUMBER", "N&uacute;mero de tarjeta");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_EXPIRY", "V&aacute;lida hasta");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_CVC", "C&oacute;digo CVC");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_CVC_TOOLTIP", "El c&oacute;digo CVV o CVC es una medida de seguridad de las tarjetas de cr&eacute;dito. Normalmente es un n&uacute;mero de tres a cuatro d&iacute;gitos de longitud. En las tarjetas de cr&eacute;dito VISA, se le llama c&oacute;digo CVV. Se puede encontrar este mismo c&oacute;digo en las tarjetas de cr&eacute;dito MasterCard -donde se le llama CVC. CVC es la abreviatura de &quot;c&oacute;digo de validez de la tarjeta&quot;. El c&oacute;digo CVV, por otro lado, es la abreviatura de &quot;c&oacute;digo de valor de verificaci&oacute;n de la tarjeta&quot;. Parecidas a MasterCard y Visa, otras tarjetas como Diners Club, Discover y JCB contienen un n&uacute;mero de tres d&iacute;gitos que se encuentra normalmente en el reverso de la tarjeta de cr&eacute;dito. Las tarjetas MAESTRO pueden tener o no el c&oacute;digo CVV de tres d&iacute;gitos. En caso de usar una tarjeta MAESTRO sin CVV, puede introducir 000 en el formulario en su lugar. American Express usa el CID (n&uacute;mero de identificaci&oacute;n de la tarjeta). El CID es un n&uacute;mero de cuatro d&iacute;gitos que normalmente se encuentra en el anverso de la tarjeta, arriba a la derecha del n&uacute;mero de la tarjeta de cr&eacute;dito.");
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
define("PAYMILL_FIELD_INVALID_CARD_NUMBER", "Por favor, introduce un n&uacute;mero v&aacute;lido de tarjeta de cr&eacute;dito.");
define("PAYMILL_FIELD_INVALID_CARD_EXP", "Fecha de expiraci&oacute;n inv&aacute;lida");
define("PAYMILL_FIELD_INVALID_CARD_CVC", "CVC inv&aacute;lido");
define("PAYMILL_FIELD_INVALID_CARD_HOLDER", "Por favor, introduce el nombre del titular de la tarjeta");
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
define("MODULE_PAYMENT_PAYMILL_CC_WEBHOOKS_TITLE", "Habilitar Webhooks");
define("MODULE_PAYMENT_PAYMILL_CC_WEBHOOKS_DESC", "Sincronizar autom&aacute;ticamente mis reembolsos con mi tienda desde la Cabina PAYMILL");
define("MODULE_PAYMENT_PAYMILL_CC_WEBHOOKS_LINK_CREATE", "Crear Webhooks");
define("MODULE_PAYMENT_PAYMILL_CC_WEBHOOKS_LINK_REMOVE", "Eliminar Webhooks");
define("MODULE_PAYMENT_PAYMILL_CC_WEBHOOKS_LINK", "Crear Webhooks");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_JANUARY", "enero");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_FEBRUARY", "febrero");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_MARCH", "marzo");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_APRIL", "abril");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_MAY", "mayo");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_JUNE", "junio");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_JULY", "julio");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_AUGUST", "agosto");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_SEPTEMBER", "septiembre");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_OCTOBER", "octubre");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_NOVEMBER", "noviembre");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_DECEMBER", "diciembre");
define("MODULE_PAYMENT_PAYMILL_CC_ZONE_TITLE", "Zonas Permitidas");
define("MODULE_PAYMENT_PAYMILL_CC_ZONE_DESC", "Por favor, introduzca individualmente las zonas a las que se deber&iacute;a permitir el uso de este m&oacute;dulo (p.ej. EEUU, Reino Unido (deje en blanco para autorizar a todas las zonas))");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_EXPIRY_INVALID", "Fecha de expiraci&oacute;n inv&aacute;lida");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_CARDNUMBER_INVALID", "Por favor, introduce un n&uacute;mero v&aacute;lido de tarjeta de cr&eacute;dito.");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_CVC_INVALID", "CVC inv&aacute;lido");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_OWNER_INVALID", "Por favor, introduce el nombre del titular de la tarjeta");
define("PAYMILL_0", "Ha ocurrido un error mientras proces&aacute;bamos tu pago.");
define("MODULE_PAYMENT_PAYMILL_CC_TEXT_TITLE", "PAYMILL Tarjeta de cr&eacute;dito");
define("TEXT_INFO_API_VERSION", "API Version");
define("MODULE_PAYMENT_PAYMILL_CC_STATUS_DESC", "");
define("MODULE_PAYMENT_PAYMILL_CC_ORDER_STATUS_ID_DESC", "");
define("MODULE_PAYMENT_PAYMILL_CC_ACCEPTED_CARDS", "Accepted Credit Cards");
?>