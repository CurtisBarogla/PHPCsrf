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
 * Csrf token valid for the duration of the session.
 * In other word, let the session handle the refresh process
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class SessionCsrfTokenStrategy implements CsrfStrategyInterface
{
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Csrf\Strategy\CsrfStrategyInterface::process()
     */    
    public function process(): ?array
    {
        return null;
    }

}
