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

namespace Ness\Component\Csrf\Strategy;

use Ness\Component\Csrf\CsrfTokenManagerInterface;

/**
 * Validate a csrf token based on an expiration time
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class TimedCsrfTokenValidationStrategy extends AbstractCsrfTokenValidationStrategy
{
    
    /**
     * Interval of time which the token is valid for the current request
     * 
     * @var \DateInterval
     */
    private $interval;
    
    /**
     * Initialize strategy
     * 
     * @param \DateInterval $interval
     *   Interval of time which the token is valid for the current request
     */
    public function __construct(\DateInterval $interval)
    {
        $this->interval = $interval;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface::onGeneration()
     */
    public function onGeneration(CsrfTokenManagerInterface $manager): void
    {
        $manager->invalidate();
    }

    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface::onSubmission()
     */
    public function onSubmission(CsrfTokenManagerInterface $manager): void
    {
        if($this->getToken()->generatedAt()->add($this->interval) < new \DateTime())          
            $manager->invalidate();
    }

    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface::postSubmission()
     */
    public function postSubmission(CsrfTokenManagerInterface $manager): void
    {
        return;
    }

}
