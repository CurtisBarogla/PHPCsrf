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
use Ness\Component\Csrf\CsrfToken;

/**
 * Validate csrf token
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface CsrfTokenValidationStrategyInterface
{
    
    /**
     * Executed when the manager generate a token
     * 
     * @param CsrfTokenManagerInterface $manager
     *   Csrf manager
     */
    public function onGeneration(CsrfTokenManagerInterface $manager): void;
    
    /**
     * Executed when the token is processed
     * 
     * @param CsrfTokenManagerInterface $manager
     *   Csrf manager
     */
    public function onSubmission(CsrfTokenManagerInterface $manager): void;
    
    /**
     * Executed after the token has been processed and considered valid
     * 
     * @param CsrfTokenManagerInterface $manager
     *   Csrf manager
     */
    public function postSubmission(CsrfTokenManagerInterface $manager): void;
    
    /**
     * Get the token currently submitted to the validated process
     * 
     * @return CsrfToken
     *   Csrf token
     */
    public function getToken(): CsrfToken;
    
    /**
     * Set the token
     * 
     * @param CsrfToken $token
     *   Csrf token
     */
    public function setToken(CsrfToken $token): void;
    
}
