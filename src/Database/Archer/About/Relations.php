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


    public function hasMany(string $class, $foreignKey = null, $localKey = null) {
        $instance = $this->newRelationInstance($class);

        if (is_null($foreignKey)) {
            $foreignKey = $this->getId();
        }

        if (is_null($localKey)) {
            $localKey = snake_case($this->getBasename())."_".$this->getPrimaryKey();
        }

        return $instance::where($localKey, $foreignKey);
    }

    public function belongsTo(string $class, $relatedKey = null) {
        $instance = $this->newRelationInstance($class);

        if (is_null($relatedKey)) {
            $relatedKey = snake_case($instance->getBasename())."_".$instance->getPrimaryKey();
        }

        return $instance::find($this->attributes[$relatedKey]);
    }

    public function hasOne(string $class, $foreignKey = null, $relatedKey = null) {
        $instance = $this->newRelationInstance($class);

        if (is_null($foreignKey)) {
            $foreignKey = $this->getId();
        }

        if (is_null($relatedKey)) {
            $relatedKey = snake_case($instance->getBasename())."_".$this->getPrimaryKey();
        }

        return $instance::find($this->attributes[$relatedKey]);
    }
}
