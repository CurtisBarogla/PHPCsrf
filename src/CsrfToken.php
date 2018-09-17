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
 * Represent a Csrf token
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
final class CsrfToken
{
    
    /**
     * Token value
     * 
     * @var string
     */
    private $value;
    
    /**
     * When token has been generated
     * 
     * @var \DateTime
     */
    private $generatedAt;
    
    /**
     * Initialize a Csrf token
     * 
     * @param string $value
     *   Csrf token value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
        $this->generatedAt = new \DateTimeImmutable();
    }
    
    /**
     * Get csrf token value
     * 
     * @return string
     *   Csrf token value
     */
    public function get(): string
    {
        return $this->value; 
    }
    
    /**
     * Get when the token has been generated
     * 
     * @return \DateTime
     *   When the token has been generated
     */
    public function generatedAt(): \DateTimeImmutable
    {
        return $this->generatedAt;
    }
    
    /**
     * {@inheritdoc}
     * @see \Ness\Component\Csrf\CsrfToken::__toString()
     */
    public function __toString(): string
    {
        return $this->value;
    }
    
}
