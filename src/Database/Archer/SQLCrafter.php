<?php

namespace Arco\Database\Archer;

class SQLCrafter {
    /**
     * Table name.
     *
     * @var string
     */
    protected string $table;

    /**
     * Array of all table columns.
     *
     * @var array
     */
    protected array $columns = [];

    /**
     * Array of columns to delete.
     *
     * @var array
     */
    protected array $columnsToDrop = [];

    /**
     * Primary Key column name.
     *
     * @var string
     */
    protected string $primaryKey = 'id';

    /**
     * Array of all foreign keys of the table.
     *
     * @var array
     */
    protected array $foreignKeys = [];

    /**
     * Write the name of the table.
     *
     * @param string $table
     */
    public function __construct(string $table) {
        $this->table = $table;
    }

    /**
     * Adds a column to the table.
     * @param string $column the name of the column
     * @param string $value the data type and constraints of the column
     * @param bool $nullable whether or not the column can have null values
     * @param bool $uuid whether or not the column is a unique identifier
    */
    protected function addColumn(string $column, string $value, bool $nullable = false, bool $uuid = false) {
        $null = $nullable ? ' NULL' : '';
        $unique = $uuid ? ' UNIQUE' : '';
        array_push($this->columns, [$column, $value.$null.$unique]);
    }

    /**
     * Drops a column from the table.
     *
     * @param string $column
     * @return void
     */
    public function dropColumn(string $column) {
        array_push($this->columnsToDrop, $column);
    }

    /**
     * Create the primery key column as `INT`.
     *
     * @param string $column
     * @return void
     */
    public function id(string $column = 'id') {
        $this->primaryKey = $column;
        $this->addColumn($column, "INT AUTO_INCREMENT");
    }

    /**
     * Create the primary key column as `BIGINT`.
     *
     * @param string $column
     * @return void
     */
    public function bigId(string $column = 'id') {
        $this->primaryKey = $column;
        $this->addColumn($column, "BIGINT AUTO_INCREMENT");
    }

    /**
     * Create an `INT` column.
     *
     * @param string $column
     * @param boolean $nullable
     * @param boolean $uuid
     * @return void
     */
    public function integer(string $column, bool $nullable = false, bool $uuid = false) {
        $this->addColumn($column, 'INT', $nullable, $uuid);
    }

    /**
     * Create an `BIGINT` column.
     *
     * @param string $column
     * @param boolean $nullable
     * @param boolean $uuid
     * @return void
     */
    public function bigInteger(string $column, bool $nullable = false, bool $uuid = false) {
        $this->addColumn($column, 'BIGINT', $nullable, $uuid);
    }

    /**
     * Create a `DECIMAL` column.
     *
     * @param string $column
     * @param integer $length
     * @param integer $places
     * @param boolean $nullable
     * @param boolean $uuid
     * @return void
     */
    public function decimal(string $column, int $length = 8, int $places = 2, bool $nullable = false, bool $uuid = false) {
        $this->addColumn($column, "DECIMAL($length, $places)", $nullable, $uuid);
    }

    /**
     * Create a `VARCHAR` column.
     *
     * @param string $column
     * @param integer $length
     * @param boolean $nullable
     * @param boolean $uuid
     * @return void
     */
    public function string(string $column, int $length = 255, bool $nullable = false, bool $uuid = false) {
        $this->addColumn($column, "VARCHAR($length)", $nullable, $uuid);
    }

    /**
     * Create a `TEXT` column.
     *
     * @param string $column
     * @param boolean $nullable
     * @param boolean $uuid
     * @return void
     */
    public function text(string $column, bool $nullable = false, bool $uuid = false) {
        $this->addColumn($column, "TEXT", $nullable, $uuid);
    }

    /**
     * Create a `DATE` column.
     *
     * @param string $column
     * @param boolean $nullable
     * @return void
     */
    public function date(string $column, bool $nullable = false) {
        $this->addColumn($column, 'DATE', $nullable);
    }

    /**
     * Create a `TIMESTAMP` column.
     *
     * @param string $column
     * @param boolean $nullable
     * @return void
     */
    public function timestamp(string $column, bool $nullable = false) {
        $this->addColumn($column, 'TIMESTAMP', $nullable);
    }

    /**
     * Create a `TIME` column.
     *
     * @param string $column
     * @param boolean $nullable
     * @return void
     */
    public function time(string $column, bool $nullable = false) {
        $this->addColumn($column, 'TIME', $nullable);
    }

    /**
     * Create the `remember_token` column used by `RememberCookieAuthentication`.
     *
     * @return void
     */
    public function rememberToken() {
        $this->string('remember_token', 100, true, true);
    }

    /**
     * Create foreign keys for this table.
     * You need to create the column before the assignation.
     *
     * @param string $column
     * @param string $referencedTable
     * @param string $referencedColumn
     * @return void
     */
    public function foreignKey(string $column, string $referencedTable, string $referencedColumn) {
        $this->foreignKeys[] = "FOREIGN KEY ($column) REFERENCES $referencedTable($referencedColumn)";
    }

    /**
     * Create the columns `created_at` and `updated_at` used by Models.
     *
     * @return void
     */
    public function timestamps() {
        $this->timestamp('created_at', true);
        $this->timestamp('updated_at', true);
    }

    /**
     * Create a column by writing the value in SQL syntax.
     *
     * @param string $column
     * @param string $value
     * @return void
     */
    public function column(string $column, string $value) {
        $this->addColumn($column, $value);
    }

    /**
     * Create a `CREATE TABLE` statement.
     *
     * @return string
     */
    public function create(): string {
        $query = "CREATE TABLE $this->table (";

        foreach ($this->columns as $column) {
            $query .= "$column[0] $column[1],";
        }

        foreach ($this->foreignKeys as $foreignKey) {
            $query .= "$foreignKey,";
        }

        $query .= "PRIMARY KEY ($this->primaryKey))";

        return $query;
    }

    /**
     * Create a `ALTER TABLE` statement.
     *
     * @return string
     */
    public function alter(): string {
        $query = "ALTER TABLE $this->table ";

        foreach ($this->columns as $column) {
            $query .= "ADD COLUMN $column[0] $column[1],";
        }

        foreach ($this->columnsToDrop as $column) {
            $query .= "DROP COLUMN $column,";
        }

        $query = rtrim($query, ",");

        return $query;
    }

    /**
     * Create a `DROP TABLE IF EXISTS` statement.
     *
     * @return string
     */
    public function dropIfExists(): string {
        return "DROP TABLE IF EXISTS {$this->table}";
    }
}
