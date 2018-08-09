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
 * Generate Csrf token
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface CsrfTokenGeneratorInterface
{
    
    /**
     * Generate a Csrf token
     * 
     * @return CsrfToken
     *   Csrf token
     */
    public function generate(): CsrfToken;
    
}
