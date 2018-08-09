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
use Ness\Component\Csrf\Generator\RandomByteCsrfTokenGenerator;
use Ness\Component\Csrf\CsrfToken;

/**
 * RandomByteCsrfTokenGenerator testcase
 * 
 * @see \Ness\Component\Csrf\Generator\RandomByteCsrfTokenGenerator
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RandomByteCsrfTokenGeneratorTest extends CsrfTestCase
{
    
    /**
     * @see \Ness\Component\Csrf\Generator\RandomByteCsrfTokenGenerator::generate()
     */
    public function testGenerate(): void
    {
        $generator = new RandomByteCsrfTokenGenerator();
        
        $token = $generator->generate();
        
        $this->assertInstanceOf(CsrfToken::class, $token);
        $this->assertSame(32, \strlen($token->get()));
        
        $generator = new RandomByteCsrfTokenGenerator(64);
        
        $token = $generator->generate();
        
        $this->assertSame(64, \strlen($token->get()));
    }
    
}
