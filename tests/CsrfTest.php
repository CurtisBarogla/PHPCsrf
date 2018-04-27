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

namespace ZoeTest\Component\Csrf;

use PHPUnit\Framework\TestCase;
use Zoe\Component\Csrf\Csrf;
use Zoe\Component\Csrf\CsrfInterface;
use Zoe\Component\Csrf\CsrfToken;
use Zoe\Component\Csrf\Exception\InvalidCsrfTokenException;
use Zoe\Component\Csrf\Generator\TokenGeneratorInterface;
use Zoe\Component\Csrf\Storage\CsrfTokenStorageInterface;
use Zoe\Component\Csrf\Strategy\CsrfStrategyInterface;

/**
 * Csrf testcase
 * 
 * @see \Zoe\Component\Csrf\Csrf
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class CsrfTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Csrf\Csrf
     */
    public function testInterface(): void
    {
        $csrf = new Csrf($this->getMockedCsrfTokenStorage(), $this->getMockedTokenGenerator(), $this->getMockedCsrfStrategy());
        
        $this->assertInstanceOf(CsrfInterface::class, $csrf);
    }
    
    /**
     * @see \Zoe\Component\Csrf\Csrf::getTokenKey()
     */
    public function testGetTokenKey(): void
    {
        $csrf = new Csrf($this->getMockedCsrfTokenStorage(), $this->getMockedTokenGenerator(), $this->getMockedCsrfStrategy());
        
        $this->assertSame("_csrf", $csrf->getTokenKey());
        
        $csrf = new Csrf($this->getMockedCsrfTokenStorage(), $this->getMockedTokenGenerator(), $this->getMockedCsrfStrategy(), "_CSRF");
        
        $this->assertSame("_CSRF", $csrf->getTokenKey());
    }
    
    /**
     * @see \Zoe\Component\Csrf\Csrf::generate()
     */
    public function testGenerateWhenStorageHasAToken(): void
    {
        $token = new CsrfToken("foo");
        $storage = $this->getMockedCsrfTokenStorage();
        $storage->method("get")->with(CsrfInterface::CSRF_IDENTIFER)->will($this->returnValue($token));
        
        $csrf = new Csrf($storage, $this->getMockedTokenGenerator(), $this->getMockedCsrfStrategy());
        
        $this->assertSame($token, $csrf->generate());
    }
    
    /**
     * @see \Zoe\Component\Csrf\Csrf::generate()
     */
    public function testGenerateWhenStorageHasNoToken(): void
    {
        $token = new CsrfToken("foo");
        $exception = new InvalidCsrfTokenException();
        $storage = $this->getMockedCsrfTokenStorage();
        $generator = $this->getMockedTokenGenerator("foo");
        $storage->method("get")->with(CsrfInterface::CSRF_IDENTIFER)->will($this->throwException($exception));
        $storage->method("add")->with(CsrfInterface::CSRF_IDENTIFER, $token)->will($this->returnValue(null));
        
        $csrf = new Csrf($storage, $generator, $this->getMockedCsrfStrategy());
        
        $this->assertEquals($token, $csrf->generate());
        $this->assertSame("foo", $csrf->generate()->get());
    }
    
    /**
     * @see \Zoe\Component\Csrf\Csrf::refresh()
     */
    public function testRefresh(): void
    {
        $storage = $this->getMockedCsrfTokenStorage();
        $generator = $this->getMockedTokenGenerator("foo");
        $storage->method("delete")->with(CsrfInterface::CSRF_IDENTIFER)->will($this->returnValue(null));
        $storage->method("add")->with(CsrfInterface::CSRF_IDENTIFER, new CsrfToken("foo"))->will($this->returnValue(null));
        
        $csrf = new Csrf($storage, $generator, $this->getMockedCsrfStrategy());
        
        $this->assertNull($csrf->refresh());
    }
    
    /**
     * Get an instance of CsrfInterface with mocked preset value
     * 
     * @param string $tokenValue
     *   Value of the csrf token returned by the storage
     * @param mixed $preProcess
     *   Pre-process callable (or other type for exception testing purpose)
     * @param mixed $postProcess
     *   Post-process callable (or other type for exception testing purpose)
     * 
     * @return CsrfInterface
     *   Csrf instance with mocked values setted
     */
    private function doGetCsrfForValidate(
        string $tokenValue,
        $preProcess = null,
        $postProcess = null): CsrfInterface
    {
        $token = new CsrfToken($tokenValue);
        $storage = $this->getMockedCsrfTokenStorage();
        $generator = $this->getMockedTokenGenerator("foo");
        $strategy = $this->getMockedCsrfStrategy();
        if(null === $preProcess && null === $postProcess) {
            $returnValue = null;
        } else {
            $returnValue = [
                CsrfStrategyInterface::PRE_VALIDATION_PROCESS   =>  $preProcess,
                CsrfStrategyInterface::POST_VALIDATION_PROCESS  =>  $postProcess
            ];
        }
        $strategy->method("process")->will($this->returnValue($returnValue));
        $storage->method("get")->with(CsrfInterface::CSRF_IDENTIFER)->will($this->returnValue($token));
        
        $csrf = new Csrf($storage, $generator, $strategy);
        
        return $csrf;
    }
    
    /**
     * @see \Zoe\Component\Csrf\Csrf::validate()
     */
    public function testValidateOnValidToken(): void
    {   
        $this->expectOutputString("PreProcess called - PostProcess called");
        $compareToken = new CsrfToken("foo");
        $csrf = $this->doGetCsrfForValidate("foo", function(CsrfToken $token, Csrf $csrf) {
            echo "PreProcess called - ";
        }, function(CsrfToken $token, Csrf $csrf) {
            echo "PostProcess called";
        });
        
        $this->assertNull($csrf->validate($compareToken));
    }
    
    /**
     * @see \Zoe\Component\Csrf\Csrf::validate()
     */
    public function testValidateOnInvalidToken(): void
    {
        $this->expectException(InvalidCsrfTokenException::class);
        $this->expectExceptionMessage("Invalid csrf token");
        
        $comparedToken = new CsrfToken("bar");
        $csrf = $this->doGetCsrfForValidate("foo");
        
        $csrf->validate($comparedToken);
    }
    
    /**
     * Get a mocked token generator
     * 
     * @param string|null $token
     *   Token returned by the generate method or null to set it manually
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     *   Mocked generator token
     */
    private function getMockedTokenGenerator(?string $token = null): \PHPUnit_Framework_MockObject_MockObject
    {
        $mock = $this->getMockBuilder(TokenGeneratorInterface::class)->setMethods(["generate"])->getMock();
        
        if(null !== $token)
            $mock->method("generate")->will($this->returnValue($token));
        
        return $mock;
    }
    
    /**
     * Get a mocked csrf token storage
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     *   Mocked csrf token storage
     */
    private function getMockedCsrfTokenStorage(): \PHPUnit_Framework_MockObject_MockObject
    {
        $methods = \array_map(function(\ReflectionMethod $method): string {
            return $method->getName(); 
        }, (new \ReflectionClass(CsrfTokenStorageInterface::class))->getMethods());
        
        $mock = $this->getMockBuilder(CsrfTokenStorageInterface::class)->setMethods($methods)->getMock();
        
        return $mock;
    }
    
    /**
     * Get a mocked csrf strategy
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     *   Mocked csrf strategy
     */
    private function getMockedCsrfStrategy(): \PHPUnit_Framework_MockObject_MockObject
    {
        $mock = $this->getMockBuilder(CsrfStrategyInterface::class)->setMethods(["process"])->getMock();
        
        return $mock;
    }
    
}
