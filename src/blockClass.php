<?php
namespace blockChain;

class block
{

    /**
     * Get new block
     * @param  string      $toAddress    Wallet adress of receiver
     * @param  string      $fromAddress  Wallet address of sender
     * @param  int      $amount       Amount that needs to be sended
     * @param  string      $desc         Optional: description for the transaction
     * @param  string     $trx          Transaction id
     * @param  string     $previousHash Hash of previous hash
     */
    public function getNewBlock(string $toAddress, string $fromAddress, int $amount, string $desc = '', string $trx = '', string $previousHash = ''): array
    {

        $time = time();

        return [
            'time'         => $time,
            'toAddress'    => $toAddress,
            'fromAddress'  => $fromAddress,
            'amount'       => $amount,
            'desc'         => $desc,
            'trx'          => $trx,
            'previousHash' => $previousHash,
            'hash'         => self::calcHash($time, $toAddress, $fromAddress, $amount, $desc, $trx, $previousHash),
        ];
    }

    /**
     * Check if block is valid
     * @param  string     $currentBlockHash Current block hash
     * @param  string     $currentBlockpreviousHash current block previous hash
     * @param  int        time          Time of current created block
     * @param  string      $toAddress    Wallet adress of receiver
     * @param  string      $fromAddress  Wallet address of sender
     * @param  int      $amount       Amount that needs to be sended
     * @param  string      $desc         Optional: description for the transaction
     * @param  string     $trx          Transaction id
     * @param  string     $previousHash Hash of previous hash
     */
    public function checkBlock(string $currentBlockHash, string $currentBlockpreviousHash, int $time, string $toAddress, string $fromAddress, int $amount, string $desc = '', string $trx = '', string $previousHash = ''): bool
    {

        // Check if current block hash is different then the recalculated hash
        if ($currentBlockHash !== self::calcHash($time, $toAddress, $fromAddress, $amount, $desc, $trx, $previousHash)) {
            return false;
            // Check if the previous hash is different then the previous block hash
        } elseif ($currentBlockpreviousHash !== $previousHash) {
            return false;
        }

        return true;
    }

    /**
     * Calculate the block hash
     * @return string Return the block hash
     */
    private static function calcHash(int $time, string $toAddress, string $fromAddress, int $amount, string $desc = '', string $trx = '', string $previousHash = ''): string
    {
        $blockHeader = (string) $time . '-' .
            $toAddress . '-' .
            $fromAddress . '-' .
            $amount . '-' .
            $desc . '-' .
            $trx . '-' .
            $previousHash
        ;

        return hash('sha256', $blockHeader);
    }
}
