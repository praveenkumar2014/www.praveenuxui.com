<?php

use PHPUnit\Framework\TestCase;
use App\Helpers\Security;

class SecurityTest extends TestCase
{
    public function testSanitizeInput()
    {
        $input = "<script>alert('xss')</script>";
        $sanitized = Security::sanitizeInput($input);
        $this->assertEquals("&lt;script&gt;alert(&#039;xss&#039;)&lt;/script&gt;", $sanitized);
    }

    public function testCSRFTokenGeneration()
    {
        $token = Security::generateCSRFToken();
        $this->assertNotEmpty($token);
        $this->assertTrue(Security::verifyCSRFToken($token));
    }
}
