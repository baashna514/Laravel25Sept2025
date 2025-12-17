<?php

namespace Tests\Unit;

use Brikmas\Easypaisa\EasypaisaClient;
use Brikmas\Easypaisa\Entities\PaymentReq;
use PHPUnit\Framework\TestCase;

final class EasypaisaClientTest extends TestCase
{
    private $client;
    private $payload;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    public function setUp(): void
    {
        // Production credentials
        $this->client = new EasypaisaClient([
            'apiBaseUrl' => 'https://easypay.easypaisa.com.pk/',
            'merchantId' => '63101',
            'username' => 'BalochTransportservices',
            'password' => 'e1803eafba93a4551fba27bb75f5e8b6',
        ]);

        $this->payload = new PaymentReq();
        $this->payload->setTxnRefNumber('1002');
        $this->payload->setAmount(2);
        $this->payload->setEmailAddress('ibrahimshahid27@gmail.com');
        $this->payload->setMobileNumber('03137525312');
    }

    public function testClientIsDefined()
    {
        $this->assertIsObject($this->client);
    }

    public function testPayloadIsDefined()
    {
        $this->assertIsObject($this->payload);
    }

//    public function testMAService()
//    {
//        $rsp = $this->client->callMobileAccountService($this->payload);
//
//        $actual = '0000: SUCCESS';
//        $expected = $rsp->responseCode.': '.$rsp->responseDesc;
//
//        $this->assertEquals($actual, $expected);
//    }

//    public function testVoucherService()
//    {
//        $rsp = $this->client->callVoucherService($this->payload);
//
//        $actual = '0000: SUCCESS';
//        $expected = $rsp->responseCode.': '.$rsp->responseDesc;
//
//        $this->assertEquals($actual, $expected);
//    }
}
