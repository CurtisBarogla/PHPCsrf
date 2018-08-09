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

namespace Ness\Component\Csrf\Exception;

/**
 * When an invalid csrf token is provided
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class InvalidCsrfTokenException extends \Exception
{
    //
}
