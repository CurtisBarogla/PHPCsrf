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

use Ness\Component\Csrf\CsrfTokenManagerInterface;

/**
 * Csrf token is invalidated when consumed
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class UniqueCsrfTokenValidationStrategy extends AbstractCsrfTokenValidationStrategy
{
 
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
    public function onGeneration(CsrfTokenManagerInterface $manager): void
    {
        if($this->refresh)
            $manager->invalidate();
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface::onSubmission()
     */
    public function onSubmission(CsrfTokenManagerInterface $manager): void
    {
        return;
    }

    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface::postSubmission()
     */
    public function postSubmission(CsrfTokenManagerInterface $manager): void
    {
        $manager->invalidate();
    }
    
}
