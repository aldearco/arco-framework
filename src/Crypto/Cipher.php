<?php

namespace Arco\Crypto;

class Cipher {
    public static function encrypt($plaintext) {
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, config("app.key"), $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, config("app.key"), $as_binary=true);
        return base64_encode($iv.$hmac.$ciphertext_raw);
    }

    public static function decrypt($ciphertext) {
        $c = base64_decode($ciphertext);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, config("app.key"), $options=OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, config("app.key"), $as_binary=true);
        if (hash_equals($hmac, $calcmac)) {
            return $original_plaintext;
        }
    }
}
