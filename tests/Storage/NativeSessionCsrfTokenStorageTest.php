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

namespace Ness\Component\Csrf\Storage {
  
    global $status;
    
    /**
     * For testing purpose
     * 
     * @return int
     *   Session status
     */
    function session_status(): int
    {
        global $status;
        
        return $status;
    }
    
    /**
     * Initialize session status 
     * 
     * @param int $session
     *   Status returns by session_status
     */
    function init(int $session): void
    {
        global $status;
        
        $status = $session;
    }
    
};

namespace NessTest\Component\Csrf\Storage {

    use NessTest\Component\Csrf\CsrfTestCase;
    use Ness\Component\Csrf\Storage\NativeSessionCsrfTokenStorage;
    use function Ness\Component\Csrf\Storage\init;
    use Ness\Component\Csrf\CsrfToken;
                                                    
    /**
     * NativeSessionCsrfTokenStorage testcase
     * 
     * @see \Ness\Component\Csrf\Storage\NativeSessionCsrfTokenStorage
     * 
     * @author CurtisBarogla <curtis_barogla@outlook.fr>
     *
     */
    class NativeSessionCsrfTokenStorageTest extends CsrfTestCase
    {
        
        /**
         * @see \Ness\Component\Csrf\Storage\NativeSessionCsrfTokenStorage::store()
         */
        public function testStore(): void 
        {
            init(PHP_SESSION_ACTIVE);
            $storage = new NativeSessionCsrfTokenStorage();
            $this->injectSession($storage);
            
            $this->assertTrue($storage->store(new CsrfToken("Foo")));
        }
        
        /**
         * @see \Ness\Component\Csrf\Storage\NativeSessionCsrfTokenStorage::get()
         */
        public function testGet(): void
        {
            init(PHP_SESSION_ACTIVE);
            $token = new CsrfToken("Foo");
            
            $storage = new NativeSessionCsrfTokenStorage();
            $this->injectSession($storage);
            
            $storage->store($token);
            
            $this->assertSame($token, $storage->get());
        }
        
        /**
         * @see \Ness\Component\Csrf\Storage\NativeSessionCsrfTokenStorage::delete()
         */
        public function testDelete(): void
        {
            init(PHP_SESSION_ACTIVE);
            $token = new CsrfToken("Foo");
            
            $storage = new NativeSessionCsrfTokenStorage();
            $this->injectSession($storage);
            $storage->store($token);
            
            $this->assertTrue($storage->delete());
            $this->assertTrue($storage->delete());
        }
        
                        /**_____EXCEPTIONS_____**/
        
        /**
         * @see \Ness\Component\Csrf\Storage\NativeSessionCsrfTokenStorage::__construct()
         */
        public function testException__constructWhenSessionNotActive(): void
        {
            $this->expectException(\LogicException::class);
            $this->expectExceptionMessage("Cannot use NativeSessionCsrfTokenStorage as csrf token storage as session is not active for the current request");
            
            init(PHP_SESSION_NONE);
            
            $storage = new NativeSessionCsrfTokenStorage();
        }
        
        /**
         * Inject an array as session into a storage
         * 
         * @param NativeSessionCsrfTokenStorage $storage
         *   Storage currently tested
         */
        private function injectSession(NativeSessionCsrfTokenStorage $storage): void
        {
            $reflection = new \ReflectionClass($storage);
            $session = [];
            $property = $reflection->getProperty("session");
            $property->setAccessible(true);
            $property->setValue($storage, $session);
        }
        
    }
}
