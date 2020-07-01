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

## Example
```
<?PHP namespace application\libraries\documents;

class customer extends \treehousetim\document\document
{
	public $first_name;
	public $last_name;
	public $address_line_1;
	public $city;
	public $state_province;
	public $postal_code;
	public $email;

	public function jsonSerialize ()
	{
		$this->validate();
		$out = [];

		$out ['first_name'] = $this->first_name;
		$out ['last_name'] = $this->last_name;
		$out ['city'] = $this->city;
		$out ['state_province'] = $this->state_province;
		$out ['postal_code'] = $this->postal_code;
		$out ['email'] = $this->email;
		$out ['address_line_1'] = $this->address_line_1;

		return $out;
	}
	//------------------------------------------------------------------------
	protected function validate()
	{
		$this->validateRequired( 'first_name' );
		$this->validateRequired( 'last_name' );
		$this->validateRequired( 'city' );
		$this->validateRequired( 'state_province' );
		$this->validateRequired( 'postal_code' );
		$this->validateRequired( 'address_line_1' );
	}
}
```