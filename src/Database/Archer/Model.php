<?php

namespace Arco\Database\Archer;

use Arco\Database\Archer\About\Arrayable;
use Arco\Helpers\Arrows\Str;
use Arco\Database\Archer\About\Relations;
use Arco\Database\Drivers\DatabaseDriver;

abstract class Model {
    use Relations;
    use Arrayable;

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
     * Get all attributes.
     *
     * @return array
     */
    public function getAttributes(): array {
        return $this->attributes;
    }

    /**
     * Get all public attributes.
     *
     * @return array
     */
    public function getPublicAttributes(): array {
        foreach ($this->hidden as $hide) {
            unset($this->attributes[$hide]);
        }

        return $this->attributes;
    }

    /**
     * Get table name
     *
     * @return string
     */
    public function getTable(): string {
        return $this->table;
    }

    /**
     * Get the primary key name for this model
     *
     * @return string
     */
    public function getKeyName(): string {
        return $this->primaryKey;
    }

    /**
     * Set the value of the primary key in attributes
     */
    public function setPrimaryKeyAttribute(int|string $primaryKey) {
        $this->__set($this->primaryKey, $primaryKey);
    }

    /**
     * Get the value of the primary key in attributes
     */
    public function getKey() {
        return $this->__get($this->primaryKey);
    }

    /**
     * Get Foreign Key for this Model in string format
     *
     * @return string
     */
    public function getForeignKey(): string {
        return Str::snake($this->getBasename()).'_'.$this->getKeyName();
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
    // public function toArray() {
    //     if (count($this->attributes) == 0) {
    //         return [];
    //     }

    //     return array_filter(
    //         $this->attributes,
    //         fn ($attr) => !in_array($attr, $this->hidden)
    //     );
    // }

    /**
     * Create a new row in database
     *
     * @return static
     */
    public function save(): static {
        if ($this->insertTimestamps) {
            $this->attributes["created_at"] = date("Y-m-d H:i:s");
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

    /**
     * Update model attributes in database
     *
     * @param array $attributes You can give attributes to be set and updated.
     * @return static
     */
    public function update(array $attributes = []): static {
        if (!empty($attributes)) {
            $this->massAssign($attributes);
        }

        if ($this->insertTimestamps) {
            $this->attributes["updated_at"] = date("Y-m-d H:i:s");
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

    /**
     * Delete the row in database
     *
     * @return static
     */
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
            return (new Collection([]))->get();
        }

        $models = [];

        for ($i = 0; $i < count($rows); $i++) {
            $models[] = (new static())->setAttributes($rows[$i]);
        }

        return (new Collection($models))->get();
    }

    /**
     * Create a collection with all Model objects stored in the database.
     *
     * @return Collection
     */
    public static function collection(): Collection {
        $model = new static();
        $rows = self::$driver->statement("SELECT * FROM $model->table");

        if (count($rows) == 0) {
            return (new Collection([]))->get();
        }

        $models = [];

        for ($i = 0; $i < count($rows); $i++) {
            $models[] = (new static())->setAttributes($rows[$i]);
        }

        return new Collection($models);
    }

    /**
     * Get matched by value and column
     *
     * @param string $column
     * @param mixed $value
     * @return Collection
     */
    public static function where(string $column, mixed $value): Collection {
        $model = new static();
        $rows = self::$driver->statement(
            "SELECT * FROM $model->table WHERE $column = ?",
            [$value]
        );

        if (count($rows) == 0) {
            return new Collection([]);
        }

        $models = [];

        for ($i = 0; $i < count($rows); $i++) {
            $models[] = (new static())->setAttributes($rows[$i]);
        }

        return new Collection($models);
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
