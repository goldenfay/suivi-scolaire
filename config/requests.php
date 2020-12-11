<?php
use \ParagonIE\Halite\KeyFactory;
use \ParagonIE\Halite\Symmetric\Crypto as SymmetricCrypto;
use ParagonIE\HiddenString\HiddenString;
$key = KeyFactory::generateEncryptionKey();

return [
    'SECRET_KEY' => $key
];