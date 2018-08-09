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

use Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface;
use Ness\Component\Csrf\Strategy\UniqueCsrfTokenValidationStrategy;
use PHPUnit\Framework\MockObject\MockObject;
use Ness\Component\Csrf\CsrfToken;

/**
 * UniqueCsrfTokenValidationStrategy testcase
 * 
 * @see \Ness\Component\Csrf\Strategy\UniqueCsrfTokenValidationStrategy
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class UniqueCsrfTokenValidationStrategyTest extends AbstractCsrfTokenValidationStrategyTest
{
    
    /**
     * @see \Ness\Component\Csrf\Strategy\UniqueCsrfTokenValidationStrategy::onGeneration()
     */
    public function testOnGeneration(): void
    {
        $manager = $this->getManager(function(MockObject $manager, CsrfToken $token): void {
            $manager->expects($this->never())->method("invalidate");  
        });
        
        $strategy = new UniqueCsrfTokenValidationStrategy();
        $this->assertNull($strategy->onGeneration($manager));
        
        $manager = $this->getManager(function(MockObject $manager, CsrfToken $token): void {
            $manager->expects($this->once())->method("invalidate");
        });
        
        $strategy = new UniqueCsrfTokenValidationStrategy(true);
        
        $this->assertNull($strategy->onGeneration($manager));
    }
    
    /**
     * @see \Ness\Component\Csrf\Strategy\UniqueCsrfTokenValidationStrategy::onSubmission()
     */
    public function testOnSubmission(): void
    {
        $this->assertNull($this->strategy->onSubmission($this->getManager()));
    }
    
    /**
     * @see \Ness\Component\Csrf\Strategy\UniqueCsrfTokenValidationStrategy::postSubmission()
     */
    public function testPostSubmission(): void
    {
        $manager = $this->getManager(function(MockObject $manager, CsrfToken $token): void {
            $manager->expects($this->once())->method("invalidate");
        });
            
        $strategy = new UniqueCsrfTokenValidationStrategy();
            
        $this->assertNull($strategy->postSubmission($manager));
    }
    
    /**
     * {@inheritDoc}
     * @see \NessTest\Component\Csrf\Strategy\AbstractCsrfTokenValidationStrategyTest::getStrategy()
     */
    protected function getStrategy(): CsrfTokenValidationStrategyInterface
    {
        return new UniqueCsrfTokenValidationStrategy();       
    }
    
}
