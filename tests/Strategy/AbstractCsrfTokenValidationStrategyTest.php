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

namespace NessTest\Component\Csrf\Strategy;

use NessTest\Component\Csrf\CsrfTestCase;
use Ness\Component\Csrf\CsrfTokenManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface;
use Ness\Component\Csrf\CsrfToken;

/**
 * Common to all csrf strategy validation test cases
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
abstract class AbstractCsrfTokenValidationStrategyTest extends CsrfTestCase
{
    
    /**
     * Strategy currently tested.
     * Must be initialized during a setUp call
     * 
     * @var CsrfTokenValidationStrategyInterface
     */
    protected $strategy;
    
    /**
     * {@inheritDoc}
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    protected function setUp(): void
    {
        $this->strategy = $this->getStrategy();
    }
    
    /**
     * @see \Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface::setToken()
     */
    public function testSetToken(): void
    {
        $token = new CsrfToken("Foo");
        
        $this->assertNull($this->strategy->setToken($token));
    }
    
    /**
     * @see \Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface::getToken()
     */
    public function testGetToken(): void
    {
        $token = new CsrfToken("Foo");
        
        $this->strategy->setToken($token);
        $this->assertSame($token, $this->strategy->getToken());
    }
    
    /**
     * Get a mocked csrf managet
     * 
     * @param \Closure|null $action
     *   Action performed on the manager
     *   Takes as first parameter the mocked manager and as second a fresh csrf token
     * 
     * @return MockObject
     *   Mocked csrf token manager
     */
    protected function getManager(?\Closure $action = null): MockObject
    {
        $token = new CsrfToken("Foo");
        $manager = $this->getMockBuilder(CsrfTokenManagerInterface::class)->getMock();
        if(null !== $action)
            $action->call($this, $manager, $token);
        
        return $manager;
    }
    
    /**
     * Provide tested strategy
     * 
     * @return CsrfTokenValidationStrategyInterface
     *   Tested strategy
     */
    abstract protected function getStrategy(): CsrfTokenValidationStrategyInterface;
    
}
