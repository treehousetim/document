![Unit Tests](https://github.com/treehousetim/document/workflows/Unit%20Tests/badge.svg)

# document
A generic document class used to create structured data objects

## Installing

`composer require treehousetim/document`

## Using
After installing, create your own class that extends `treehousetim\document`.
You must implement two methods.

`abstract public function jsonSerialize();`
Return a structure that makes sense for your document.  This method is called automatically if you json_encode an instance of your class.

`abstract protected function validate();`
This is semantic - you should call ->validate() where it makes sense in your code.  You can see one approach in the example below.

## Getting Data Out
You can json_encode a document sub class and it will return what you return from `jsonSerialize()` serialized into a JSON string.

### ->dataArray()
This will return the result of `->jsonSerialize()` cast as an array.

### ->dataObject()
This will return the result of `->jsonSerialize()` cast as an object.  This will be a stdClass.

### Note
*`dataArray` and `dataObject` will both be shallow arrays/objects - it only affects the return type of the immediate document, these do not descend into sub documents.*

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
}
```

### ->validateRequired( $name )
Validates that a property is valid.  Current logic is `if ( ! $this->{$name} ) throw Exception(...`

The code thrown is `Exception::missingData`

### ->validateSubDocument( $name )
You can validate a sub document using this method.  This will throw an exception if the sub document does not exist as a document class object.  If it does, ->validate is called on the property which may throw other exceptions.

The code thrown is `Exception::missingData` if the property is not set or `Exception::wrongType` if the property is not set as a document subclass.

### ->validateValueInList( string $name, string $value, array $list )
You can validate a value to be in a list using this method.

The code thrown is `Exception::disallowedValue`

The suggestion is made to implement a setter function for any values you want to set using this validation function.  Before you set the value on the document class you would call this validation function.  This will protect your document object from ever having wrong values set on it.

### ->validateNotNull( string ...$names )
Validates that a property exists and is not equal to null. `!== null`

The code thrown is `Exception::noSuchProperty` if property does not exist on class.

The code thrown is `Exception::missingData` if the property === null.

## Example
```php
<?PHP namespace application\libraries\documents;

class customer extends \treehousetim\document\document
{
	protected $first_name;
	protected $last_name;
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
			'first_name',
			'last_name',
			'city',
			'state_province',
			'postal_code',
			'email',
			'address_line_1',
			'address_line_2'
		);
	}
	//------------------------------------------------------------------------
	protected function validate()
	{
		$this->validateRequired(
			'first_name',
			'last_name',
			'city',
			'state_province',
			'postal_code',
			'address_line_1'
		);

		$this->validateNotNull( 'address_line_2' );
	}
}
```

## Testing the code base
If you have cloned this repository, you can run the tests.

1. Install test dependencies: `composer install`
2. Run tests: `composer test`