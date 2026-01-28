<?php

namespace Tests\Unit;

use Brikmas\Jazzcash\Entities\VoucherReq;
use Brikmas\Jazzcash\JazzClient;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    private $client;
    private $voucherReq;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        $this->voucherReq = new VoucherReq();
        $this->client = new JazzClient([
            'apiBaseUrl' => 'https://payments.jazzcash.com.pk/',
            'merchantId' => '00168280',
            'password' => 'db5920syw0',
            'salt' => 'va15u32etw',
        ]);
    }

    public function testJazzClientIsDefined()
    {
        $this->assertIsObject($this->client);
    }

    public function testVoucherReqIsDefined()
    {
        $this->assertIsObject($this->voucherReq);
    }

    public function testVoucherService()
    {
        $txn_ref_no = time();
        $request = new VoucherReq();
        $request->setAmount(10.0);
        $request->setBillRefNumber($txn_ref_no);
        $request->setDescription('Unit test case');
        $request->setTxnRefNumber($txn_ref_no);
        $request->setVersion('1.1');
        $request->setTxnType('OTC');
        $request->setCallbackUrl('https://balochtransport.com/gateway/jazzcash/callback');
        $request->setCustomProperty('03331234567');

        $response = $this->client->callVoucherService($request);

        $this->assertNotEmpty($response);
        $this->assertObjectHasAttribute('pp_ResponseCode', $response);
        $this->assertEquals(124, $response->pp_ResponseCode);
        $this->assertEquals("Order is placed and waiting for financials to be received over the counter.", $response->pp_ResponseMessage);
    }
}
