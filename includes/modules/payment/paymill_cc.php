<?php
require_once('paymill/paymill_abstract.php');

class paymill_cc extends paymill_abstract
{

    function paymill_cc()
    {
        parent::paymill_abstract();
        $this->fastCheckout = new FastCheckout(trim(MODULE_PAYMENT_PAYMILL_CC_PRIVATEKEY));
        global $order;

        $this->code = 'paymill_cc';
        $this->title = MODULE_PAYMENT_PAYMILL_CC_TEXT_TITLE;
        $this->public_title = MODULE_PAYMENT_PAYMILL_CC_TEXT_PUBLIC_TITLE;

        if (defined('MODULE_PAYMENT_PAYMILL_CC_STATUS')) {
            $this->enabled = ((MODULE_PAYMENT_PAYMILL_CC_STATUS == 'True') ? true : false);
            $this->sort_order = MODULE_PAYMENT_PAYMILL_CC_SORT_ORDER;
            $this->privateKey = trim(MODULE_PAYMENT_PAYMILL_CC_PRIVATEKEY);
            $this->logging = ((MODULE_PAYMENT_PAYMILL_CC_LOGGING == 'True') ? true : false);
            $this->webHooksEnabled = ((MODULE_PAYMENT_PAYMILL_CC_WEBHOOKS == 'True') ? true : false);
            $this->publicKey = MODULE_PAYMENT_PAYMILL_CC_PUBLICKEY;
            $this->form_action_url = 'paymill_confirmation_form';
            $this->fastCheckoutFlag = ((MODULE_PAYMENT_PAYMILL_CC_FASTCHECKOUT == 'True') ? true : false);
            $this->payments = new Services_Paymill_Payments(trim($this->privateKey), $this->apiUrl);
            $this->clients = new Services_Paymill_Clients(trim($this->privateKey), $this->apiUrl);
            if ((int) MODULE_PAYMENT_PAYMILL_CC_ORDER_STATUS_ID > 0) {
                $this->order_status = MODULE_PAYMENT_PAYMILL_CC_ORDER_STATUS_ID;
            }

            if ($this->logging) {
                $this->description .= '<p><a href="' . xtc_href_link('paymill_logging.php') . '">PAYMILL Log</a></p>';
            }

            if ($this->webHooksEnabled) {
                $type = 'CC';
                $this->displayWebhookButton($type);
            }

        }

        if (is_object($order)) $this->update_status();
    }

    function selection()
    {
        $selection = parent::selection();
        $selection['module'] .= '<div>' . $this->getBrandLogos() . '</div>';
        return $selection;
    }

    function getPayment($userId)
    {
        $payment = array(
            'last4' => '',
            'cvc' => '',
            'card_holder' => '',
            'expire_month' => '',
            'expire_year' => '',
            'card_type' => '',
        );

        if ($this->fastCheckout->canCustomerFastCheckoutCc($userId)) {
            $data = $this->fastCheckout->loadFastCheckoutData($userId);
            $payment = $this->payments->getOne($data['paymentID_CC']);
            $payment['last4'] = '************' . $payment['last4'];
            $payment['cvc'] = '***';
        }

        return $payment;
    }
    
