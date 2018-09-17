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
use Ness\Component\Csrf\CsrfToken;

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
        $this->assertNull($this->strategy->onGeneration());
    }
    
    /**
     * @see \Ness\Component\Csrf\Strategy\PerSessionCsrfTokenValidationStrategy::onSubmission()
     */
    public function testOnSubmission(): void
    {
        $this->assertNull($this->strategy->onSubmission(new CsrfToken("Foo")));
    }
    
    /**
     * @see \Ness\Component\Csrf\Strategy\PerSessionCsrfTokenValidationStrategy::postSubmission()
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
        return new PerSessionCsrfTokenValidationStrategy();
    }
    
}
