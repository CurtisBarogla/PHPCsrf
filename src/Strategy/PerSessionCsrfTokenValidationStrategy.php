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
 * Token is valid during the session lifetime
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PerSessionCsrfTokenValidationStrategy extends AbstractCsrfTokenValidationStrategy
{

    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface::onGeneration()
     */
    public function onGeneration(CsrfTokenManagerInterface $token): void
    {
        return;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface::onSubmission()
     */
    public function onSubmission(CsrfTokenManagerInterface $manager): void
    {
        return;
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
