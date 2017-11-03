<?php
//StrictType
declare(strict_types = 1);

/*
 * Zoe
 * Csrf component
 *
 * Author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */

namespace Zoe\Component\Csrf;

use Zoe\Component\Csrf\Exception\InvalidCsrfTokenException;


/**
 * Handle validation of Csrf token
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface CsrfInterface
{
    
    /**
     * Generate and store a csrf token.
     * Returned token must be a stored one or a new generated one if none has been already stored
     * 
     * @return CsrfToken
     *   Csrf token
     */
    public function generate(): CsrfToken;
    
    /**
     * Refresh a stored Csrf token
     * 
     * @throws InvalidCsrfTokenException
     *   If no Csrf token has been found into the store
     */
    public function refresh(): void;

    /**
     * Validate a token over a stored one
     * 
     * @param CsrfToken $token
     *   Token to validate
     *   
     * @throws InvalidCsrfTokenException
     *   When the given token is invalid or no token is found
     */
    public function validate(CsrfToken $token): void;
    
}
