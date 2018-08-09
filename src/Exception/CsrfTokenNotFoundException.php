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
 * When a csrf token is not found
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class CsrfTokenNotFoundException extends \Exception
{
    //
}
