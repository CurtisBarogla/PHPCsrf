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

namespace ZoeTest\Component\Csrf\Generator;

use PHPUnit\Framework\TestCase;
use Zoe\Component\Csrf\Generator\RandomBytesTokenGenerator;
use Zoe\Component\Csrf\Exception\InvalidArgumentException;

/**
 * RandomBytesTokenGenerator testcase 
 * 
 * @see \Zoe\Component\Csrf\Generator\RandomBytesTokenGenerator
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RandomBytesTokenGeneratorTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Csrf\Generator\RandomBytesTokenGenerator::generate()
     */
    public function testGenerate(): void
    {
        $generator = new RandomBytesTokenGenerator(8);
        
        // not really pertinent... but i like it ><
        $stored = null;
        for ($i = 0; $i < 100; $i++) {
            $stored = $generator->generate();
            $this->assertNotSame($stored, $generator->generate());
            $this->assertRegExp("#[A-Za-z0-9]#", $generator->generate());
            $stored = null;
        }
        
    }
    
                    /**_____EXCEPTIONS_____**/
    
    /**
     * @see \Zoe\Component\Csrf\Generator\RandomBytesTokenGenerator::__construct()
     */
    public function testExceptionWhenLengthIsInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Length cannot be <= 0. '0' given");
        
        $generator = new RandomBytesTokenGenerator(0);
    }
    
}
