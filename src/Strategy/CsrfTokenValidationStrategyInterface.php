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
 * Validate csrf token
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface CsrfTokenValidationStrategyInterface
{
    
    /**
     * Executed when the generation process happened
     */
    public function onGeneration(): void;
    
    /**
     * Executed when the token is processed
     * 
     * @param CsrfToken $token
     *   Token processed
     */
    public function onSubmission(CsrfToken $token): void;
    
    /**
     * Executed after the token has been processed and considered valid
     * 
     * @param CsrfToken $token
     *   Token processed
     */
    public function postSubmission(CsrfToken $token): void;
    
}
