<?php

namespace Arco\Validation\Rules;

use Arco\Database\DB;

class Unique implements ValidationRule {
    public function __construct(
        private string $params
    ) {
        $this->params = $params;
    }


    public function message(): string {
        return "A record already exists with the entered value.";
    }

    public function isValid(string $field, array $data): bool {
        if (empty($data[$field])) {
            return false;
        }

        $params = explode('.', $this->params);

        switch (count($params)) {
            case 1:
                $query = "SELECT $field FROM $params[0] WHERE $field=?";
                break;
            case 2:
                $query = "SELECT $params[1] FROM $params[0] WHERE $params[1]=?";
                break;
            default:
                throw new RuleParseException('Unique Rule only accepts 2 params. \'unique:table.column\'');
                break;
        }


        $match = DB::statement($query, [$data[$field]]);

        // var_dump($match); die;
        return count($match) === 0;
    }
}
