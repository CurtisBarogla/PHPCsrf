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

namespace Zoe\Component\Csrf\Storage;

use Zoe\Component\Csrf\CsrfToken;
use Zoe\Component\Csrf\Exception\InvalidCsrfTokenException;

/**
 * Used for storing a token
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface CsrfTokenStorageInterface
{
    
    /**
     * Add a csrf token into the store
     * 
     * @param string $tokenIdentifer
     *   Csrf token identifer
     * @param CsrfToken $token
     *   Csrf token instance
     */
    public function add(string $tokenIdentifer, CsrfToken $token): void;
    
    /**
     * Get a stored Csrf
     * 
     * @param string $tokenIdentifier
     *   Csrf token identifier
     * 
     * @return CsrfToken
     *   Csrf token instance
     *   
     * @throws InvalidCsrfTokenException
     *   When no token has been found
     */
    public function get(string $tokenIdentifier): CsrfToken;
    
    /**
     * Delete a token from the store
     * 
     * @param string $tokenIdentifier
     *   Csrf token identifer
     *   
     * @throws InvalidCsrfTokenException
     *   When no token has been found
     */
    public function delete(string $tokenIdentifier): void;
    
}
