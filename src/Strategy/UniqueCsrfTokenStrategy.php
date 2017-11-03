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

use Zoe\Component\Csrf\CsrfToken;
use Zoe\Component\Csrf\CsrfInterface;

/**
 * Delete and re-generate a new csrf token after its usage 
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class UniqueCsrfTokenStrategy implements CsrfStrategyInterface
{
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Csrf\Strategy\CsrfStrategyInterface::process()
     */
    public function process(): ?array
    {
        return [
            CsrfStrategyInterface::POST_VALIDATION_PROCESS  =>  function(CsrfToken $token, CsrfInterface $csrf): void {
                $csrf->refresh();      
            }
        ];
    }
    
}
