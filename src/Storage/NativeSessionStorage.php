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
 * Use the native session array to store csrf token
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NativeSessionStorage implements CsrfTokenStorageInterface
{
    
    /**
     * $_SESSION
     * 
     * @var array
     */
    private $session;
    
    /**
     * Initialize the storage
     */
    public function __construct()
    {
        $this->session = &$_SESSION;
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Csrf\Storage\CsrfTokenStorageInterface::add()
     */
    public function add(string $tokenIdentifer, CsrfToken $token): void
    {
        $this->session[$tokenIdentifer] = $token;
    }

    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Csrf\Storage\CsrfTokenStorageInterface::get()
     */
    public function get(string $tokenIdentifier): CsrfToken
    {
        $this->checkToken($tokenIdentifier);
            
        return $this->session[$tokenIdentifier];
    }

    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Csrf\Storage\CsrfTokenStorageInterface::delete()
     */
    public function delete(string $tokenIdentifier): void
    {
        $this->checkToken($tokenIdentifier);
        
        unset($this->session[$tokenIdentifier]);
    }
    
    /**
     * Check if a token is in the store
     * 
     * @param string $tokenIdentifier
     *   Csrf token identifer
     * 
     * @throws InvalidCsrfTokenException
     *   When no token found
     */
    private function checkToken(string $tokenIdentifier): void
    {
        if(!isset($this->session[$tokenIdentifier]))
            throw new InvalidCsrfTokenException(\sprintf("No csrf token found for this identifier '%s'",
                $tokenIdentifier));
    }
    
}
