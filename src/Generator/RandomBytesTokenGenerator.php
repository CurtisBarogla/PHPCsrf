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

namespace Zoe\Component\Csrf\Generator;

use Zoe\Component\Csrf\Exception\InvalidArgumentException;

/**
 * Use simply the native function random_bytes to generate a token
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RandomBytesTokenGenerator implements TokenGeneratorInterface
{
    
    /**
     * Token length
     * 
     * @var int
     */
    private $length;
    
    /**
     * Initialize the generator
     * 
     * @param int $length
     *   Length token
     * 
     * @throws InvalidArgumentException
     *   When given length is invalid
     */
    public function __construct(int $length)
    {
        if($length <= 0) {
            throw new InvalidArgumentException(\sprintf("Length cannot be <= 0. '%d' given", 
                $length));
        }
        
        $this->length = $length;
    }
    
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Csrf\Generator\TokenGeneratorInterface::generate()
     */
    public function generate(): string
    {
        return \base64_encode(
                    \bin2hex(
                        \random_bytes($this->length)
                    )
                );
    }
    
}
