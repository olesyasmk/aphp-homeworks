<?php

namespace App\Classes;

use App\Interfaces\TableWrapperInterface;

class UserTableWrapper implements TableWrapperInterface
{
	private array $rows = [];
	
	
	public function insert( array $values ): void
	{
		$this->rows[] = $values;
	}
	
	public function update( int $id, array $values ): array
	{
		if ( ! isset( $this->rows[ $id ] ) ) {
			return [];
		}
		
		$this->rows[ $id ] = array_merge( $this->rows[ $id ], $values );
		
		return $this->rows[ $id ];
	}
	
	public function delete( int $id ): void
	{
		if ( isset( $this->rows[ $id ] ) ) {
			unset( $this->rows[ $id ] );
		}
	}
	
	public function get(): array
	{
		return $this->rows;
	}
}
