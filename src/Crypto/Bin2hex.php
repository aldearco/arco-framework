<?php

namespace Arco\Crypto;

class Bin2hex implements Hasher {
    public function hash(string $input): string {
        return bin2hex($input);
    }

    public function verify(string $input, string $hash): bool {
        return $hash === bin2hex($input);
    }

    public static function random() {
        return bin2hex(random_bytes(32));
    }
}
