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
     * Get the linked csrf token manager
     * 
     * @return CsrfTokenManagerInterface
     *   Linked csrf token manager
     */
    public function getManager(): CsrfTokenManagerInterface;
    
    /**
     * Link a csrf token manager to the component
     * 
     * @param CsrfTokenManagerInterface $manager
     *   Csrf token manager
     */
    public function setManager(CsrfTokenManagerInterface $manager): void;
    
}
