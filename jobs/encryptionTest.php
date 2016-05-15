<?php
$config = array(
    "digest_alg" => "sha512",
    "private_key_bits" => 1024,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
);
    
// Create the private and public key
$res = openssl_pkey_new($config);

// Extract the private key from $res to $privKey
openssl_pkey_export($res, $privKey);
echo $privKey.'<br>';

// Extract the public key from $res to $pubKey
$pubKey = openssl_pkey_get_details($res);
$pubKey = $pubKey["key"];
echo $pubKey .'<br>';

$data = 'your data here!';

// Encrypt the data to $encrypted using the public key
for($i=0; $i<10; $i++){
openssl_public_encrypt($data, $encrypted, $pubKey);
$data = $encrypted;
}

// Decrypt the data using the private key and store the results in $decrypted
for($i=0; $i<3; $i++){
openssl_private_decrypt($encrypted, $decrypted, $privKey);
$encrypted =  $decrypted;
}

echo $decrypted;
?>