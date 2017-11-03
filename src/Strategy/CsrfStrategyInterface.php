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

namespace Zoe\Component\Csrf\Strategy;

/**
 * Manage token over the validation process
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface CsrfStrategyInterface
{
    
    /**
     * Process executed before the validation of the csrf token
     * 
     * @var int
     */
    public const PRE_VALIDATION_PROCESS = 0;
    
    /**
     * Process executed after the validation of the csrf token
     * 
     * @var int
     */
    public const POST_VALIDATION_PROCESS = 1;
    
    /**
     * Process a strategy over the token csrf pre and/or post validation
     * Each can be setted to a callable or null to be skipped
     * Callable take as parameters the current stored csrf token and the csrf instance
     *  
     * @return callable[]|null
     *   Callable for each pre or post process. Can return null to do nothing
     */
    public function process(): ?array;
    
}
