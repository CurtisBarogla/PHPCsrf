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

use Ness\Component\Csrf\CsrfTokenManagerAwareInterface;
use Ness\Component\Csrf\Traits\CsrfTokenManagerAwareTrait;
use Ness\Component\Csrf\CsrfToken;

/**
 * Validate a csrf token based on an expiration time
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class TimedCsrfTokenValidationStrategy implements CsrfTokenValidationStrategyInterface, CsrfTokenManagerAwareInterface
{
    
    use CsrfTokenManagerAwareTrait;
    
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
    public function onGeneration(): void
    {
        $this->manager->invalidate();
    }

    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface::onSubmission()
     */
    public function onSubmission(CsrfToken $token): void
    {
        if($token->generatedAt()->add($this->interval) < new \DateTime())          
            $this->manager->invalidate();
    }

    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface::postSubmission()
     */
    public function postSubmission(CsrfToken $token): void
    {
        return;
    }

}
