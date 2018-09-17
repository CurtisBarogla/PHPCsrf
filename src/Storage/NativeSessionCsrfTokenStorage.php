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
 * Use native session to store a csrf token
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NativeSessionCsrfTokenStorage implements CsrfTokenStorageInterface
{
    
    /**
     * $_SESSION
     * 
     * @var array
     */
    private $session;
    
    /**
     * Identify the csrf token through the session
     * 
     * @var string
     */
    private const CSRF_TOKEN_SESSION_IDENTIFIER = "ness_csrf_token_";
    
    /**
     * Initialize storage
     * 
     * @throws \LogicException
     *   When session not active
     */
    public function __construct()
    {
        if(session_status() !== PHP_SESSION_ACTIVE)
            throw new \LogicException("Cannot use NativeSessionCsrfTokenStorage as csrf token storage as session is not active for the current request");
        
        $this->session = &$_SESSION;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Storage\CsrfTokenStorageInterface::store()
     */
    public function store(CsrfToken $token): bool
    {
        $this->session[self::CSRF_TOKEN_SESSION_IDENTIFIER] = $token;
        
        return true;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Storage\CsrfTokenStorageInterface::get()
     */
    public function get(): ?CsrfToken
    {
        return $this->session[self::CSRF_TOKEN_SESSION_IDENTIFIER] ?? null;
    }

    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Storage\CsrfTokenStorageInterface::delete()
     */
    public function delete(): bool
    {
        if(!isset($this->session[self::CSRF_TOKEN_SESSION_IDENTIFIER]))
            return false;
        
        unset($this->session[self::CSRF_TOKEN_SESSION_IDENTIFIER]);
        
        return true;
    }

}
