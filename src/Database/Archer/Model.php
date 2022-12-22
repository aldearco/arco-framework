<?php

namespace Arco\Database\Archer;

use Arco\Database\Archer\About\Relations;
use Arco\Database\Drivers\DatabaseDriver;

abstract class Model {
    use Relations;

    /**
     * Name of the table
     *
     * @var string|null
     */
    protected ?string $table = null;

    /**
     * Name of the primary key.
     *
     * @var string Default `"id"`
     */
    protected string $primaryKey = "id";

    /**
     * Hidden attributes
     *
     * @var array
     */
    protected array $hidden = [];

    /**
     * Fillable and public attributes
     *
     * @var array
     */
    protected array $fillable = [];

    /**
     * All object attributes
     *
     * @var array
     */
    protected array $attributes = [];

    /**
     * Define if insert `created_at` and `updated_at` rows
     *
     * @var boolean Default `true`
     */
    protected bool $insertTimestamps = true;

    /**
     * Define if this model has a primary key autoincrementable
     *
     * @var boolean
     */
    protected bool $incrementable = true;

    /**
     * Database Driver pointer
     *
     * @var DatabaseDriver|null
     */
    private static ?DatabaseDriver $driver = null;

    /**
     * Set Database Driver given by the app provider
     *
     * @param DatabaseDriver $driver
     */
    public static function setDatabaseDriver(DatabaseDriver $driver) {
        self::$driver = $driver;
    }

    public function __construct() {
        if (is_null($this->table)) {
            $subclass = new \ReflectionClass(static::class);
            $this->table = snake_case("{$subclass->getShortName()}s");
        }
    }

    /**
     * Set attribute
     */
    public function __set($name, $value) {
        $this->attributes[$name] = $value;
    }

    /**
     * Get attribute
     */
    public function __get($name) {
        return $this->attributes[$name];
    }

    public function __sleep() {
        foreach ($this->hidden as $hide) {
            unset($this->attributes[$hide]);
        }

        return array_keys(get_object_vars($this));
    }

    /**
     * Set all attributes for this object
     *
     * @param array $attributes
     * @return static
     */
    protected function setAttributes(array $attributes): static {
        foreach ($attributes as $key => $value) {
            $this->__set($key, $value);
        }

        return $this;
    }

    /**
     * Get the primary key name for this model
     */
    protected function getPrimaryKey() {
        return $this->primaryKey;
    }

    /**
     * Set the value of the primary key in attributes
     */
    protected function setPrimaryKeyAttribute(int|string $primaryKey) {
        $this->__set($this->primaryKey, $primaryKey);
    }

    /**
     * Get the value of the primary key in attributes
     */
    protected function getPrimaryKeyAttribute() {
        return $this->__get($this->primaryKey);
    }

    /**
     * Get basename class
     */
    protected function getBasename() {
        return basename(str_replace('\\', '/', get_class($this)));
    }

    /**
     * Masive assignation of attributes used when you create new model
     *
     * @param array $attributes
     * @return static
     */
    protected function massAssign(array $attributes): static {
        if (count($this->fillable) == 0) {
            throw new \Error("Model ". static::class . " does not have fillable attributes");
        }

        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->__set($key, $value);
            }
        }

        return $this;
    }

    /**
     * Turn object models into array
     */
    public function toArray() {
        if (count($this->attributes) == 0) {
            return [];
        }

        return array_filter(
            $this->attributes,
            fn ($attr) => !in_array($attr, $this->hidden)
        );
    }

    public function save(): static {
        if ($this->insertTimestamps) {
            $this->attributes["created_at"] = date("Y-m-d H:m:s");
            $this->attributes["updated_at"] = null;
        }
        $databaseColumns = implode(",", array_keys($this->attributes));
        $bind = implode(",", array_fill(0, count($this->attributes), "?"));
        self::$driver->statement(
            "INSERT INTO $this->table ($databaseColumns) VALUES ($bind)",
            array_values($this->attributes)
        );

        if ($this->incrementable) {
            $this->setPrimaryKeyAttribute(intval(self::$driver->lastInsertId()));
        }

        return $this;
    }

    public function update(): static {
        if ($this->insertTimestamps) {
            $this->attributes["updated_at"] = date("Y-m-d H:m:s");
        }

        $databaseColumns = array_keys($this->attributes);
        $bind = implode(",", array_map(fn ($c) => "$c = ?", $databaseColumns));
        $id = $this->attributes[$this->primaryKey];

        self::$driver->statement(
            "UPDATE $this->table SET $bind WHERE $this->primaryKey = $id",
            array_values($this->attributes)
        );

        return $this;
    }

    public function delete(): static {
        self::$driver->statement(
            "DELETE FROM $this->table WHERE $this->primaryKey = {$this->attributes[$this->primaryKey]}"
        );

        return $this;
    }

    /**
     * Static method to create
     *
     * @param array $attributes
     * @return static
     */
    public static function create(array $attributes): static {
        return (new static())->massAssign($attributes)->save();
    }

    /**
     * Get the first
     *
     * @return static|null
     */
    public static function first(): ?static {
        $model = new static();
        $rows = self::$driver->statement("SELECT * FROM $model->table LIMIT 1");

        if (count($rows) == 0) {
            return null;
        }

        return $model->setAttributes($rows[0]);
    }

    /**
     * Find by the model primary key
     *
     * @param integer|string $id
     * @return static|null
     */
    public static function find(int|string $id): ?static {
        $model = new static();
        $rows = self::$driver->statement(
            "SELECT * FROM $model->table WHERE $model->primaryKey = ?",
            [$id]
        );

        if (count($rows) == 0) {
            return null;
        }

        return $model->setAttributes($rows[0]);
    }

    /**
     * Get all form table
     *
     * @return array
     */
    public static function all(): array {
        $model = new static();
        $rows = self::$driver->statement("SELECT * FROM $model->table");

        if (count($rows) == 0) {
            return [];
        }

        $models = [];

        for ($i = 0; $i < count($rows); $i++) {
            $models[] = (new static())->setAttributes($rows[$i]);
        }

        return $models;
    }

    /**
     * Get matched by value and column
     *
     * @param string $column
     * @param mixed $value
     * @return array
     */
    public static function where(string $column, mixed $value): array {
        $model = new static();
        $rows = self::$driver->statement(
            "SELECT * FROM $model->table WHERE $column = ?",
            [$value]
        );

        if (count($rows) == 0) {
            return [];
        }

        $models = [];

        for ($i = 0; $i < count($rows); $i++) {
            $models[] = (new static())->setAttributes($rows[$i]);
        }

        return $models;
    }

    /**
     * Get the first matched by column and value
     *
     * @param string $column
     * @param mixed $value
     * @return static|null
     */
    public static function firstWhere(string $column, mixed $value): ?static {
        $model = new static();
        $rows = self::$driver->statement(
            "SELECT * FROM $model->table WHERE $column = ?",
            [$value]
        );

        if (count($rows) == 0) {
            return null;
        }

        return $model->setAttributes($rows[0]);
    }
}
