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

/**
 * Link a component to a CsrfTokenManagerInterface implementation
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface CsrfTokenManagerAwareInterface
{
    
    /**
     * Link a csrf token manager to the component
     * 
     * @param CsrfTokenManagerInterface $manager
     *   Csrf token manager
     */
    public function setManager(CsrfTokenManagerInterface $manager): void;
    
}
