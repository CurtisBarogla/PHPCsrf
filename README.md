# CSRF Component

This library provides a simple way to generate and validate csrf token.

0. [How to install](#0-installing-the-component)
1. [Why ?](#1-why)
2. [How to use](#2-how-to-use)
3. [CSRF Token](#3-csrf-token)
4. [Generate CSRF token](#4-generate-csrf-token)
5. [Store CSRF token](#5-store-csrf-token)
6. [Validation strategy](#6-validation-strategy)
7. [Csrf token manager](#7-csrf-token-manager)
8. [Contributing](#8-contributing)
9. [License](#9-license)

## 0. Installing the component

Csrf library can be installed via composer

~~~bash
$ composer require ness/csrf
~~~

## 1. Why ?

This library allows you simply to check if the user who made the request is really who he is. For a simple use case see : [simple use case](#2-how-to-use).

This library allows you natively to interact with the csrf token through all its validation stages via a set of strategies. See [strategy](#6-validation-strategy).

This library is also **fully unit tested**.

## 2. How to use

This library allows you through a CsrfTokenManager to generate and validate CsrfToken.

The CsrfTokenManager requires you just to provide some settings : 
- a [generator](#4-generate-csrf-token),
- a [store](#5-store-csrf-token),
- a [strategy](#6-validation-strategy).

Let's see a simple example which use the native session mechanism of PHP, a basic generator and a strategy persisting the csrf token during the lifetime of the user's session in a naive, of course very secure (◔_◔), scenario :

~~~php
$generator = new RandomByteCsrfTokenGenerator(64); // will generate a 64 length csrf token
$store = new NativeSessionCsrfTokenStorage(); // requires session enabled !
$strategy = new PerSessionCsrfTokenValidationStrategy(); // csrf token is persisted for the session duration

$manager = new CsrfTokenManager($generator, $store, $strategy);
~~~

~~~html
<!DOCTYPE>
<html>
    <body>
        <h1>What to do with the world today ? </h1>
        <form action="yhwh.php" method="POST">
            <input type="radio" name="whattodo_whattodo" id="destroy" value="destroy"> 
            <label for="destroy">No life will be persisted ! [DANGER ZONE]</label> <br />
            
            <input type="radio" name="whattodo_whattodo" id="restore" value="restore"> 
            <label for="restore">RESET THIS !</label> <br />
            
            <input type="radio" name="whattodo_whattodo" id="nothing" value="nothing"> 
            <label for="nothing">Nothing...</label> <br />
            
            <input type="hidden" name="csrf_token" value="<?= $manager->generate() ?>" />
            
            <button>ACT !</button>
        </form>
    </body>
</html>
~~~


~~~php
// yhwh.php
if($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $manager->validate(new CsrfToken($_POST["csrf_token"] ?? null));
        update_world($_POST["whattodo_whattodo"]); 
        // nicely done !
    } catch(InvalidCsrfTokenException|CsrfTokenNotFoundException|TypeError $e) {
        // someone tried to modify the world ! bad
        die("Invalid Csrf Token");
    }
} else {
    die("... you tried ?");
}
~~~


## 3. CSRF token

CsrfToken class is a simple way to share a csrf token representation among all components responsible of its validation.

It consist of two simples accesseurs, first one representing an arbitrary value initialized into the constructor and second one representing, as a DateTimeImmutable, when it was generated.

Let's see how to initialized a csrf token

~~~php
$token = new CsrfToken("Foo"); // very secure :p
echo $token->getValue() // will output Foo
$token->generatedAt() // provide when the token has been generated as a DateTimeImmutable

// CsrfToken implements __toString(), therefore can be outputted simply by "echoing" it
echo $token // will output Foo
~~~

## 4. Generate CSRF token

This library provides a simple interface (Ness\Component\CsrfTokenGeneratorInterface) allowing you to generate a CSRF token for later usage.

~~~php
$generator = new CsrfTokenGeneratorImplementation();
// simply generate a csrf token initialized with a "secure" value depending of the implementation at the current timestamp
$generator->generate();
~~~

This library comes with two basics implementations

### 4.1 RandomByteCsrfTokenGenerator

RandomByteCsrfTokenGenerator is the simpliest way to get a secure (csrf token speaking) value. Token length is setted by default to 32 characters and can be changed via the constructor.

Setting a length token value **lower** than 2 will result an Error at generation time. **No verification are done** whatsoever on this parameter into the constructor.

### 4.2 ApacheUniqueIdCsrfTokenGenerator

This implementation is based on an Apache header and needs the apache mod "mod\_unique_id" enabled.

By default, this implementation is using the $\_SERVER variable provided by PHP and the key UNIQUE\_ID which the value is unique among all requests. <br />
This behaviour can be altered simply by overriding the getHeader() method.

_e.g (In a Symfony environement)_ : <br />
Symfony provides its own layer for accessing the request, so instead of using the barbaric $\_SERVER variable, we can imagine using the request stack handled by its kernel.

Obviously, for this configuration to work, the current request still must be accessible and not be popped out by the Symfony kernel (generally when the response has been generated).

~~~php
/**
 * Given as example
*/
class SymfonyApacheUniqueIdCsrfTokenGenerator extends ApacheUniqueIdCsrfTokenGenerator
{
    
    /**
     * Symfony request stack
     * 
     * @var RequestStack
     */
    private $stack;
    
    /**
     * Overriding getHeader() using Symfony request
     * 
     * @param RequestStack $stack
     *   Symfony request stack
     */
    public function __construct(RequestStack $stack)
    {
        $this->stack = $stack;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Csrf\Generator\ApacheUniqueIdCsrfTokenGenerator::getHeader()
     */
    protected function getHeader(): string
    {
        return $this->stack->getCurrentRequest()->server->get("UNIQUE_ID");
    }
    
}
~~~

## 5. Store CSRF token

A store is responsible to store an already generated csrf token for later usages.

It consists in a simple interface providing storing and access methods to a csrf token.

~~~php
$store = new CsrfTokenStoreImplementation(); 
// storing a csrf token
$store->store(new CsrfToken("Foo"));
// get a csrf token (will return null if no csrf token has been found for the current call)
$store->get();
// delete the token from the store (returns true or false)
$store->delete();
~~~

### 5.1 NativeSessionCsrfTokenStorage

This library comes with a basic implementation of the CsrfTokenStorageInterface interface. <br />
Use the native session using $\_SESSION variable for storing the csrf token through the request.

Session mechanism MUST be active or a LogicException will be thrown at construct time.

## 6. Validation strategy

Validation strategy allows you to manipulate the state of a csrf token before and during its validation process. <br />
It consists of a simple implementation of CsrfTokenValidateStrategyInterface.

~~~php
$strategy = new CsrfTokenValidationStrategyImplementation();
// is executed during the generation of a csrf token
$strategy->onGeneration();
// is executed before the csrf token is submitted to the validation process
$strategy->onSubmission(CsrfToken $token);
// is executed after the csrf token has been consummed by the validation process
$strategy->postSubmission(CsrfToken $token);
~~~

This library provides you 3 implemented validation strategies.

### 6.1 PerSessionCsrfTokenValidationStrategy

The most **simple** validation strategy. It let the session mechanism handle the invalidation of an already generated csrf token. <br />
In other words, the csrf token is valid during the whole session. 

### 6.2 UniqueCsrfTokenValidationStrategy

UniqueCsrfTokenValidationStategy allows you to invalidate a token in two differents ways, depending of the refresh parameter setted at construct time. <br />
This stategy interacts with a CsrfTokenManagerInterface for invalidating the csrf token

First way, and default way (refresh setted to false), the csrf token is still valid during the session lifetime until it was consumed by the CsrfTokenManager.

Second way, the csrf token is always unique no matter what.

~~~php
// setted first way
$strategy = new UniqueCsrfTokenValidationStrategy();
// setted second way
$strategy = new UniqueCsrfTokenValidationStrategy(true); 
~~~

### 6.3 TimedCsrfTokenValidationStrategy

TimedCsrfTokenValidationStrategy generate a unique csrf token for each request and based on its generation time invalidate it.

This stategy interacts with a CsrfTokenManagerInterface for invalidating the csrf token

A simple example : 

~~~php
$validInterval = new DateInterval("PT10M");
$strategy = new TimedCsrfTokenValidationStrategy($validInterval);
// and that's it
// given this configuration, the token is valid for only 10 minutes
~~~

## 7. Csrf token manager

CsrfTokenManagerInterface is the main component responsible to provide, invalidate and validate csrf token.

### 7.1 General

Let's describe how the interface is handling the csrf token

#### 7.1.1 Getting a Csrf token

No matter what, the manager MUST provide an instance of CsrfToken. <br />
This token can be newly generated or fetched from a store mechanism (session...).

~~~php
$manager = new CsrfManagerImplementation();
// provide a newly generated csrf token
$tokenNewlyGenerated = $manager->generate();
// make an another call to generate() method SHOULD/MUST return the exact same csrf token previously generated
$tokenFetched = $manager->generate();

// in other words, this should be true
$tokenNewlyGenerated === $tokenFetched;
~~~

If an error happen during the generation process, a CriticalCsrfException is thrown

#### 7.1.2 Invalidate a Csrf token

Remove an already generated csrf token

If this csrf token cannot be removed, a CriticalCsrfException is thrown

#### 7.1.3 Validate a Csrf token

Simply validate a previously generated (by generate() method) over a given csrf token.

~~~php
$manager = new CsrfManagerImplementation();
$manager->generate(); // let's assume the generated token has for value Foo
$manager->validate(new CsrfToken("Foo"));

// no exception thrown whatsoever, the given csrf token is considered valid
~~~

The validation process throws two kinds of exception depending of the context.

A CsrfTokenNotFoundException if no token has been previously generated or a InvalidCsrfTokenException if a token has been found but does not match the given one.

~~~php
$manager = new CsrfManagerImplementation();

// will throw a CsrfTokenNotFoundException
$manager->validate(new CsrfToken("Foo"));

$manager = new CsrfManagerImplementation();

// will throw a InvalidCsrfTokenException
$manager->generate(); // let's assume the generated token has for value Foo
$manager->validate(new CsrfToken("Bar"));
~~~

### 7.2 Implementation

This library provides an implementation of CsrfTokenManagerInterface based on the components described above.

Let's talk about the validation strategy and how it is handled by the manager.

onGeneration() method is called every time the generate() is called. <br />
onSubmission() method is called when a token has been found into the store and allows you to perform a verification on it. <br />
postSubmission() method is called when the given token corresponds the stored one and allows you to modify its state.

Let's see how to instantiate it and use it.

~~~php
$generator = new CsrfTokenGeneratorImplementation();
$store = new CsrfTokenStorageImplementation();
$strategy = new CsrfTokenValidationStrategyImplementation();

$manager = new CsrfTokenManager($generator, $store, $strategy);
// we have now an instance of CsrfTokenManager()

// getting a csrf token
$manager->generate(); // assume token has for value Foo;
// at this state, a new csrf token is provided and added to the setted store

// now we can simply validate the generated token
try {
    $manager->validate(new CsrfToken("Foo"));
} catch(InvalidCsrfTokenException|CsrfTokenNotFoundException $e) {
    // handle when csrf token is invalid or not previously generated
}
~~~

## 8. Contributing

Found something **wrong** (nothing is perfect) ? Wanna talk or participate ? <br />
Issue the case or contact me at [curtis_barogla@outlook.fr](mailto:curtis_barogla@outlook.fr)

## 9. License

The Ness Csrf component is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