    function getBrandLogos()
    {
        $logos = '';
        if (!((MODULE_PAYMENT_PAYMILL_CC_AMEX === 'False') 
            && (MODULE_PAYMENT_PAYMILL_CC_CARTASI === 'False') 
            && (MODULE_PAYMENT_PAYMILL_CC_DANKORT === 'False') 
            && (MODULE_PAYMENT_PAYMILL_CC_CARTEBLEUE === 'False') 
            && (MODULE_PAYMENT_PAYMILL_CC_DISCOVER === 'False') 
            && (MODULE_PAYMENT_PAYMILL_CC_DINERSCLUB === 'False') 
            && (MODULE_PAYMENT_PAYMILL_CC_UNIONPAY === 'False') 
            && (MODULE_PAYMENT_PAYMILL_CC_MAESTRO === 'False') 
            && (MODULE_PAYMENT_PAYMILL_CC_JCB === 'False') 
            && (MODULE_PAYMENT_PAYMILL_CC_MASTERCARD === 'False')
            && (MODULE_PAYMENT_PAYMILL_CC_VISA === 'False'))
        ) {
            if (MODULE_PAYMENT_PAYMILL_CC_AMEX === 'True') {
                $logos .= '<img src="ext/modules/payment/paymill/public/images/32x20_amex.png" alt="American Express" style="margin-right: 1px;"/>';
            }
            
            if (MODULE_PAYMENT_PAYMILL_CC_CARTASI === 'True') {
                $logos .= '<img src="ext/modules/payment/paymill/public/images/32x20_carta-si.png" alt="American Express" style="margin-right: 1px;"/>';
            }
            
            if (MODULE_PAYMENT_PAYMILL_CC_DANKORT === 'True') {
                $logos .= '<img src="ext/modules/payment/paymill/public/images/32x20_dankort.png" alt="American Express" style="margin-right: 1px;"/>';
            }
            
            if (MODULE_PAYMENT_PAYMILL_CC_CARTEBLEUE === 'True') {
                $logos .= '<img src="ext/modules/payment/paymill/public/images/32x20_carte-bleue.png" alt="American Express" style="margin-right: 1px;"/>';
            }
            
            if (MODULE_PAYMENT_PAYMILL_CC_DISCOVER === 'True') {
                $logos .= '<img src="ext/modules/payment/paymill/public/images/32x20_discover.png" alt="American Express" style="margin-right: 1px;"/>';
            }
            
            if (MODULE_PAYMENT_PAYMILL_CC_DINERSCLUB === 'True') {
                $logos .= '<img src="ext/modules/payment/paymill/public/images/32x20_dinersclub.png" alt="American Express" style="margin-right: 1px;"/>';
            }
            
            if (MODULE_PAYMENT_PAYMILL_CC_UNIONPAY === 'True') {
                $logos .= '<img src="ext/modules/payment/paymill/public/images/32x20_unionpay.png" alt="American Express" style="margin-right: 1px;"/>';
            }
            
            if (MODULE_PAYMENT_PAYMILL_CC_MAESTRO === 'True') {
                $logos .= '<img src="ext/modules/payment/paymill/public/images/32x20_maestro.png" alt="American Express" style="margin-right: 1px;"/>';
            }
            
            if (MODULE_PAYMENT_PAYMILL_CC_JCB === 'True') {
                $logos .= '<img src="ext/modules/payment/paymill/public/images/32x20_jcb.png" alt="American Express" style="margin-right: 1px;"/>';
            }
            
            if (MODULE_PAYMENT_PAYMILL_CC_MASTERCARD === 'True') {
                $logos .= '<img src="ext/modules/payment/paymill/public/images/32x20_mastercard.png" alt="American Express" style="margin-right: 1px;"/>';
            }
            
            if (MODULE_PAYMENT_PAYMILL_CC_VISA === 'True') {
                $logos .= '<img src="ext/modules/payment/paymill/public/images/32x20_visa.png" alt="American Express" style="margin-right: 1px;"/>';
            }
            
        }
        
        return $logos;
    }


