![Unit Tests](https://github.com/treehousetim/document/workflows/Unit%20Tests/badge.svg)

# treehousetim/document
A generic document class used to create structured data objects.  One use for this is to create objects that conform to an API request or response.

## Installing

`composer require treehousetim/document`

## Using
After installing, create your own class that extends `treehousetim\document`.
Add all your properties as protected and implement the abstract methods.

### Abstract Methods

```php
abstract public function jsonSerialize();
```
Return a structure that makes sense for your document.  This method is called automatically if you json_encode an instance of your class.

```php
abstract protected function validate()
```

This is semantic - you should call ->validate() where it makes sense in your code.
It is suggested to always call `$this->validate();` at the top of your `jsonSerialize()` function.
You can see one approach in the examples at the bottom.

This method will be called on sub documents that are members of a document.

### Setting values
After extending and creating a document class with properties, you can set values using chained function calls.

```php
// See class definitions at the bottom of this README

// create a sub document and pass it to a function
$name = (new personName())
	->first( 'Easter' )
	->last( 'Bunny' )
	->full( 'The Easter Bunny' );

$customer = new (customer() )
	->name( $name );

// or create sub documents inline

$customer = new (customer() )
	->name( (new personName())
		->first( 'Easter' )
		->last( 'Bunny' )
		->full( 'The Easter Bunny' )
	)
	->address_line_1( '123 Main Street' )
	->address_line_2( '' )
	->city( 'Dubuque' )
	->state_province( 'IA' )
	->postal_code( '12345' );
```

### Setting sub documents
You should always write a setter method to enforce the type of the sub document (if using PHP 7.4 or later, you can specify type on the class declaration).

*see example at the bottom of this README*

### Setting and validating a value is in a list
You can validate values coming into your document to conform to a list of allowed values by using `$this->validateValueInList`.

```php
class aDocument extends \treehousetim\document\document
{
	const adtHTML = 'html';
	const adtTEXT = 'text';
	const adtJSON = 'json';

	protected $allowedTypes = [
		self::adtHTML,
		self::adtTEXT,
		self::adtJSON
	];

	protected $type;

	public function type( string $type ) : self
	{
		$this->validateValueInList( 'type', $type, $this->allowedTypes );
		return parent::type( $type );
	}
	//------------------------------------------------------------------------
	public function jsonSerialize()
	{
		$this->validate();
		return $this->getFieldArray( 'type' );
	}
	//------------------------------------------------------------------------
	protected function validate()
	{
		$this->validateRequired( 'type' );
	}
}
```
## Arrays of sub documents
In order to support documents containing array lists of other documents, you can have properties/fields that are arrays.

```php
class arrayDocument extends \treehousetim\document\document
{
	protected $name = array();

	public function jsonSerialize ()
	{
		$this->validate();
		return $this->getFieldArray( 'name' );
	}
	//------------------------------------------------------------------------
	public function name( nameDocument $nameDoc ) : self
	{
		$this->name[] = $nameDoc;
		$this->markValueSet( 'name' );
		return $this;
	}
	//------------------------------------------------------------------------
	protected function validate()
	{
		$this->validateSubDocument( 'name' );
	}
}
```

## When to call markValueSet
If you write a custom property setter as described above, you might need to call `->markValueSet( $name )` to ensure validation works.
If you don't call the parent class and set the property directly, you will need to call `markValueSet( $name )`.
If you call the parent class like this you will not need to.

```php
// choose one way to do this
public function name( personName $name ) : self
{
	$this->name = $name;
	$this->markValueSet( 'name' );
	return $this;
}

// or 
public function name( personName $name ) : self
{
	return parent::name( $name );
}
```

See the examples at the bottom.

---

## Getting Data Out
You can json_encode a document sub class and it will return what you return from `jsonSerialize()` serialized into a JSON string.

### ->dataArray()
This will return the result of `->jsonSerialize()` cast as an array.

### ->dataObject()
This will return the result of `->jsonSerialize()` cast as an object.  This will be a stdClass.

### Note
*`dataArray` and `dataObject` will both be shallow arrays/objects - it only affects the return type of the immediate document, these do not descend into sub documents.*

---

## Validating
You can use built in methods in the document class to validate.  Validation is done via exceptions - you should validate your data before creating documents if you want to return end-user validation messages.

### ->doValidate()
A public interface to the protected validate method.  Public/Protected are separated for possible future features.

### Exception Codes
Exceptions are thrown using codes according to the following list

```php
class Exception extends \LogicException
{
	const undefined = -1;
	const missingData = 1;
	const wrongType = 2;
	const callOneVar = 3;
	const noSuchProperty = 4;
	const disallowedValue = 5;
	const noValue = 6;
}
```

### ->validateRequired( $name )
For properties that are set using the `treehousetim\document\document` class, this validation rule will work.  No matter the value set on the property, if it has been set using a function call this validation rule will succeed.  No exception will be thrown.

### Note
If you are using custom property setting class functions, you will need to call `->markValueSet( 'property_name' );` in your function.
The code thrown is `Exception::missingData`

If the property does not exist, you will receive an exception with a code `Exception::noSuchProperty`.  If the property has not been set, you will receive an exception code `Exception::missingData`.

### ->validateSubDocument( $name )
You can validate a sub document using this method.  Internally, `->validateRequired` is called first. Then if the class property is not a document subclass, you will receive an exception code `Exception::wrontType`.

If both previous conditions pass, ->validate is called on the property, which is another document class and may throw other exceptions from its validation function.

*Note: the call to ->validate works even though it is a protected method because that's how PHP works.*

### ->validateValueInList( string $name, string $value, array $list )
You can validate a value to be in a list using this method.

The code thrown is `Exception::disallowedValue`

The suggestion is made to implement a setter function for any values you want to set using this validation function.  Before you set the value on the document class you would call this validation function.  This will protect your document object from ever having wrong values set on it.

**Set Example Above**

### ->validateNotNull( string ...$names )
Validates that a property exists and is not equal to null. `!== null`

The code thrown is `Exception::noSuchProperty` if property does not exist on class.

The code thrown is `Exception::missingData` if the property === null.

### Other validations
You can and should perform any other validations that are necessary to protect the integrity of your document's data.
The suggestion is to throw exceptions - it should be a validation of last resort, not end user validation with error messages.

You can and should throw `\treehousetim\document\Exception` exceptions along with an appropriate exception code from this project's Exception class.

```php
class Exception extends \LogicException
{
	const undefined = -1;
	const missingData = 1;
	const wrongType = 2;
	const callOneVar = 3;
	const noSuchProperty = 4;
	const disallowedValue = 5;
	const noValue = 6;
}
```

## Testing the code base
If you have cloned this repository, you can run the tests.

1. Install test dependencies: `composer install`
2. Run tests: `composer test`

---

## Examples

```php
<?PHP namespace application\libraries\documents;

class customer extends \treehousetim\document\document
{
	protected $name;
	protected $address_line_1;
	protected $address_line_2;
	protected $city;
	protected $state_province;
	protected $postal_code;
	protected $email;

	public function jsonSerialize ()
	{
		$this->validate();
		return $this->getFieldArray(
			'name',
			'address_line_1',
			'address_line_2',
			'city',
			'state_province',
			'postal_code',
			'email'
		);
	}
	//------------------------------------------------------------------------
	protected function validate()
	{
		// email is optional, so not included

		$this->validateHasValue(
			'address_line_1',
			'city',
			'state_province',
			'postal_code'
		);

		$this->validateRequired( 'address_line_2' );
		$this->validateSubDocument( 'name' );
	}
	//------------------------------------------------------------------------
	public function name( personName $name ) : self
	{
		return parent::name( $name );
	}
}

class personName extends \treehousetim\document\document
{
	protected $first;
	protected $last;
	protected $full;

	public function jsonSerialize()
	{
		return $this->getFieldArray(
			'first',
			'last',
			'full'
		);
	}
	//------------------------------------------------------------------------
	protected function validate()
	{
		// all are required to be set with some data
		$this->validateHasValue(
			'first',
			'last',
			'full'
		);
	}
}


```

## Important API
```php
protected function optionalFieldOut( $fieldName, array &$out )
protected function fieldOutIfSet( $name, array &$out )
protected function getFieldArray( string ...$fields ) : array
public function doValidate()
public function dataArray() : array
public function dataObject() : \stdClass
public function fieldExists( $name ) : bool
public function fieldSet( $name ) : bool
public function fieldExistsAndIsSet( $name ) : bool
```