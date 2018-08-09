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
 * Common to all csrf token validation strategies
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
abstract class AbstractCsrfTokenValidationStrategy implements CsrfTokenValidationStrategyInterface
{
    
    /**
     * Csrf token
     * 
     * @var CsrfToken;
     */
    private $token;
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface::getToken()
     */
    public function getToken(): CsrfToken
    {
        return $this->token;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface::setToken()
     */
    public function setToken(CsrfToken $token): void
    {
        $this->token = $token;
    }
    
}
