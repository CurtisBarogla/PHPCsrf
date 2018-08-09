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

namespace Ness\Component\Csrf;

use Ness\Component\Csrf\Exception\InvalidCsrfTokenException;
use Ness\Component\Csrf\Exception\CsrfTokenNotFoundException;

/**
 * Responsible to generate and validate Csrf tokens
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface CsrfTokenManagerInterface
{
    
    /**
     * Generate a csrf token
     * 
     * @return CsrfToken
     *   Csrf token
     */
    public function generate(): CsrfToken;
    
    /**
     * Invalidate an already generated csrf token
     */
    public function invalidate(): void;
    
    /**
     * Validate an already generated csrf token. 
     * 
     * @param CsrfToken $token
     *   Csrf token to validate
     *   
     * @throws InvalidCsrfTokenException
     *   If given csrf token cannot refer an already generated one
     * @throws CsrfTokenNotFoundException
     *   When no csrf token has been previously generated
     */
    public function validate(CsrfToken $token): void;
    
}
