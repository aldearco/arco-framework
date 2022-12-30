<?php

namespace Arco\Auth\Access;

use Arco\Database\Archer\Model;
use Arco\Auth\Access\Exceptions\AuthorizeRequiresPolicyNameException;
use Arco\Auth\Access\Exceptions\PolicyDoesNotExistsException;
use Arco\Auth\Access\Exceptions\PolicyMethodNotFoundException;

trait Gatekeeper {
    protected function isSpecificPolicy(string $policyClass) {
        if (str_contains(class_basename($policyClass), 'Policy')) {
            return new $policyClass();
        }

        throw new PolicyDoesNotExistsException();
    }

    protected function isResourcePolicy($arguments) {
        if (!is_object($arguments) && is_subclass_of(get_class($arguments), Model::class)) {
            throw new AuthorizeRequiresPolicyNameException('This authorization requires explicit policy class when is called.');
        }

        $policyClass = 'App\\Policies\\'.class_basename($arguments).'Policy';
        return new $policyClass();
    }


    protected function instancePolicy(array|object $arguments, ?string $policyClass = null) {
        if (is_null($policyClass)) {
            return $this->isResourcePolicy($arguments);
        } else {
            return $this->isSpecificPolicy($policyClass);
        }
    }

    protected function can($policy, $method, $arguments) {
        return $policy->$method($arguments) ? true : $policy->deny($method);
    }

    protected function authorize(string $method, array|object $arguments = [], ?string $policyClass = null) {
        $policy = $this->instancePolicy($arguments, $policyClass);

        return $this->can($policy, $method, $arguments);
    }
}
