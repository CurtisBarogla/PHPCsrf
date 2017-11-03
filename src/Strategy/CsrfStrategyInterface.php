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
     * @var string
     */
    public const PRE_VALIDATION_PROCESS = "pre_validation";
    
    /**
     * Process executed after the validation of the csrf token
     * 
     * @var string
     */
    public const POST_VALIDATION_PROCESS = "post_validation";
    
    /**
     * Process a strategy over the token csrf post validation
     * Each can be setted to a callable or null or skipped
     * Callable take as parameters the current stored csrf token and the csrf instance
     *  
     * @return callable[]|null
     *   Callable for each pre or post process. Can return null to do nothing
     */
    public function process(): ?array;
    
}
