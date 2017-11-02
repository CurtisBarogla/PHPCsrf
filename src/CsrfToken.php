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

/**
 * Csrf token
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class CsrfToken
{
    
    /**
     * Token value
     * 
     * @var string
     */
    private $value;
    
    /**
     * Timestamp
     * 
     * @var int
     */
    private $timestamp;
    
    /**
     * Initialize a csrf token
     * 
     * @param string $value
     *   Token value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
        $this->timestamp = \time();
    }
    
    /**
     * Get the token value
     * 
     * @return string
     *   Token value
     */
    public function get(): string
    {
        return $this->value;
    }
    
    /**
     * Get the timestamp creation
     * 
     * @return int
     *   Timestamp creation
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }
    
    /**
     * Output csrf token value
     * 
     * @return string
     *   Token value
     */
    public function __toString(): string
    {
        return $this->value;
    }
    
}
