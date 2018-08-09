<?php
//StrictType
declare(strict_types = 1);

/*
 * Ness
 * Csrf component
 *
 * Author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */

namespace NessTest\Component\Csrf;

use Ness\Component\Csrf\CsrfToken;

/**
 * CsrfToken testcase
 * 
 * @see \Ness\Component\Csrf\CsrfToken
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class CsrfTokenTest extends CsrfTestCase
{
    
    /**
     * @see \Ness\Component\Csrf\CsrfToken::get()
     */
    public function testGet(): void
    {
        $token = new CsrfToken("Foo");
        
        $this->assertSame("Foo", $token->get());
    }
    
    /**
     * @see \Ness\Component\Csrf\CsrfToken::generatedAt()
     */
    public function testGeneratedAt(): void
    {
        $format = "d/m/Y H:i:s";
        $token = new CsrfToken("Foo");
        
        $this->assertSame((new \DateTime())->format($format), $token->generatedAt()->format($format));
    }
    
    /**
     * @see \Ness\Component\Csrf\CsrfToken::__toString()
     */
    public function test__toString(): void
    {
        $this->expectOutputString("Foo");
        
        $token = new CsrfToken("Foo");
        
        echo $token;
    }
    
}
