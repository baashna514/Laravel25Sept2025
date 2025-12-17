<?php


namespace Brikmas\Easypaisa\Entities;


class PaymentReq
{
    /**
     * Merchant's system generated Order ID.
     *
     * @var $orderId
     */
    public $orderId;
    /**
     * Store id or merchant id associated with partner Account,
     * Generated during merchant registration in Easypaisa.
     *
     * @var $storeId
     */
    public $storeId;
    /**
     * Amount or Total price of order.
     *
     * @var $transactionAmount
     */
    public $transactionAmount;
    /**
     * Transaction type, possible values are MA, OTC, CC.
     *
     * @var $transactionType
     */
    public $transactionType;
    /**
     * Mobile number, Required in OTC transaction.
     *
     * @var $Msisdn
     */
    public $Msisdn;
    /**
     * Mobile number, Required in MA transaction.
     *
     * @var $mobileAccountNo
     */
    public $mobileAccountNo;
    /**
     * Email address, Required in MA, OTC transactions.
     *
     * @var $emailAddress
     */
    public $emailAddress;
    /**
     * Token expiry, Required in OTC transaction.
     *
     * @var $tokenExpiry
     */
    public $tokenExpiry;
    /**
     * Optional parameter. Used for custom data received in response.
     * Possible use case of these parameters are in callbacks or IPN.
     *
     * @var $optional1
     */
    public $optional1;
    public $optional2;
    public $optional3;
    public $optional4;
    public $optional5;


    public function __construct()
    {
        $this->setTxnExpiry(30);
    }

    /**
     * @return mixed
     */
    public function getTxnRefNumber()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $value
     */
    public function setTxnRefNumber($value): void
    {
        $this->orderId = $value;
    }

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->storeId;
    }

    /**
     * @param mixed $value
     */
    public function setMerchantId($value): void
    {
        $this->storeId = $value;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->transactionAmount;
    }

    /**
     * @param mixed $value
     */
    public function setAmount($value): void
    {
        $this->transactionAmount = number_format($value, 2, '.', '');
    }

    /**
     * @return mixed
     */
    public function getTxnType()
    {
        return $this->transactionType;
    }

    /**
     * @param mixed $value
     */
    public function setTxnType($value): void
    {
        $this->transactionType = $value;
    }

    /**
     * @return mixed
     */
    public function getMobileNumber()
    {
        return $this->mobileAccountNo;
    }

    /**
     * @param mixed $value
     */
    public function setMobileNumber($value): void
    {
        $this->mobileAccountNo = $value;
    }

    /**
     * @return mixed
     */
    public function getMsisdn()
    {
        return $this->Msisdn;
    }

    /**
     * @param mixed $value
     */
    public function setMsisdn($value): void
    {
        $this->Msisdn = $value;
    }

    /**
     * @return mixed
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param mixed $emailAddress
     */
    public function setEmailAddress($emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return false|string
     */
    public function getTxnExpiry()
    {
        return $this->tokenExpiry;
    }

    /**
     * @param false|string $minutes
     */
    public function setTxnExpiry($minutes): void
    {
        date_default_timezone_set("Asia/Karachi");
        $this->tokenExpiry = date('Ymd His', strtotime("+{$minutes} Minutes"));
    }

    /**
     * @return mixed
     */
    public function getOptional1()
    {
        return $this->optional1;
    }

    /**
     * @param mixed $optional1
     */
    public function setOptional1($optional1): void
    {
        $this->optional1 = $optional1;
    }

    /**
     * @return mixed
     */
    public function getOptional2()
    {
        return $this->optional2;
    }

    /**
     * @param mixed $optional2
     */
    public function setOptional2($optional2): void
    {
        $this->optional2 = $optional2;
    }

    /**
     * @return mixed
     */
    public function getOptional3()
    {
        return $this->optional3;
    }

    /**
     * @param mixed $optional3
     */
    public function setOptional3($optional3): void
    {
        $this->optional3 = $optional3;
    }

    /**
     * @return mixed
     */
    public function getOptional4()
    {
        return $this->optional4;
    }

    /**
     * @param mixed $optional4
     */
    public function setOptional4($optional4): void
    {
        $this->optional4 = $optional4;
    }

    /**
     * @return mixed
     */
    public function getOptional5()
    {
        return $this->optional5;
    }

    /**
     * @param mixed $optional5
     */
    public function setOptional5($optional5): void
    {
        $this->optional5 = $optional5;
    }
}