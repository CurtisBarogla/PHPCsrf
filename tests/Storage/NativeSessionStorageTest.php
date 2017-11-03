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
        $this->mockNativeSessioAndSetItIntoTheStore($store, $reflection);
        
        $token = new CsrfToken("bar");
        $this->assertNull($store->add("foo", $token));
        
        $this->assertNotEmpty($this->getMockedSessionFromStorage($store, $reflection));
    }
    
    /**
     * @see \Zoe\Component\Csrf\Storage\NativeSessionStorage::get()
     */
    public function testGet(): void
    {
        $store = new NativeSessionStorage();
        $reflection = new \ReflectionClass($store);
        $this->mockNativeSessioAndSetItIntoTheStore($store, $reflection);
        
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
        $this->mockNativeSessioAndSetItIntoTheStore($store, $reflection);
        
        $token = new CsrfToken("bar");
        $store->add("foo", $token);
        
        $this->assertNull($store->delete("foo"));
        $this->assertEmpty($this->getMockedSessionFromStorage($store, $reflection));
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
    
    
    /**
     * Use a simple array as session and set it into the session property of the store
     * 
     * @param NativeSessionStorage $store
     *   Store instance
     * @param \ReflectionClass $reflection
     *   Reflection with store setted
     */
    private function mockNativeSessioAndSetItIntoTheStore(NativeSessionStorage $store, \ReflectionClass $reflection): void
    {
        $session = [];
        $property = $reflection->getProperty("session");
        $property->setAccessible(true);
        $property->setValue($store, $session);
    }
    
    /**
     * Get the mocked session setted by the reflectivity
     * 
     * @param NativeSessionStorage $store
     *   Store instance
     * @param \ReflectionClass $reflection
     *   Reflection with store setted
     * 
     * @return array
     *   Mocked session array property value
     */
    private function getMockedSessionFromStorage(NativeSessionStorage $store, \ReflectionClass $reflection): array
    {
        $property = $reflection->getProperty("session");
        $property->setAccessible(true);
        
        return $property->getValue($store);
    }
    
}
