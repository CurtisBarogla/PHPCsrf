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

use Ness\Component\Csrf\CsrfToken;

/**
 * Token is valid during the session lifetime.
 * Report to session mechanism
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PerSessionCsrfTokenValidationStrategy implements CsrfTokenValidationStrategyInterface
{
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface::onGeneration()
     */
    public function onGeneration(): void
    {
        return;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface::onSubmission()
     */
    public function onSubmission(CsrfToken $token): void
    {
        return;
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
