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

/**
 * Generate a token value to be store into a Csrf token
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface TokenGeneratorInterface
{
    
    /**
     * Generate a string to be stored into a Csrf token
     * 
     * @return string
     *   Token value
     */
    public function generate(): string;
    
}
