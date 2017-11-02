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

use Zoe\Component\Csrf\CsrfInterface;

/**
 * Manage token over the validation process
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface CsrfTokenStrategyInterface
{
    
    /**
     * Process a strategy over the token csrf
     * 
     * @param CsrfInterface $csrf
     *   Csrf   
     */
    public function process(CsrfInterface $csrf): void;
    
}
