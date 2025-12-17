<?php


namespace Brikmas\Easypaisa\Entities;


class InquireTxnReq extends PaymentReq
{
    /**
     * Merchant's EWP Account Number present at Profile Page of Easypaisa
     * Merchant portal "EWP Account".
     *
     * @var $accountNum
     */
    public $accountNum;

    /**
     * @return mixed
     */
    public function getAccountNumber()
    {
        return $this->accountNum;
    }

    /**
     * @param mixed $value
     */
    public function setAccountNumber($value): void
    {
        $this->accountNum = $value;
    }
}