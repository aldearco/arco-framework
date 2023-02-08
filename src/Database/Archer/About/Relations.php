<?php

namespace Arco\Database\Archer\About;

use Arco\Helpers\Arrows\Str;
use Arco\Database\Archer\Model;
use Arco\Database\Archer\Collection;

trait Relations {
    protected static array $relatedInstances = [];

    protected function newRelationInstance(string $class) {
        if (!array_key_exists($class, self::$relatedInstances)) {
            self::$relatedInstances[$class] = new $class();
        }

        return self::$relatedInstances[$class];
    }

    public function pivotTableName(string $related): string {
        $segments = [
            Str::snake(class_basename($related)),
            Str::snake($this->getBasename()),
        ];

        sort($segments);

        return strtolower(implode('_', $segments));
    }

    /**
     * Create a relationship One to Many of ownership
     *
     * @param string $related Instanced class
     * @param string|null $foreignKey Value for the search in `$related`
     * @param string|null $localKey Column to search for the value in `$related`
     */
    public function hasMany(string $related, ?string $foreignKey = null, ?string $localKey = null) {
        $instance = $this->newRelationInstance($related);

        if (is_null($foreignKey)) {
            $foreignKey = $this->getKey();
        }

        if (is_null($localKey)) {
            $localKey = $this->getForeignKey();
        }

        return $instance::where($localKey, $foreignKey);
    }

    /**
     * Create a relationship One to One of propierty
     *
     * @param string $related Instanced class
     * @param string|null $foreignKey Value for the search in `$related`
     * @param string|null $localKey Column to search for the value in `$related`
     */
    public function belongsTo(string $related, ?string $foreignKey = null, ?string $localKey = null) {
        $instance = $this->newRelationInstance($related);

        if (is_null($foreignKey)) {
            $foreignKeyName = $instance->getForeignKey();
            $foreignKey = $this->attributes[$foreignKeyName];
        }

        if (is_null($localKey)) {
            $localKey = $instance->getKeyName();
        }

        return $instance::firstWhere($localKey, $foreignKey);
    }

    /**
     * Generate SQL Statement for Many to Many relationship using a pivot table
     *
     * @param Model $instance
     * @param string $table
     * @param string $foreignPivotKey
     * @param string $relatedPivotKey
     * @param string $parentKey
     * @param string $relatedKey
     * @return string
     */
    protected function sqlBelongsToMany(Model $instance, string $table, string $foreignPivotKey, string $relatedPivotKey, string $parentKey, string $relatedKey): string {
        return "SELECT t1.*
                FROM {$instance->getTable()} AS t1
                INNER JOIN $table AS pivot ON pivot.{$relatedPivotKey} = t1.{$relatedKey}
                INNER JOIN {$this->table} AS t2 ON pivot.{$foreignPivotKey} = t2.{$parentKey}
                WHERE pivot.{$foreignPivotKey} = {$this->getKey()}";
    }

    /**
     * Generate the Related Models for Many to Many relationship
     *
     * @param string $related
     * @param array $rows
     * @return array
     */
    protected function belongsToManyRelatedModels(string $related, array $rows): array {
        if (count($rows) == 0) {
            return [];
        }

        $models = [];

        for ($i = 0; $i < count($rows); $i++) {
            $models[] = (new $related())->setAttributes($rows[$i]);
        }

        return new Collection($models);
    }

    /**
     *  Create a relationship Many to Many of ownership
     *
     * @param string $related
     * @param string|null $table
     * @param string|null $foreignPivotKey
     * @param string|null $relatedPivotKey
     * @param string|null $parentKey
     * @param string|null $relatedKey
     */
    public function belongsToMany(string $related, ?string $table = null, ?string $foreignPivotKey = null, ?string $relatedPivotKey = null, ?string $parentKey = null, ?string $relatedKey = null) {
        $instance = $this->newRelationInstance($related);

        if (is_null($table)) {
            $table = $this->pivotTableName($related);
        }

        $rows = $instance::$driver->statement(
            $this->sqlBelongsToMany(
                $instance,
                $table,
                $foreignPivotKey ?? $this->getForeignKey(),
                $relatedPivotKey ?? $instance->getForeignKey(),
                $parentKey ?? $this->getKeyName(),
                $relatedKey ?? $instance->getKeyName()
            )
        );

        return $this->belongsToManyRelatedModels($related, $rows);
    }

    /**
     * Create a relationship One to One of ownership
     *
     * @param string $related Instanced Class
     * @param string|null $foreignKey Value for the search in `$related`
     * @param string|null $localKey Column to search for the value in `$related`
     */
    public function hasOne(string $related, ?string $foreignKey = null, ?string $localKey = null) {
        $instance = $this->newRelationInstance($related);

        if (is_null($foreignKey)) {
            $foreignKey = $this->getKey();
        }

        if (is_null($localKey)) {
            $localKey = $this->getForeignKey();
        }

        return $instance::firstWhere($localKey, $foreignKey);
    }
}
