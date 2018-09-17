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
use Ness\Component\Csrf\Strategy\TimedCsrfTokenValidationStrategy;
use PHPUnit\Framework\MockObject\MockObject;
use Ness\Component\Csrf\CsrfToken;

/**
 * TimedCsrfTokenValidationStrategy testcase
 * 
 * @see \Ness\Component\Csrf\Strategy\TimedCsrfTokenValidationStrategy
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class TimedCsrfTokenValidationStrategyTest extends AbstractCsrfTokenValidationStrategyTest
{
    
    /**
     * @see \Ness\Component\Csrf\Strategy\TimedCsrfTokenValidationStrategy::onGeneration()
     */
    public function testOnGeneration(): void
    {
        $manager = $this->getManager(function(MockObject $manager, CsrfToken $token): void {
            $manager->expects($this->once())->method("invalidate");
        });
        
        $strategy = new TimedCsrfTokenValidationStrategy(new \DateInterval("P42D"));
        
        $this->assertNull($strategy->setManager($manager));
        $this->assertNull($strategy->onGeneration());
    }
    
    /**
     * @see \Ness\Component\Csrf\Strategy\TimedCsrfTokenValidationStrategy::onSubmission()
     */
    public function testOnSubmission(): void
    {
        $manager = $this->getManager(function(MockObject $manager, CsrfToken $token): void {
            $manager->expects($this->once())->method("invalidate");
        });
            
        $strategy = new TimedCsrfTokenValidationStrategy(new \DateInterval("P42D"));
        $strategy->setManager($manager);
        $this->assertNull($strategy->onSubmission(new CsrfToken("Foo")));
        
        $strategy = new TimedCsrfTokenValidationStrategy(new \DateInterval("PT1S"));
        $strategy->setManager($manager);
        $token = new CsrfToken("Foo");
        \sleep(2);
        $this->assertNull($strategy->onSubmission($token));
    }
    
    /**
     * @see \Ness\Component\Csrf\Strategy\TimedCsrfTokenValidationStrategy::postSubmission()
     */
    public function testPostSubmission(): void 
    {
        $this->assertNull($this->strategy->postSubmission(new CsrfToken("Foo")));
    }
    
    /**
     * {@inheritDoc}
     * @see \NessTest\Component\Csrf\Strategy\AbstractCsrfTokenValidationStrategyTest::getStrategy()
     */
    protected function getStrategy(): CsrfTokenValidationStrategyInterface
    {
        return new TimedCsrfTokenValidationStrategy(new \DateInterval("P42D"));
    }
    
}
