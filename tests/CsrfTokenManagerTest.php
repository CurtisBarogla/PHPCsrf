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

namespace NessTest\Component\Csrf;

use Ness\Component\Csrf\CsrfTokenManager;
use Ness\Component\Csrf\Generator\CsrfTokenGeneratorInterface;
use Ness\Component\Csrf\Storage\CsrfTokenStorageInterface;
use Ness\Component\Csrf\Strategy\CsrfTokenValidationStrategyInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Ness\Component\Csrf\CsrfToken;
use Ness\Component\Csrf\Exception\CsrfTokenNotFoundException;
use Ness\Component\Csrf\Exception\InvalidCsrfTokenException;
use Ness\Component\Csrf\CsrfTokenManagerAwareInterface;

/**
 * CsrfTokenManager testcase
 * 
 * @see \Ness\Component\Csrf\CsrfTokenManager
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class CsrfTokenManagerTest extends CsrfTestCase
{
    
    /**
     * @see \Ness\Component\Csrf\CsrfTokenManager::__construct()
     */
    public function test__constructWithACsrfTokenManagerAwareStrategy(): void
    {
        
        $generator = $this->getMockBuilder(CsrfTokenGeneratorInterface::class)->getMock();
        $storage = $this->getMockBuilder(CsrfTokenStorageInterface::class)->getMock();
        $strategy = $this->getMockBuilder([CsrfTokenValidationStrategyInterface::class, CsrfTokenManagerAwareInterface::class])->getMock();
        $strategy->expects($this->once())->method("setManager");
        
        $manager = new CsrfTokenManager($generator, $storage, $strategy);
    }
    
    /**
     * @see \Ness\Component\Csrf\CsrfTokenManager::generate()
     */
    public function testGenerate(): void
    {
        $tokenFound = new CsrfToken("Foo");
        $tokenGenerated = new CsrfToken("Bar");
        $action = function(MockObject $generator, MockObject $storage, MockObject $strategy) use ($tokenFound, $tokenGenerated): void {
            $storage->expects($this->exactly(2))->method("get")->will($this->onConsecutiveCalls($tokenFound, null));
            $generator->expects($this->once())->method("generate")->will($this->returnValue($tokenGenerated));
            $storage->expects($this->once())->method("store")->with($tokenGenerated)->will($this->returnValue(true));
            $strategy->expects($this->exactly(2))->method("onGeneration");
        };
        $manager = $this->getManager($action);
        
        $this->assertSame($tokenFound, $manager->generate());
        $this->assertSame($tokenGenerated, $manager->generate());
    }
    
    /**
     * @see \Ness\Component\Csrf\CsrfTokenManager::invalidate()
     */
    public function testInvalidate(): void
    {
        $action = function(MockObject $generator, MockObject $storage, MockObject $strategy): void {
            $storage->expects($this->once())->method("delete");
        };
        
        $manager = $this->getManager($action);
        
        $this->assertNull($manager->invalidate());
    }
    
    /**
     * @see \Ness\Component\Csrf\CsrfTokenManager::validate()
     */
    public function testValidate(): void
    {
        $tokenGiven = new CsrfToken("Foo");
        $tokenStored = new CsrfToken("Foo");
        $action = function(MockObject $generator, MockObject $storage, MockObject $strategy) use ($tokenGiven, $tokenStored): void {
            $strategy->expects($this->once())->method("onSubmission");
            $strategy->expects($this->once())->method("postSubmission");
            $storage->expects($this->exactly(2))->method("get")->will($this->returnValue($tokenStored));
        };
        
        $manager = $this->getManager($action);
        
        $this->assertNull($manager->validate($tokenGiven));
    }
    
                    /**_____EXCEPTIONS_____**/
    
    /**
     * @see \Ness\Component\Csrf\CsrfTokenManager::validate()
     */
    public function testExceptionValidateWhenCsrfTokenNotFound(): void
    {
        $this->expectException(CsrfTokenNotFoundException::class);
        $this->expectExceptionMessage("Csrf token not found");
        
        $action = function(MockObject $generator, MockObject $storage, MockObject $strategy): void {
            $storage->expects($this->once())->method("get")->will($this->returnValue(null));
        };
        
        $manager = $this->getManager($action);
        $manager->validate(new CsrfToken("Foo"));
    }
    
    /**
     * @see \Ness\Component\Csrf\CsrfTokenManager::validate()
     */
    public function testExceptionValidateWhenCsrfTokenInvalid(): void
    {
        $this->expectException(InvalidCsrfTokenException::class);
        $this->expectExceptionMessage("Invalid csrf token given");
        
        $tokenGiven = new CsrfToken("Bar");
        $tokenStored = new CsrfToken("Foo");
        $action = function(MockObject $generator, MockObject $storage, MockObject $strategy) use ($tokenGiven, $tokenStored): void {
            $strategy->expects($this->once())->method("onSubmission");
            $strategy->expects($this->never())->method("postSubmission");
            $storage->expects($this->exactly(2))->method("get")->will($this->returnValue($tokenStored));
        };
        
        $manager = $this->getManager($action);
        
        $this->assertNull($manager->validate($tokenGiven));
    }
    
    /**
     * @see \Ness\Component\Csrf\CsrfTokenManager::validate()
     */
    public function testExceptionValidateWhenCsrfTokenInvalidatedByStrategy(): void
    {
        $this->expectException(InvalidCsrfTokenException::class);
        $this->expectExceptionMessage("Invalid csrf token given");
        
        $tokenGiven = new CsrfToken("Bar");
        $tokenStored = new CsrfToken("Foo");
        $action = function(MockObject $generator, MockObject $storage, MockObject $strategy) use ($tokenGiven, $tokenStored): void {
            $strategy->expects($this->once())->method("onSubmission");
            $strategy->expects($this->never())->method("postSubmission");
            $storage->expects($this->exactly(2))->method("get")->will($this->onConsecutiveCalls($tokenStored, null));
        };
        
        $manager = $this->getManager($action);
        
        $this->assertNull($manager->validate($tokenGiven));
    }
    
    /**
     * Get an initialized CsrfTokenManager
     * 
     * @param \Closure $action
     *   Action done on the generator, storage and strategy
     * 
     * @return CsrfTokenManager
     *   Initialized csrf token manager
     */
    private function getManager(?\Closure $action = null): CsrfTokenManager
    {
        $generator = $this->getMockBuilder(CsrfTokenGeneratorInterface::class)->getMock();
        $storage = $this->getMockBuilder(CsrfTokenStorageInterface::class)->getMock();
        $strategy = $this->getMockBuilder(CsrfTokenValidationStrategyInterface::class)->getMock();
        
        if(null !== $action)
            $action->call($this, $generator, $storage, $strategy);
        
        return new CsrfTokenManager($generator, $storage, $strategy);
    }
    
}
