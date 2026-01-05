<?php

namespace App\Classes;

use App\Interfaces\DatabaseWrapper;
use PDO;

abstract class BaseModel implements DatabaseWrapper
{
	protected $pdo;
	protected $table;
	
	public function __construct( PDO $pdo )
	{
		$this->pdo = $pdo;
	}
	
	public function insert( array $tableColumns, array $values ): array
	{
		$columns      = implode( ', ', $tableColumns );
		$placeholders = implode( ', ', array_fill( 0, count( $values ), '?' ) );
		
		$sql  = "INSERT INTO `{$this->table}` ({$columns}) VALUES ({$placeholders})";
		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute( $values );
		
		$id = (int) $this->pdo->lastInsertId();
		
		return $this->find( $id );
	}
	
	public function update( int $id, array $values ): array
	{
		$setParts = [];
		$params   = [];
		foreach ( $values as $column => $value ) {
			$setParts[] = "`{$column}` = ?";
			$params[]   = $value;
		}
		$params[] = $id;
		
		$sql  = "UPDATE `{$this->table}` SET " . implode( ', ', $setParts ) . " WHERE id = ?";
		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute( $params );
		
		return $this->find( $id );
	}
	
	public function find( int $id ): array
	{
		$sql  = "SELECT * FROM `{$this->table}` WHERE id = ?";
		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute( [ $id ] );
		$result = $stmt->fetch();
		
		return $result ?: [];
	}
	
	public function delete( int $id ): bool
	{
		$sql  = "DELETE FROM `{$this->table}` WHERE id = ?";
		$stmt = $this->pdo->prepare( $sql );
		
		return $stmt->execute( [ $id ] );
	}
}