    function confirmation()
    {
        global $order;

        $confirmation = parent::confirmation();
        array_push($confirmation['fields'], 
            array(
                'title' => '<div style="width: 500px;">' . $this->getBrandLogos() . '</div>',
                'field' => ''
            )
        );

        $months_array     = array();
        $months_array[1]  = array('01', MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_JANUARY);
        $months_array[2]  = array('02', MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_FEBRUARY);
        $months_array[3]  = array('03', MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_MARCH);
        $months_array[4]  = array('04', MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_APRIL);
        $months_array[5]  = array('05', MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_MAY);
        $months_array[6]  = array('06', MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_JUNE);
        $months_array[7]  = array('07', MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_JULY);
        $months_array[8]  = array('08', MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_AUGUST);
        $months_array[9]  = array('09', MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_SEPTEMBER);
        $months_array[10] = array('10', MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_OCTOBER);
        $months_array[11] = array('11', MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_NOVEMBER);
        $months_array[12] = array('12', MODULE_PAYMENT_PAYMILL_CC_TEXT_MONTH_DECEMBER);

        $today = getdate();
        $years_array = array();

        for ($i=$today['year']; $i < $today['year']+10; $i++) {
            $years_array[$i] = array(strftime('%Y', mktime(0, 0, 0, 1 , 1, $i)), strftime('%Y',mktime(0, 0, 0, 1, 1, $i)));
        }


        if($_SESSION['customers_status']['customers_status_id'] === "1"){
            $this->fastCheckout->setFastCheckoutFlag(false);
        } else {
            $this->fastCheckout->setFastCheckoutFlag($this->fastCheckoutFlag);
        }


        $payment = $this->getPayment($_SESSION['customer_id']);

        $script = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>'
                . '<script type="text/javascript">'
                    . 'var cclogging = "' . MODULE_PAYMENT_PAYMILL_CC_LOGGING . '";'
                    . 'var cc_expiery_invalid = "' . html_entity_decode(MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_EXPIRY_INVALID) . '";'
                    . 'var cc_owner_invalid = "' . html_entity_decode(MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_OWNER_INVALID) . '";'
                    . 'var cc_card_number_invalid = "' .html_entity_decode(MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_CARDNUMBER_INVALID) . '";'
                    . 'var cc_cvc_number_invalid = "' . html_entity_decode(MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_CVC_INVALID) . '";'
                    . 'var brand = "' . $payment['card_type'] . '";'
                    . 'var paymill_total = ' . json_encode((int) $_SESSION['paymill']['amount']) . ';'
                    . 'var paymill_currency = ' . json_encode(strtoupper($order->info['currency'])) . ';'
                    . 'var paymill_cc_months = ' . json_encode($months_array) . ';'
                    . 'var paymill_cc_years = ' . json_encode($years_array) . ';'
                    . 'var paymill_cc_number_val = "' . $payment['last4'] . '";'
                    . 'var paymill_cc_cvc_val = "' . $payment['cvc'] . '";'
                    . 'var paymill_cc_card_type = "' . utf8_decode($payment['card_type']) . '";'
                    . 'var paymill_cc_holder_val = "' . utf8_decode($payment['card_holder']) . '";'
                    . 'var paymill_cc_expiry_month_val = "' . $payment['expire_month'] . '";'
                    . 'var paymill_cc_expiry_year_val = "' . $payment['expire_year'] . '";'
                    . 'var paymill_cc_fastcheckout = ' . ($this->fastCheckout->canCustomerFastCheckoutCc($_SESSION['customer_id']) ? 'true' : 'false') . ';'
                    . 'var checkout_payment_link = "' . xtc_href_link(FILENAME_CHECKOUT_PAYMENT, 'step=step2&payment_error=' . $this->code . '&error=', 'SSL', true, false) . '";'
                    . 'var logos =  new Array();'
                    . "logos['amex'] = " . strtolower(MODULE_PAYMENT_PAYMILL_CC_AMEX) . ";"
                    . "logos['carta-si'] = " . strtolower(MODULE_PAYMENT_PAYMILL_CC_CARTASI) . ";"
                    . "logos['dankort'] =  " . strtolower(MODULE_PAYMENT_PAYMILL_CC_DANKORT) . ";"
                    . "logos['carte-bleue'] =  " . strtolower(MODULE_PAYMENT_PAYMILL_CC_CARTEBLEUE) . ";"
                    . "logos['discover'] =  " . strtolower(MODULE_PAYMENT_PAYMILL_CC_DISCOVER) . ";"
                    . "logos['diners-club'] =  " . strtolower(MODULE_PAYMENT_PAYMILL_CC_DINERSCLUB) . ";"
                    . "logos['china-unionpay'] =  " . strtolower(MODULE_PAYMENT_PAYMILL_CC_UNIONPAY) . ";"
                    . "logos['maestro'] =  " . strtolower(MODULE_PAYMENT_PAYMILL_CC_MAESTRO) . ";"
                    . "logos['jcb'] =  " . strtolower(MODULE_PAYMENT_PAYMILL_CC_JCB) . ";"
                    . "logos['mastercard'] =  " . strtolower(MODULE_PAYMENT_PAYMILL_CC_MASTERCARD) . ";"
                    . "logos['visa'] =  " . strtolower(MODULE_PAYMENT_PAYMILL_CC_VISA) . ";"
                    . "var allBrandsDisabled = !logos['amex'] && !logos['carta-si'] && !logos['dankort'] && !logos['carte-bleue'] && !logos['discover'] && !logos['diners-club'] && !logos['china-unionpay'] && !logos['maestro'] && !logos['jcb'] && !logos['mastercard'] && !logos['visa'];"
                . '</script>'
                . '<script type="text/javascript" src="ext/modules/payment/paymill/public/javascript/BrandDetection.js"></script>'
                . '<script type="text/javascript" src="ext/modules/payment/paymill/public/javascript/cc.js"></script>';

        array_push($confirmation['fields'],
            array(
                'field' => $script
            )
        );

        array_push($confirmation['fields'],
            array(
                'title' => '<div class="paymill-label-field">' . MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_OWNER . '</div>',
                'field' => '<span id="card-owner-field"></span><div id="card-owner-error" class="paymill-error"></div>'
            )
        );

        array_push($confirmation['fields'],
            array(
                'title' => '<div class="paymill-label-field">' . MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_NUMBER . '</div>',
                'field' => '<span id="card-number-field"></span><div id="card-number-error" class="paymill-error"></div>'
            )
        );

        array_push($confirmation['fields'],
            array(
                'title' => '<div class="paymill-label-field">' . MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_EXPIRY . '</div>',
                'field' => '<span class="paymill-expiry"><span id="card-expiry-month-field"></span>&nbsp;<span id="card-expiry-year-field"></span></span><div id="card-expiry-error" class="paymill-error"></div>'
            )
        );

        array_push($confirmation['fields'],
            array(
                'title' => '<div class="paymill-label-field">' . MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_CVC . '<span class="tooltip" title="' . MODULE_PAYMENT_PAYMILL_CC_TEXT_CREDITCARD_CVC_TOOLTIP . '">?</span></div>',
                'field' => '<span id="card-cvc-field" class="card-cvc-row"></span><div id="card-cvc-error" class="paymill-error"></div>'
            )
        );

        array_push($confirmation['fields'],
            array(
                'field' => '<form id="paymill_form" action="' . xtc_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL') . '" method="post" style="display: none;"></form>'
            )
        );

        return $confirmation;
    }

