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

namespace ZoeTest\Component\Csrf\Storage;

use PHPUnit\Framework\TestCase;
use Zoe\Component\Csrf\Storage\NativeSessionStorage;
use Zoe\Component\Csrf\Storage\CsrfTokenStorageInterface;
use Zoe\Component\Csrf\CsrfToken;
use Zoe\Component\Csrf\Exception\InvalidCsrfTokenException;
use Zoe\Component\Internal\ReflectionTrait;

/**
 * NativeSessionStorage testcase
 * 
 * @see \Zoe\Component\Csrf\Storage\NativeSessionStorage
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NativeSessionStorageTest extends TestCase
{
    
    use ReflectionTrait;
    
    /**
     * @see \Zoe\Component\Csrf\Storage\NativeSessionStorage
     */
    public function testInterface(): void
    {
        $store = new NativeSessionStorage();
        
        $this->assertInstanceOf(CsrfTokenStorageInterface::class, $store);
    }
    
    /**
     * @see \Zoe\Component\Csrf\Storage\NativeSessionStorage::add()
     */
    public function testAdd(): void
    {
        $store = new NativeSessionStorage();
        $reflection = new \ReflectionClass($store);
        $this->reflection_injectNewValueIntoProperty($store, $reflection, "session", []);
        
        $token = new CsrfToken("bar");
        
        $this->assertNull($store->add("foo", $token));
        $this->assertCount(1, $this->reflection_getPropertyValue($store, $reflection, "session"));
    }
    
    /**
     * @see \Zoe\Component\Csrf\Storage\NativeSessionStorage::get()
     */
    public function testGet(): void
    {
        $store = new NativeSessionStorage();
        $reflection = new \ReflectionClass($store);
        $this->reflection_injectNewValueIntoProperty($store, $reflection, "session", []);
        
        $token = new CsrfToken("bar");
        $store->add("foo", $token);
        
        $this->assertInstanceOf(CsrfToken::class, $store->get("foo"));
    }
    
    /**
     * @see \Zoe\Component\Csrf\Storage\NativeSessionStorage::delete()
     */
    public function testDelete(): void
    {
        $store = new NativeSessionStorage();
        $reflection = new \ReflectionClass($store);
        $this->reflection_injectNewValueIntoProperty($store, $reflection, "session", []);
        
        $token = new CsrfToken("bar");
        $store->add("foo", $token);
        
        $this->assertNull($store->delete("foo"));
        $this->assertEmpty($this->reflection_getPropertyValue($store, $reflection, "session"));
    }
    
                    /**_____EXCEPTION_____**/
    
    /**
     * @see \Zoe\Component\Csrf\Storage\NativeSessionStorage::get()
     */
    public function testExceptionWhenTryingToGetANonExistingCsrfToken(): void
    {
        $this->expectException(InvalidCsrfTokenException::class);
        $this->expectExceptionMessage("No csrf token found for this identifier 'foo'");
        
        $store = new NativeSessionStorage();
        $store->get("foo");
    }
    
    /**
     * @see \Zoe\Component\Csrf\Storage\NativeSessionStorage::delete()
     */
    public function testExceptionWhenTryingToDeleteANonExistingCsrfToken(): void
    {
        $this->expectException(InvalidCsrfTokenException::class);
        $this->expectExceptionMessage("No csrf token found for this identifier 'foo'");
        
        $store = new NativeSessionStorage();
        $store->delete("foo");
    }
    
}
