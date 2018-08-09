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

namespace Ness\Component\Csrf\Storage;

use Ness\Component\Csrf\CsrfToken;

/**
 * Store Csrf token
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface CsrfTokenStorageInterface
{
    
    /**
     * Store a Csrf token
     * 
     * @param CsrfToken $token
     *   Csrf token to store
     * 
     * @return bool
     *   True if the token has been stored with success. Fals otherwise
     */
    public function store(CsrfToken $token): bool;
    
    /**
     * Get an already stored Csrf token
     * 
     * @return CsrfToken|null
     *   Csrf token assigned or null if not found
     */
    public function get(): ?CsrfToken;
    
    /**
     * Delete a stored Csrf token 
     * 
     * @return bool
     *   True if the Csrf token has been correctly deleted from the store. False otherwise
     */
    public function delete(): bool;
    
}
