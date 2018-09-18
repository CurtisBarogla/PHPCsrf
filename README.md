# CSRF Component

This library provides a simple way to generate and validate csrf token.

0. [How to install](#0-installing-the-component)
1. [CSRF Token](#1-csrf-token)
2. [Generate CSRF token](#2-generate-csrf-token)
3. [Store CSRF token](#3-store-csrf-token)
4. [Validation strategy](#4-validation-strategy)
5. [Csrf token manager](#5-csrf-token-manager)
6. [Contributing](#6-contributing)
7. [License](#7-license)

## 0. Installing the component

Csrf library can be installed via composer

~~~bash
$ composer require ness/csrf
~~~

## 1. CSRF token

CsrfToken class is a simple way to share a csrf token representation among all components responsible of its validation.

It consist of two simples accesseurs, first one representing an arbitrary value initialized into the constructor and second one representing, as a DateTimeImmutable, when it was generated.

Let's see how to initialized a csrf token

~~~php
<?php
$token = new CsrfToken("Foo"); // very secure :p
echo $token->getValue() // will output Foo
$token->generatedAt() // provide when the token has been generated as a DateTimeImmutable

// CsrfToken implements __toString(), therefore can be outputted simply by "echoing" it
echo $token // will output Foo
~~~

## 2. Generate CSRF token

This library provides a simple interface (Ness\Component\CsrfTokenGeneratorInterface) allowing you to generate a CSRF token for later usage.

~~~php
<?php
$generator = new CsrfTokenGeneratorImplementation();
// simply generate a csrf token initialized with a "secure" value depending of the implementation at the current timestamp
$generator->generate();
~~~

This library comes with two basics implementations

### 2.1 RandomByteCsrfTokenGenerator

RandomByteCsrfTokenGenerator is the simpliest way to get a secure (csrf token speaking) value. Token length is setted by default to 32 characters and can be changed via the constructor.

Setting a length token value **lower** than 2 will result an Error at generation time. **No verification are done** whatsoever on this parameter into the constructor.

### 2.2 ApacheUniqueIdCsrfTokenGenerator

This implementation is based on an Apache header and needs the apache mod "mod\_unique_id" enabled.

By default, this implementation is using the $\_SERVER variable provided by PHP and the key UNIQUE\_ID which the value is unique among all requests. <br />
This behaviour can be altered simply by overriding the getHeader() method.

_e.g (In a Symfony environement)_ : <br />
Symfony provides its own layer for accessing the request, so instead of using the barbaric $\_SERVER variable, we can imagine using the request stack handled by its kernel.

Obviously, for this configuration to work, the current request still must be accessible and not be popped out by the Symfony kernel (generally when the response has been generated).

~~~php
<?php

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

## 3. Store CSRF token

A store is responsible to store an already generated csrf token for later usages.

It consists in a simple interface providing storing and access methods to a csrf token.

~~~php
<?php
$store = new CsrfTokenStoreImplementation(); 
// storing a csrf token
$store->store(new CsrfToken("Foo"));
// get a csrf token (will return null if no csrf token has been found for the current call))
$store->get();
// delete the token from the store (returns true or false)
$store->delete();
~~~

### 3.1 NativeSessionCsrfTokenStorage

This library comes with a basic implementation of the CsrfTokenStorageInterface interface. <br />
Use the native session using $_SESSION variable for storing the csrf token through the request.

Session mechanism MUST be active or a LogicException will be thrown at construct time.

## 4. Validation strategy

Validation strategy allows you to manipulate the state of a csrf token before and during its validation process. <br />
It consists of a simple implementation of CsrfTokenValidateStrategyInterface.

~~~php
<?php
$strategy = new CsrfTokenValidationStrategyImplementation();
// is executed during the generation of a csrf token
$strategy->onGeneration();
// is executed before the csrf token is submitted to the validation process
$strategy->onSubmission(CsrfToken $token);
// is executed after the csrf token has been consummed by the validation process
$strategy->postSubmission(CsrfToken $token);
~~~

This library provides you 3 implemented validation strategies.

### 4.1 PerSessionCsrfTokenValidationStrategy

The most **simple** validation strategy. It let the session mechanism handle the invalidation of an already generated csrf token. <br />
In other words, the csrf token is valid during the whole session. 

### 4.2 UniqueCsrfTokenValidationStrategy

UniqueCsrfTokenValidationStategy allows you to invalidate a token in two differents ways, depending of the refresh parameter setted at construct time. <br />
This stategy interacts with a CsrfTokenManagerInterface for invalidating the csrf token

First way, and default way (refresh setted to false), the csrf token is still valid during the session lifetime until it was consumed by the CsrfTokenManager.

Second way, the csrf token is always unique no matter what.

~~~php
<?php
// setted first way
$strategy = new UniqueCsrfTokenValidationStrategy();
// setted second way
$strategy = new UniqueCsrfTokenValidationStrategy(true); 
~~~

### 4.3 TimedCsrfTokenValidationStrategy

TimedCsrfTokenValidationStrategy generate a unique csrf token for each request and based on its generation time invalidate it.

This stategy interacts with a CsrfTokenManagerInterface for invalidating the csrf token

A simple example : 

~~~php
<?php
$validInterval = new DateInterval("PT10M");
$strategy = new TimedCsrfTokenValidationStrategy($validInterval);
// and that's it
// given this configuration, the token is valid for only 10 minutes
~~~

## 5. Csrf token manager

CsrfTokenManagerInterface is the main component responsible to provide, invalidate and validate csrf token.

### 5.1 General

Let's describe how the interface is handling the csrf token

#### 5.1.1 Getting a Csrf token

No matter what, the manager MUST provide an instance of CsrfToken. <br />
This token can be newly generated or fetched from a store mechanism (session...).

~~~php
<?php
$manager = new CsrfManagerImplementation();
// provide a newly generated csrf token
$tokenNewlyGenerated = $manager->generate();
// make an another call to generate() method SHOULD/MUST return the exact same csrf token previously generated
$tokenFetched = $manager->generate();

// in other words, this should be true
$tokenNewlyGenerated === $tokenFetched;
~~~

If an error happen during the generation process, a CriticalCsrfException is thrown

#### 5.1.2 Invalidate a Csrf token

Remove an already generated csrf token

If this csrf token cannot be removed, a CriticalCsrfException is thrown

#### 5.1.3 Validate a Csrf token

Simply validate a previously generated (by generate() method) over a given csrf token.

~~~php
<?php
$manager = new CsrfManagerImplementation();
$manager->generate(); // let's assume the generated token has for value Foo
$manager->validate(new CsrfToken("Foo"));

// no exception thrown whatsoever, the given csrf token is considered valid
~~~

The validation process throws two kinds of exception depending of the context.

A CsrfTokenNotFoundException if no token has been previously generated or a InvalidCsrfTokenException if a token has been found but does not match the given one.

~~~php
<?php
$manager = new CsrfManagerImplementation();

// will throw a CsrfTokenNotFoundException
$manager->validate(new CsrfToken("Foo"));

$manager = new CsrfManagerImplementation();

// will throw a CsrfTokenNotFoundException
$manager->generate(); // let's assume the generated token has for value Foo
$manager->validate(new CsrfToken("Bar"));
~~~

### 5.2 Implementation

This library provides an implementation of CsrfTokenManagerInterface based on the components described above.

Let's talk about the validation strategy and how it is handled by the manager.

onGeneration() method is called every time the generate() is called. <br />
onSubmission() method is called when a token has been found into the store and allows you to perform a verification on it. <br />
postSubmission() method is called when the given token corresponds the stored one and allows you to modify its state.

Let's see how to instantiate it and use it.

~~~php
<?php
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

## 6. Contributing

Found something **wrong** (nothing is perfect) ? Wanna talk or participate ? <br />
Issue the case or contact me at [curtis_barogla@outlook.fr](mailto:curtis_barogla@outlook.fr)

## 7. License

The Ness Csrf component is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
