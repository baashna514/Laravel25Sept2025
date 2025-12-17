<?php


namespace Brikmas\Easypaisa;


abstract class EpResponse
{
    public  const SUCCESS  = 0000;
    private const MESSAGES = [
        '0000' => 'SUCCESS',
        '0001' => 'SYSTEM ERROR',
        '0002' => 'REQUIRED FIELD MISSING',
        '0003' => '',
        '0004' => '',
        '0005' => 'MERCHANT ACCOUNT NOT ACTIVE',
        '0006' => 'INVALID STORE ID',
        '0007' => 'STORE NOT ACTIVE',
        '0008' => 'PAYMENT METHOD NOT ENABLED',
        '0009' => '',
        '0010' => 'INVALID CREDENTIALS',
        '0011' => '',
        '0012' => '',
        '0013' => 'LOW BALANCE',
        '0014' => 'ACCOUNT DOES NOT EXIST',
        '0015' => 'INVALID_TOKEN_EXPIRY',
        '0016' => 'Expiry date should be future date',
    ];

    public static function Message($code)
    {
        return self::MESSAGES[$code];
    }
}