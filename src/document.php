<?PHP namespace treehousetim\document;

abstract class document implements \jsonSerializable
{
	private $_doc_set_values = [];

	abstract public function jsonSerialize ();
	abstract protected function validate();
	//------------------------------------------------------------------------
	public function doValidate()
	{
		$this->validate();
	}
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
	public function fieldExists( $name ) : bool
	{
		return property_exists( get_class( $this ), $name );
	}
	//------------------------------------------------------------------------
	protected function requireFieldExists( string $name )
	{
		if( ! $this->fieldExists( $name ) )
		{
			throw new Exception( $name . ' does not exist on ' . get_class( $this ), Exception::noSuchProperty );
		}
	}
	//------------------------------------------------------------------------
	public function fieldSet( $name ) : bool
	{
		return array_key_exists( $name, $this->_doc_set_values ) && $this->_doc_set_values[$name] != false;
	}
	//------------------------------------------------------------------------
	public function fieldExistsAndIsSet( $name ) : bool
	{
		return $this->fieldExists( $name ) && $this->fieldSet( $name );
	}
	//------------------------------------------------------------------------
	protected function validateRequired( string ...$names )
	{
		foreach( $names as $name )
		{
			if( ! $this->fieldSet( $name ) )
			{
				$this->requireFieldExists( $name );

				throw new Exception( $name . ':: has not been set', Exception::missingData );
			}
		}
	}
	//------------------------------------------------------------------------
	protected function validateHasValue( string ...$names )
	{
		foreach( $names as $name )
		{
			$this->validateNotNull( $name );

			if( $this->{$name} == '' )
			{
				throw new Exception( $name . ':: is empty', Exception::noValue );
			}
		}
	}
	//------------------------------------------------------------------------
	protected function validateNotNull( string ...$names )
	{
		foreach( $names as $name )
		{
			$this->validateRequired( $name );

			if( $this->{$name} === null )
			{
				throw new Exception( $name . ':: is null', Exception::missingData );
			}
		}
	}
	//------------------------------------------------------------------------
	protected function validateSubDocument( string ...$names )
	{
		foreach( $names as $name )
		{
			$this->validateRequired( $name );

			if( is_array( $this->{$name} ) )
			{
				$this->validateArrayOfSubDocuments( $name, $this->{$name} );
			}
			else
			{
				if( ! $this->{$name} instanceOf document )
				{
					throw new Exception( $name . ':: is not a sub document', Exception::wrongType );
				}

				$this->{$name}->validate();
			}
		}
	}
	//------------------------------------------------------------------------
	protected function validateArrayOfSubDocuments( string $name, array $arr )
	{
		foreach( $arr as $sub )
		{
			if( ! $sub instanceOf document )
			{
				throw new Exception( $name . ':: array item is not a sub document', Exception::wrongType );
			}

			$sub->validate();
		}
	}
	//------------------------------------------------------------------------
	protected function validateValueInList( string $name, string $value, array $list )
	{
		if( ! in_array( $value, $list ) )
		{
			throw new Exception( $name . ':: Disallowed Value (' . print_r( $value, true ) . ') for ' . $name . ' on ' . get_class( $this ), Exception::disallowedValue );
		}
	}
	//------------------------------------------------------------------------
	public function __call( $name, $arguments ) :self
	{
		if( count( $arguments ) != 1 )
		{
			throw new Exception( 'When setting values on ' . get_class( $this ) . ' ('. $name . ') You must pass exactly one value. Value passed:' . PHP_EOL . print_r( $arguments, true ), Exception::callOneVar );
		}

		$this->requireFieldExists( $name );

		$this->_doc_set_values[$name] = true;
		$this->{$name} = $arguments[0];

		return $this;
	}
	//------------------------------------------------------------------------
	protected function markValueSet( $name ) : self
	{
		$this->_doc_set_values[$name] = true;
		return $this;
	}
	//------------------------------------------------------------------------
	public function __get( $name )
	{
		$this->requireFieldExists( $name );

		return $this->{$name};
	}
	//------------------------------------------------------------------------
	public function __unset( $name )
	{
		$this->requireFieldExists( $name );

		$this->_doc_set_values[$name] = false;
		$this->{$name} = null;
	}
	//------------------------------------------------------------------------
	protected function optionalFieldOut( $fieldName, array &$out )
	{
		if( $this->$fieldName )
		{
			$out[$fieldName] = $this->{$fieldName};
		}
	}
	//------------------------------------------------------------------------
	protected function fieldOutIfSet( $name, array &$out )
	{
		if( $this->fieldSet( $name ) )
		{
			$out[$name] = $this->{$name};
		}
	}
	//------------------------------------------------------------------------
	public function setFromMappedArray( array $map, array $data )
	{
		foreach ( $map as $incoming => $classProp )
		{
			$this->requireFieldExists( $classProp );

			if( ! array_key_exists( $incoming, $data ) )
			{
				throw new Exception( $incoming . ' does not exist in provided array', Exception::missingData );
			}

			$this->$classProp( $data[$incoming] );
		}

		return $this;
	}
	//------------------------------------------------------------------------
	protected function getFieldArray( string ...$fields ) : array
	{
		$out = array();

		foreach( $fields as $name )
		{
			$this->requireFieldExists( $name );

			if( $this->{$name} instanceOf document )
			{
				$out[$name] = $this->{$name}->jsonSerialize();
			}
			else
			{
				if( is_array( $this->{$name} ) && count( $this->{$name} ) && $this->{$name}[0] instanceOf document )
				{
					foreach( $this->{$name} as $subDocument )
					{
						$out[$name][] = $subDocument->jsonSerialize();
					}
				}
				else
				{
					$out[$name] = $this->{$name};
				}
			}
		}

		return $out;
	}
}
