<?php

namespace Arco\Database\Archer\About;

trait Relations {
    protected static array $relatedInstances = [];

    protected function newRelationInstance(string $class) {
        if (!array_key_exists($class, self::$relatedInstances)) {
            self::$relatedInstances[$class] = new $class();
        }

        return self::$relatedInstances[$class];
    }

    /**
     * Create a relationship One to Many of ownership
     *
     * @param string $class Instanced class
     * @param [type] $foreignKey Value for the search in `$class`
     * @param [type] $localKey Column to search for the value in `$class`
     */
    public function hasMany(string $class, $foreignKey = null, $localKey = null) {
        $instance = $this->newRelationInstance($class);

        if (is_null($foreignKey)) {
            $foreignKey = $this->getPrimaryKeyAttribute();
        }

        if (is_null($localKey)) {
            $localKey = snake_case($this->getBasename())."_".$this->getPrimaryKey();
        }

        return $instance::where($localKey, $foreignKey);
    }

    /**
     * Create a relationship One to One of propierty
     *
     * @param string $class Instanced class
     * @param [type] $foreignKey Value for the search in `$class`
     * @param [type] $localKey Column to search for the value in `$class`
     */
    public function belongsTo(string $class, $foreignKey = null, $localKey = null) {
        $instance = $this->newRelationInstance($class);

        if (is_null($foreignKey)) {
            $foreignKeyName = snake_case($instance->getBasename())."_".$instance->getPrimaryKey();
            $foreignKey = $this->attributes[$foreignKeyName];
        }

        if (is_null($localKey)) {
            $localKey = $instance->getPrimaryKey();
        }

        return $instance::firstWhere($localKey, $foreignKey);
    }

    /**
     * Create a relationship One to One of ownership
     *
     * @param string $class Instanced Class
     * @param [type] $foreignKey Value for the search in `$class`
     * @param [type] $localKey Column to search for the value in `$class`
     */
    public function hasOne(string $class, $foreignKey = null, $localKey = null) {
        $instance = $this->newRelationInstance($class);

        if (is_null($foreignKey)) {
            $foreignKey = $this->getPrimaryKeyAttribute();
        }

        if (is_null($localKey)) {
            $localKey = snake_case($this->getBasename())."_".$this->getPrimaryKey();
        }

        return $instance::firstWhere($localKey, $foreignKey);
    }
}
