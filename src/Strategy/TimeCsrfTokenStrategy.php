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
 * Set an expiration time for a stored csrf token
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class TimeCsrfTokenStrategy implements CsrfStrategyInterface
{
    
    /**
     * Time in seconds which the token is still valid
     * 
     * @var int
     */
    private $time;
    
    /**
     * Initialize the strategy
     * 
     * @param int $time
     *   Time in seconds which the csrf token is still valid
     */
    public function __construct(int $time)
    {
        $this->time = $time;
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Csrf\Strategy\CsrfStrategyInterface::process()
     */
    public function process(): ?array
    {
        return [
            CsrfStrategyInterface::PRE_VALIDATION_PROCESS   =>  function(CsrfToken $token, CsrfInterface $csrf): void {
                if($token->getTimestamp() + $this->time <= \time()) {
                    $csrf->refresh();
                }
            }
        ];
    }

}