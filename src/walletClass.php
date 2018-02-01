<?php
namespace blockChain;

class wallet
{
    public $wallets         = [];
    private $cipher         = 'AES-256-CBC';
    private $privateKeySize = 16; // 128 bits
    private $walletKeySize  = 16; // 128 bits

    public function __construct()
    {

    }

    private function pkcs7Pad($data, $size): string
    {
        $length = $size - strlen($data) % $size;
        return $data . str_repeat(chr($length), $length);
    }

    private function generatePrivateKey(): string
    {
        $privateKey = openssl_random_pseudo_bytes($this->privateKeySize, $strong);
        if (!$strong) {
            return $this->generatePrivateKey();
        }

        return $privateKey;
    }

    private function generateWalletKey(): string
    {
        $walletKey = openssl_random_pseudo_bytes($this->walletKeySize, $strong);
        if (!$strong) {
            return $this->generateWalletKey();
        }

        return $walletKey;
    }

    private function encrypt(string $walletKey = '', string $privateKey = '', array $walletInfo = []): array
    {
        if (empty($walletKey)) {
            $walletKey = $this->generatePrivateKey();
        }

        if (empty($privateKey)) {
            $privateKey = $this->generatePrivateKey();
        }

        $walletEncryptedMessage = json_encode($walletInfo);

        $encryptedWallet = openssl_encrypt(
            $this->pkcs7Pad($walletEncryptedMessage, 256), // padded data
            $this->cipher, // cipher and mode
            $privateKey, // secret key
            0, // options (not used)
            $walletKey // initialisation vector
        );

        $this->wallets[$walletKey] = $encryptedWallet;

        return [
            'walletKey'  => str_replace('=', '', base64_encode($walletKey)),
            'privateKey' => str_replace('=', '', base64_encode($privateKey)),
        ];
    }

    private function pkcs7Unpad($data): string
    {
        return substr($data, 0, -ord($data[strlen($data) - 1]));
    }

    public function decrypt(string $walletKey = '', string $privateKey = ''): array
    {
      $walletKey  = base64_decode($walletKey);
      $privateKey = base64_decode($privateKey);
      
        $walletEncryptedMessage = $this->wallets[$walletKey];

        $walletInfo = $this->pkcs7Unpad(openssl_decrypt(
            $walletEncryptedMessage,
            $this->cipher,
            $privateKey,
            0,
            $walletKey
        ));

        return json_decode($walletInfo, 1);
    }

    public function createWallet(array $walletInfo = []): array
    {
        $walletEncryptInfo = $this->encrypt('', '', $walletInfo);

        return $walletEncryptInfo;
    }
}
