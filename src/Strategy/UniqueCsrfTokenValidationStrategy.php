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

use Ness\Component\Csrf\CsrfTokenManagerAwareInterface;
use Ness\Component\Csrf\Traits\CsrfTokenManagerAwareTrait;
use Ness\Component\Csrf\CsrfToken;

/**
 * Csrf token is invalidated when consumed
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class UniqueCsrfTokenValidationStrategy implements CsrfTokenValidationStrategyInterface, CsrfTokenManagerAwareInterface
{
    
    use CsrfTokenManagerAwareTrait;
 
    /**
     * If token must be refreshed on each request
     * 
     * @var bool
     */
    private $refresh;
    
    /**
     * Initialize strategy
     * 
     * @param bool $refresh
     *   If token must be refresed on each request
     */
    public function __construct(bool $refresh = false)
    {
        $this->refresh = $refresh;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface::onGeneration()
     */
    public function onGeneration(): void
    {
        if($this->refresh)
            $this->getManager()->invalidate();
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface::onSubmission()
     */
    public function onSubmission(CsrfToken $token): void
    {
        return;
    }

    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface::postSubmission()
     */
    public function postSubmission(CsrfToken $token): void
    {
        if(!$this->refresh)
            $this->getManager()->invalidate();
    }
    
}
