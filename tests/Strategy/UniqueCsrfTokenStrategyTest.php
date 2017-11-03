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

namespace ZoeTest\Component\Csrf\Strategy;

use PHPUnit\Framework\TestCase;
use Zoe\Component\Csrf\Strategy\UniqueCsrfTokenStrategy;
use Zoe\Component\Csrf\Strategy\CsrfStrategyInterface;
use Zoe\Component\Csrf\CsrfToken;
use Zoe\Component\Csrf\Csrf;

/**
 * UniqueCsrfTokenStrategy testcase
 * 
 * @see \Zoe\Component\Csrf\Strategy\UniqueCsrfTokenStrategy
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class UniqueCsrfTokenStrategyTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Csrf\Strategy\UniqueCsrfTokenStrategy
     */
    public function testInterface(): void
    {
        $strategy = new UniqueCsrfTokenStrategy();
        
        $this->assertInstanceOf(CsrfStrategyInterface::class, $strategy);
    }
    
    /**
     * @see \Zoe\Component\Csrf\Strategy\UniqueCsrfTokenStrategy::process()
     */
    public function testProcess(): void
    {
        $token = new CsrfToken("foo");
        $mockedCsrf = $this->getMockBuilder(Csrf::class)->disableOriginalConstructor()->setMethods(["refresh"])->getMock();
        $mockedCsrf->expects($this->once())->method("refresh")->will($this->returnValue(null));
        
        $strategy = new UniqueCsrfTokenStrategy();
        \call_user_func($strategy->process()[CsrfStrategyInterface::POST_VALIDATION_PROCESS], $token, $mockedCsrf);
    }
    
}
