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
use Zoe\Component\Csrf\Strategy\SessionCsrfTokenStrategy;
use Zoe\Component\Csrf\Strategy\CsrfStrategyInterface;

/**
 * SessionCsrfTokenStrategy testcase
 * 
 * @see \Zoe\Component\Csrf\Strategy\UniqueCsrfTokenStrategy
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class SessionCsrfTokenStrategyTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Csrf\Strategy\SessionCsrfTokenStrategy
     */
    public function testInterface(): void
    {
        $strategy = new SessionCsrfTokenStrategy();
        
        $this->assertInstanceOf(CsrfStrategyInterface::class, $strategy);
    }
    
    /**
     * @see \Zoe\Component\Csrf\Strategy\SessionCsrfTokenStrategy::process()
     */
    public function testProcess(): void
    {
        // mostly useless as the session handle the removal process
        $strategy = new SessionCsrfTokenStrategy();
        
        $this->assertNull($strategy->process());
    }
    
}
