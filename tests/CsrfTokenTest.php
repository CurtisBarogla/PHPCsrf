<?php
//StrictType
declare(strict_types = 1);

/*
 * Zoe
 * Csrf component
 *
 * Author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */

namespace ZoeTest\Component\Csrf;

use PHPUnit\Framework\TestCase;
use Zoe\Component\Csrf\CsrfToken;

/**
 * CsrfToken testcase
 * 
 * @see \Zoe\Component\Csrf\CsrfToken
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class CsrfTokenTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Csrf\CsrfToken::get()
     */
    public function testGet(): void
    {
        $token = new CsrfToken("foo");
        
        $this->assertSame("foo", $token->get());
    }
    
    /**
     * @see \Zoe\Component\Csrf\CsrfToken::getTimestamp()
     */
    public function testGetTimestamp(): void
    {
        $token = new CsrfToken("foo");
        
        $this->assertSame(\time(), $token->getTimestamp());
    }
    
    /**
     * @see \Zoe\Component\Csrf\CsrfToken::__toString()
     */
    public function test__toString(): void
    {
        $this->expectOutputString("foo");
        
        $token = new CsrfToken("foo");
        echo $token;
    }
    
}
