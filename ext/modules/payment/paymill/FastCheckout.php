<?php
require_once(DIR_FS_CATALOG . 'ext/modules/payment/paymill/lib/Services/Paymill/Payments.php');
class FastCheckout
{
    private $_fastCheckoutFlag = false;
    
    public function canCustomerFastCheckoutCcTemplate($userId)
    {
        $flag = 'false';
        if ($this->canCustomerFastCheckoutCc($userId)) {
            $flag = 'true';
        }
        
        return $flag;
    }    
    
    public function canCustomerFastCheckoutElvTemplate($userId)
    {
        $flag = 'false';
        if ($this->canCustomerFastCheckoutElv($userId)) {
            $flag = 'true';
        }
        
        return $flag;
    }    
    
    public function canCustomerFastCheckoutCc($userId)
    {   
        return $this->hasCcPaymentId($userId) && $this->_fastCheckoutFlag;
    }
    
    public function canCustomerFastCheckoutElv($userId)
    {   
        return $this->hasElvPaymentId($userId) && $this->_fastCheckoutFlag;
    }
    
    public function saveCcIds($userId, $newClientId, $newPaymentId)
    {
        if ($this->_canUpdate($userId)) {
            $sql = "UPDATE `pi_paymill_fastcheckout`SET `paymentID_CC` = '$newPaymentId' WHERE `userID` = '$userId'";
        } else {
            $sql = "INSERT INTO `pi_paymill_fastcheckout` (`userID`, `clientID`, `paymentID_CC`) VALUES ('$userId', '$newClientId', '$newPaymentId')";
        }

        xtc_db_query($sql);
    }
    
    public function saveElvIds($userId, $newClientId, $newPaymentId)
    {   
        if ($this->_canUpdate($userId)) {
            $sql = "UPDATE `pi_paymill_fastcheckout`SET `paymentID_ELV` = '$newPaymentId' WHERE `userID` = '$userId'";
        } else {
            $sql = "INSERT INTO `pi_paymill_fastcheckout` (`userID`, `clientID`, `paymentID_ELV`) VALUES ('$userId', '$newClientId', '$newPaymentId')";
        }
        
       xtc_db_query($sql);
    }
    
    private function _canUpdate($userId)
    {
        $data = $this->loadFastCheckoutData($userId);
        return $data;
    }
    
    public function loadFastCheckoutData($userId)
    {
        $sql = "SELECT * FROM `pi_paymill_fastcheckout` WHERE `userID` = '$userId'";
        
        return xtc_db_fetch_array(xtc_db_query($sql));
    }

    private function hasPaymentId($paymentType, $userId)
    {
        $result = false;
        $privateKey = trim(MODULE_PAYMENT_PAYMILL_ELV_PRIVATEKEY);
        $apiUrl = 'https://api.paymill.com/v2/';
        $data = $this->loadFastCheckoutData($userId);
        if($data && array_key_exists('paymentID_'.$paymentType, $data) && !empty($data['paymentID_'.$paymentType])){
            $payment = new Services_Paymill_Payments($privateKey, $apiUrl);
            $paymentData = $payment->getOne($data['paymentID_'.$paymentType]);
            $result = $paymentData && array_key_exists('id', $paymentData) && !empty($data['id']);
        }
        return $result;
    }

    public function hasElvPaymentId($userId)
    {
        return $this->hasPaymentId("ELV", $userId);
    }

    public function hasCcPaymentId($userId)
    {
        return $this->hasPaymentId("CC", $userId);
    }

    public function setFastCheckoutFlag($fastCheckoutFlag)
    {
        $this->_fastCheckoutFlag = $fastCheckoutFlag;
    }
    
}