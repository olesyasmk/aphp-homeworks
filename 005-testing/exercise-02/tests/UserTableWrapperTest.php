<?php

namespace Tests;

use App\Classes\UserTableWrapper;
use PHPUnit\Framework\TestCase;

class UserTableWrapperTest extends TestCase
{
	private UserTableWrapper $table;
	
	protected function setUp(): void
	{
		$this->table = new UserTableWrapper();
	}
	
	public function testInsert( array $values ): void
	{
		$this->table->insert( $values );
		$this->assertContains( $values, $this->table->get() );
	}
	
	public function testUpdate( int $id, array $initialValues, array $updateValues, array $expectedResult ): void
	{
		$this->table->insert( $initialValues );
		$result = $this->table->update( $id, $updateValues );
		
		$this->assertEquals( $expectedResult, $result );
		$this->assertEquals( $expectedResult, $this->table->get()[ $id ] );
	}
	
	public function testDelete( int $id, array $initialRows, int $expectedCount ): void
	{
		foreach ( $initialRows as $row ) {
			$this->table->insert( $row );
		}
		
		$this->table->delete( $id );
		$this->assertCount( $expectedCount, $this->table->get() );
		$this->assertArrayNotHasKey( $id, $this->table->get() );
	}
	
	public function testGet( array $initialRows, array $expectedResult ): void
	{
		foreach ( $initialRows as $row ) {
			$this->table->insert( $row );
		}
		
		$this->assertEquals( $expectedResult, $this->table->get() );
	}
	
	public static function insertDataProvider(): array
	{
		return [
			'single row'       => [ [ 'name' => 'John', 'email' => 'john@example.com' ] ],
			'multiple columns' => [ [ 'id' => 1, 'name' => 'Alice', 'role' => 'admin' ] ],
		];
	}
	
	public static function updateDataProvider(): array
	{
		return [
			'update name' => [
				0,
				[ 'name' => 'John', 'email' => 'john@example.com' ],
				[ 'name' => 'Johnny' ],
				[ 'name' => 'Johnny', 'email' => 'john@example.com' ]
			],
		];
	}
	
	public static function deleteDataProvider(): array
	{
		return [
			'delete first row' => [
				0,
				[ [ 'name' => 'John' ], [ 'name' => 'Alice' ] ],
				1
			],
		];
	}
	
	public static function getDataProvider(): array
	{
		return [
			'empty table'     => [ [], [] ],
			'table with data' => [
				[ [ 'name' => 'John' ], [ 'name' => 'Alice' ] ],
				[ [ 'name' => 'John' ], [ 'name' => 'Alice' ] ]
			],
		];
	}
}
