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
use Zoe\Component\Csrf\Csrf;
use Zoe\Component\Csrf\CsrfToken;
use Zoe\Component\Csrf\Strategy\CsrfStrategyInterface;
use Zoe\Component\Csrf\Strategy\TimeCsrfTokenStrategy;

/**
 * TimeCsrfTokenStrategy testcase
 * 
 * @see \Zoe\Component\Csrf\Strategy\TimeCsrfTokenStrategy
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class TimeCsrfTokenStrategyTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Csrf\Strategy\TimeCsrfTokenStrategy
     */
    public function testInterface(): void
    {
        $strategy = new TimeCsrfTokenStrategy(1);
        
        $this->assertInstanceOf(CsrfStrategyInterface::class, $strategy);
    }
    
    /**
     * @see \Zoe\Component\Csrf\Strategy\TimeCsrfTokenStrategy::process()
     */
    public function testProcessOnExpiredToken(): void
    { 
        $strategy = new TimeCsrfTokenStrategy(1);
        $mockedCsrf = $this->getMockBuilder(Csrf::class)->disableOriginalConstructor()->setMethods(["refresh"])->getMock();
        $mockedCsrf->expects($this->exactly(2))->method("refresh")->will($this->returnValue(null));
        $token = new CsrfToken("foo");
        \sleep(1);
        \call_user_func($strategy->process()[CsrfStrategyInterface::PRE_VALIDATION_PROCESS], $token, $mockedCsrf);
        \call_user_func($strategy->process()[CsrfStrategyInterface::POST_VALIDATION_PROCESS], $token, $mockedCsrf);
    }
    
    /**
     * @see \Zoe\Component\Csrf\Strategy\TimeCsrfTokenStrategy::process()
     */
    public function testProcessOnStillValidToken(): void
    {
        $strategy = new TimeCsrfTokenStrategy(10);
        $mockedCsrf = $this->getMockBuilder(Csrf::class)->disableOriginalConstructor()->setMethods(["refresh"])->getMock();
        $mockedCsrf->expects($this->never())->method("refresh")->will($this->returnValue(null));
        $token = new CsrfToken("foo");
        \call_user_func($strategy->process()[CsrfStrategyInterface::PRE_VALIDATION_PROCESS], $token, $mockedCsrf);
    }
    
}
