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

namespace Zoe\Component\Csrf;

use Zoe\Component\Csrf\Exception\InvalidCsrfTokenException;
use Zoe\Component\Csrf\Generator\TokenGeneratorInterface;
use Zoe\Component\Csrf\Storage\CsrfTokenStorageInterface;
use Zoe\Component\Csrf\Strategy\CsrfStrategyInterface;
use Zoe\Component\Csrf\Exception\LogicException;

/**
 * Manage Csrf token generation and validation
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class Csrf implements CsrfInterface
{
    
    /**
     * Csrf token storage
     * 
     * @var CsrfTokenStorageInterface
     */
    private $storage;
    
    /**
     * Token generator
     * 
     * @var TokenGeneratorInterface
     */
    private $generator;
    
    /**
     * Validation strategy
     * 
     * @var CsrfStrategyInterface
     */
    private $stategy;
    
    /**
     * Identifier to store a csrf token
     * 
     * @var string
     */
    private const CSRF_IDENTIFER = "CSRF-TOKEN";
    
    /**
     * Initialize csrf 
     * 
     * @param CsrfTokenStorageInterface $storage
     *   Csrf token storage
     * @param TokenGeneratorInterface $generator
     *   Token generator
     * @param CsrfStrategyInterface $strategy
     *   Validation strategy
     */
    public function __construct(CsrfTokenStorageInterface $storage, TokenGeneratorInterface $generator, CsrfStrategyInterface $strategy)
    {
        $this->storage = $storage;
        $this->generator = $generator;
        $this->stategy = $strategy;
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Csrf\CsrfInterface::generate()
     */
    public function generate(): CsrfToken
    {
        try {
            return $this->storage->get(self::CSRF_IDENTIFER); 
        } catch (InvalidCsrfTokenException $e) {
            $token = new CsrfToken($this->generator->generate());
            $this->storage->add(self::CSRF_IDENTIFER, $token);
            
            return $token;
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Csrf\CsrfInterface::refresh()
     */
    public function refresh(): void
    {
        $this->storage->delete(self::CSRF_IDENTIFER);
        $this->storage->add(self::CSRF_IDENTIFER, new CsrfToken($this->generator->generate()));
    }

    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Csrf\CsrfInterface::validate()
     */
    public function validate(CsrfToken $token): void
    {        
        $processors = $this->stategy->process();
        $this->executeProcess($this->storage->get(self::CSRF_IDENTIFER), $processors, CsrfStrategyInterface::PRE_VALIDATION_PROCESS);
        
        $postPreProcessToken = $this->storage->get(self::CSRF_IDENTIFER);
        if(!\hash_equals($postPreProcessToken->get(), $token->get()))
            throw new InvalidCsrfTokenException("Invalid csrf token");
        
        $this->executeProcess($postPreProcessToken, $processors, CsrfStrategyInterface::POST_VALIDATION_PROCESS);
    }
    
    /**
     * Execute a processor
     * 
     * @param CsrfToken $token
     *   Csrf token currently process
     * @param array|null $processors
     *   Array of processors
     * @param int $processor
     *   Processor from processors to execute
     *   
     * @throws LogicException
     *   When processor is invalid (wrong return value)
     */
    private function executeProcess(CsrfToken $token, ?array $processors, int $processor): void
    {
        if(null === $processors)
            return;
        
        $getName = function($arg): string {
            return (\is_object($arg)) ? \get_class($arg) : \gettype($arg);
        };
            
        if(isset($processors[$processor]) && null !== $callable = $processors[$processor]) {
            if(!\is_callable($callable))
                throw new LogicException(\sprintf("Processor '%s' MUST be a callable., '%s' given",
                    ($processor === CsrfStrategyInterface::PRE_VALIDATION_PROCESS) ? "PRE_VALIDATION_PROCESS" : "POST_VALIDATION_PROCESS",
                    $getName($callable)));
            if(null !== $returned = \call_user_func($callable, $token, $this)) {
                throw new LogicException(\sprintf("Callable for processor '%s' MUST return 'void' or null. '%s' returned",
                    ($processor === CsrfStrategyInterface::PRE_VALIDATION_PROCESS) ? "PRE_VALIDATION_PROCESS" : "POST_VALIDATION_PROCESS",
                    $getName($returned)));
            }
        }
    }

}
