<?php
/**
 * Opulence
 *
 * @link      https://www.opulencephp.com
 * @copyright Copyright (C) 2016 David Young
 * @license   https://github.com/opulencephp/Opulence/blob/master/LICENSE.md
 */
namespace Opulence\Authentication\Tokens\JsonWebTokens\Verification;

use Opulence\Authentication\Tokens\JsonWebTokens\JwtPayload;
use Opulence\Authentication\Tokens\JsonWebTokens\SignedJwt;

/**
 * Tests the issuer verifier
 */
class IssuerVerifierTest extends \PHPUnit_Framework_TestCase
{
    /** @var IssuerVerifier The verifier to use in tests */
    private $verifier = null;
    /** @var SignedJwt|\PHPUnit_Framework_MockObject_MockObject The token to use in tests */
    private $jwt = null;
    /** @var JwtPayload|\PHPUnit_Framework_MockObject_MockObject The payload to use in tests */
    private $jwtPayload = null;

    /**
     * Sets up the tests
     */
    public function setUp()
    {
        $this->verifier = new IssuerVerifier("foo");
        $this->jwt = $this->getMock(SignedJwt::class, [], [], "", false);
        $this->jwtPayload = $this->getMock(JwtPayload::class);
        $this->jwt->expects($this->any())
            ->method("getPayload")
            ->willReturn($this->jwtPayload);
    }

    /**
     * Tests that an exception is thrown on an invalid token
     */
    public function testExceptionThrownOnInvalidToken()
    {
        $this->jwtPayload->expects($this->once())
            ->method("getIssuer")
            ->willReturn("bar");
        $this->assertFalse($this->verifier->verify($this->jwt, $error));
        $this->assertNotEmpty($error);
    }

    /**
     * Tests verifying valid token
     */
    public function testVerifyingValidToken()
    {
        $this->jwtPayload->expects($this->once())
            ->method("getIssuer")
            ->willReturn("foo");
        $this->assertTrue($this->verifier->verify($this->jwt, $error));
        $this->assertNull($error);
    }
}