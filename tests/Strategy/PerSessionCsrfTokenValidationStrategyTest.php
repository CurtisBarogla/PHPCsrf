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


use Ness\Component\Csrf\Strategy\PerSessionCsrfTokenValidationStrategy;
use Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface;

/**
 * PerSessionCsrfTokenValidationStrategy testcase
 * 
 * @see \Ness\Component\Csrf\Strategy\PerSessionCsrfTokenValidationStrategy
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PerSessionCsrfTokenValidationStrategyTest extends AbstractCsrfTokenValidationStrategyTest
{
    
    /**
     * @see \Ness\Component\Csrf\Strategy\PerSessionCsrfTokenValidationStrategy::onGeneration()
     */
    public function testOnGeneration(): void
    {
        $this->assertNull($this->strategy->onGeneration($this->getManager(null)));
    }
    
    /**
     * @see \Ness\Component\Csrf\Strategy\PerSessionCsrfTokenValidationStrategy::onSubmission()
     */
    public function testOnSubmission(): void
    {
        $this->assertNull($this->strategy->onSubmission($this->getManager(null)));
    }
    
    /**
     * @see \Ness\Component\Csrf\Strategy\PerSessionCsrfTokenValidationStrategy::postSubmission()
     */
    public function testPostSubmission(): void
    {
        $this->assertNull($this->strategy->postSubmission($this->getManager(null)));
    }

    /**
     * {@inheritDoc}
     * @see \NessTest\Component\Csrf\Strategy\AbstractCsrfTokenValidationStrategyTest::getStrategy()
     */
    protected function getStrategy(): CsrfTokenValidationStrategyInterface
    {
        return new PerSessionCsrfTokenValidationStrategy();
    }
    
}
