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
 * Manage Csrf token generation and validation
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class Csrf
{
    
    /**
     * Identifer to store a csrf token
     * 
     * @var string
     */
    public const CSRF_IDENTIFER = "CSRF-TOKEN";
    

    
}
