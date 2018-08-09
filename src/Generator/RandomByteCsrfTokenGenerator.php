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

namespace Ness\Component\Csrf\Generator;

use Ness\Component\Csrf\CsrfToken;

/**
 * Use simply intern function random_bytes to generate a csrf token
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RandomByteCsrfTokenGenerator implements CsrfTokenGeneratorInterface
{
    
    /**
     * Token value length
     * 
     * @var int
     */
    private $length;
    
    /**
     * Initialize generator
     * 
     * @param int $length
     *   Token value length. By default 32
     */
    public function __construct(int $length = 32)
    {
        $this->length = $length >> 1;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Generator\CsrfTokenGeneratorInterface::generate()
     */
    public function generate(): CsrfToken
    {
        return new CsrfToken(\bin2hex(\random_bytes($this->length)));
    }

}
