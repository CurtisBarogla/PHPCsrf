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

use Ness\Component\Csrf\Generator\CsrfTokenGeneratorInterface;
use Ness\Component\Csrf\Storage\CsrfTokenStorageInterface;
use Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface;
use Ness\Component\Csrf\Exception\CsrfTokenNotFoundException;
use Ness\Component\Csrf\Exception\InvalidCsrfTokenException;
use Ness\Component\Csrf\Exception\CriticalCsrfException;

/**
 * Native implementation of CsrfTokenManagerInterface
 * Validate csrf token over strategy
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class CsrfTokenManager implements CsrfTokenManagerInterface
{
    
    /**
     * Csrf token generator
     * 
     * @var CsrfTokenGeneratorInterface
     */
    private $generator;
    
    /**
     * Store generated csrf token
     * 
     * @var CsrfTokenStorageInterface
     */
    private $storage;
    
    /**
     * Strategy used to validate csrf token
     * 
     * @var CsrfTokenValidationStrategyInterface
     */
    private $strategy;
    
    /**
     * Initialize csrf manager
     * 
     * @param CsrfTokenGeneratorInterface $generator
     *   Csrf token generator
     * @param CsrfTokenStorageInterface $storage
     *   Csrf token store
     * @param CsrfTokenValidationStrategyInterface $strategy
     *   Validate strategy
     */
    public function __construct(
        CsrfTokenGeneratorInterface $generator, 
        CsrfTokenStorageInterface $storage, 
        CsrfTokenValidationStrategyInterface $strategy)
    {
        $this->generator = $generator;
        $this->storage = $storage;
        $this->strategy = $strategy;
        
        if($this->strategy instanceof CsrfTokenManagerAwareInterface)
            $this->strategy->setManager($this);
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\CsrfTokenManagerInterface::generate()
     */
    public function generate(): CsrfToken
    {
        $this->strategy->onGeneration();
        if(null === $token = $this->storage->get()) {
            $token = $this->generator->generate();
            if(false === $this->storage->store($token))
                throw new CriticalCsrfException(\sprintf("Failed to store the csrf token into the given store '%s'",
                    \get_class($this->storage)));
        }
        
        return $token;
    }

    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\CsrfTokenManagerInterface::invalidate()
     */
    public function invalidate(): void
    {
        if(false === $this->storage->delete())
            throw new CriticalCsrfException(\sprintf("Failed to invalidate the csrf token provided by the given store '%s'",
                \get_class($this->storage)));
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\CsrfTokenManagerInterface::validate()
     */
    public function validate(CsrfToken $token): void
    {        
        if(null === $stored = $this->storage->get())
            throw new CsrfTokenNotFoundException("Csrf token not found");
        
        $this->strategy->onSubmission($stored);
        
        try {
            if(!\hash_equals( ( $stored = $this->storage->get() )->get(), $token->get()))
                throw new InvalidCsrfTokenException("Invalid csrf token given");            
        } catch (\Error $e) {
            throw new InvalidCsrfTokenException("Invalid csrf token given");
        }
        
        $this->strategy->postSubmission($stored);
    }

}