    function check()
    {
        if (!isset($this->_check)) {
            $check_query = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_PAYMENT_PAYMILL_CC_STATUS'");
            $this->_check = xtc_db_num_rows($check_query);
        }
        return $this->_check;
    }

    function install()
    {
        parent::install();

        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PAYMILL_CC_STATUS', 'True', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PAYMILL_CC_FASTCHECKOUT', 'False', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PAYMILL_CC_WEBHOOKS', 'False', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_PAYMILL_CC_SORT_ORDER', '0', '6', '0', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_PAYMILL_CC_PRIVATEKEY', '0', '6', '0', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_PAYMILL_CC_PUBLICKEY', '0', '6', '0', now())");

        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PAYMILL_CC_AMEX', 'False', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PAYMILL_CC_VISA', 'False', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PAYMILL_CC_UNIONPAY', 'False', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PAYMILL_CC_MASTERCARD', 'False', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PAYMILL_CC_JCB', 'False', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PAYMILL_CC_DISCOVER', 'False', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PAYMILL_CC_DINERSCLUB', 'False', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PAYMILL_CC_CARTEBLEUE', 'False', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PAYMILL_CC_DANKORT', 'False', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PAYMILL_CC_CARTASI', 'False', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PAYMILL_CC_MAESTRO', 'False', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
        
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) values ('MODULE_PAYMENT_PAYMILL_CC_ORDER_STATUS_ID', '0',  '6', '0', 'xtc_cfg_pull_down_order_statuses(', 'xtc_get_order_status_name', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PAYMILL_CC_LOGGING', 'False', '6', '0', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) values ('MODULE_PAYMENT_PAYMILL_CC_TRANSACTION_ORDER_STATUS_ID', '" . $this->getOrderStatusTransactionID() . "', '6', '0', 'xtc_cfg_pull_down_order_statuses(', 'xtc_get_order_status_name', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_PAYMILL_CC_ALLOWED', '', '6', '0', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) values ('MODULE_PAYMENT_PAYMILL_CC_ZONE', '0', '6', '2', 'xtc_get_zone_class_title', 'xtc_cfg_pull_down_zone_classes(', now())");
    }

    function keys()
    {
        return array(
            'MODULE_PAYMENT_PAYMILL_CC_STATUS',
            'MODULE_PAYMENT_PAYMILL_CC_FASTCHECKOUT',
            'MODULE_PAYMENT_PAYMILL_CC_WEBHOOKS',
            'MODULE_PAYMENT_PAYMILL_CC_AMEX',
            'MODULE_PAYMENT_PAYMILL_CC_VISA',
            'MODULE_PAYMENT_PAYMILL_CC_UNIONPAY',
            'MODULE_PAYMENT_PAYMILL_CC_MASTERCARD',
            'MODULE_PAYMENT_PAYMILL_CC_JCB',
            'MODULE_PAYMENT_PAYMILL_CC_DISCOVER',
            'MODULE_PAYMENT_PAYMILL_CC_DINERSCLUB',
            'MODULE_PAYMENT_PAYMILL_CC_CARTEBLEUE',
            'MODULE_PAYMENT_PAYMILL_CC_DANKORT',
            'MODULE_PAYMENT_PAYMILL_CC_CARTASI',
            'MODULE_PAYMENT_PAYMILL_CC_MAESTRO',
            'MODULE_PAYMENT_PAYMILL_CC_PRIVATEKEY',
            'MODULE_PAYMENT_PAYMILL_CC_PUBLICKEY',
            'MODULE_PAYMENT_PAYMILL_CC_ORDER_STATUS_ID',
            'MODULE_PAYMENT_PAYMILL_CC_TRANSACTION_ORDER_STATUS_ID',
            'MODULE_PAYMENT_PAYMILL_CC_ZONE',
            'MODULE_PAYMENT_PAYMILL_CC_ALLOWED',
            'MODULE_PAYMENT_PAYMILL_CC_LOGGING',
            'MODULE_PAYMENT_PAYMILL_CC_SORT_ORDER'
        );
    }
}
?>
