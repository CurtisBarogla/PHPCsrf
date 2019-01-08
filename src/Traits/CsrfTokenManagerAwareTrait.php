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

namespace Ness\Component\Csrf\Traits;

use Ness\Component\Csrf\CsrfTokenManagerInterface;

/**
 * Shortcut making a component compliant with CsrfTokenManagerAwareInterface
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
trait CsrfTokenManagerAwareTrait
{

    /**
     * Csrf token manager linked
     *
     * @var CsrfTokenManagerInterface
     */
    private $manager;
    
    /**
     * Get the linked csrf token manager
     *
     * @return CsrfTokenManagerInterface
     *   Linked csrf token manager
     */
    public function getManager(): CsrfTokenManagerInterface
    {
        return $this->manager;
    }
    
    /**
     * Link the csrf token manager to the component
     * 
     * @param CsrfTokenManagerInterface $manager
     *   Csrf token manager
     */
    public function setManager(CsrfTokenManagerInterface $manager): void
    {
        $this->manager = $manager;
    }
    
}
