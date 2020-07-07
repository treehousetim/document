<?PHP namespace treehousetim\document;

abstract class document implements \jsonSerializable
{
	abstract public function jsonSerialize ();
	abstract protected function validate();
	//------------------------------------------------------------------------
	public function dataArray() : array
	{
		return (array) $this->jsonSerialize();
	}
	//------------------------------------------------------------------------
	public function dataObject() : \stdClass
	{
		return (object) $this->jsonSerialize();
	}
	//------------------------------------------------------------------------
	protected function validateRequired( string $name )
	{
		if( ! $this->{$name} )
		{
			throw new Exception( $name . ' missing', Exception::missingData );
		}
	}
	//------------------------------------------------------------------------
	protected function validateSubDocument( string $name )
	{
		$this->validateRequired( $name );

		if( ! $this->{$name} instanceOf document )
		{
			throw new Exception( $name . ' is not a sub document', Exception::wrongType );
		}

		$this->{$name}->validate();
	}
	//------------------------------------------------------------------------
	protected function validateValueInList( string $name, string $value, array $list )
	{
		if( ! in_array( $value, $list ) )
		{
			throw new Exception( 'Disallowed Value (' . print_r( $value, true ) . ') for ' . $name . ' on ' . get_class( $this ), Exception::disallowedValue );
		}
	}
	//------------------------------------------------------------------------
	public function __call( $name, $arguments ) :self
	{
		if( count( $arguments ) != 1 )
		{
			throw new Exception( 'When setting values on ' . get_class( $this ) . ' ('. $name . ') You must pass exactly one value' . print_r( $arguments, true ), Exception::callOneVar );
		}

		if( ! property_exists( get_class( $this ), $name ) )
		{
			throw new Exception( $name . ' does not exist on ' . get_class( $this ), Exception::noSuchProperty );
		}

		$this->{$name} = $arguments[0];

		return $this;
	}
	//------------------------------------------------------------------------
	public function __get( $name )
	{
		if( ! property_exists( get_class( $this ), $name ) )
		{
			throw new Exception( $name . ' does not exist on ' . get_class( $this ), Exception::noSuchProperty );
		}

		return $this->{$name};
	}
	//------------------------------------------------------------------------
	protected function optionalFieldOut( $fieldName, array &$out )
	{
		if( $this->$fieldName )
		{
			$out[$fieldName] = $this->{$fieldName};
		}
	}
}
