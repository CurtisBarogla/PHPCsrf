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

namespace NessTest\Component\Csrf\Generator;

use NessTest\Component\Csrf\CsrfTestCase;
use Ness\Component\Csrf\Generator\ApacheUniqueIdCsrfTokenGenerator;
use Ness\Component\Csrf\CsrfToken;

/**
 * ApacheUniqueIdCsrfTokenGenerator testcase
 * 
 * @see \Ness\Component\Csrf\Generator\ApacheUniqueIdCsrfTokenGenerator
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class ApacheUniqueIdCsrfTokenGeneratorTest extends CsrfTestCase
{
    
    /**
     * @see \Ness\Component\Csrf\Generator\ApacheUniqueIdCsrfTokenGenerator::generate()
     */
    public function testGenerate(): void
    {
        // bad, i know !
        $_SERVER["UNIQUE_ID"] = \Closure::bind(function(int $length): string {
            $uniq = "";
            $alpha = \array_merge(\range('a', 'z'), \range('A', 'Z'), \range('0', '9'));
            for ($i = 0; $i < $length; $i++) {
                $uniq .= $alpha[\random_int(0, \count($alpha) - 1)];
            }
            
            return $uniq;
        }, null)(64); 
        
        $generator = new ApacheUniqueIdCsrfTokenGenerator();
        
        $this->assertInstanceOf(CsrfToken::class, $generator->generate());
    }
    
}
