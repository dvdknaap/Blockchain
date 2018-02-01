<?php
namespace blockChain;

class blockchain
{
    public $chain          = [];
    protected $totalBlocks = 0;
    protected $wallet;
    protected $block;
    protected $lastBlockHash = 'fistBlockHash';

    public function __construct()
    {
        $this->block  = new block();
        $this->wallet = new wallet();
    }
    /**
     * Create first block (nemesis block)
     * @return object Return this class
     */
    public function createFirstBlock(int $walletAmount = 100, array $walletInfo =[])
    {
		$walletEncryptInfo = $this->wallet->createWallet();

		$firstBlock = $this->newBlock($walletEncryptInfo['walletKey'], 'firstWallet', $walletAmount);
		
        return $walletEncryptInfo;
    }

    /**
     * Create an new block
     * @param  string         $toAddress    Wallet adress of receiver
     * @param  string         $fromAddress  Wallet address of sender
     * @param  int            $amount       Amount that needs to be sended
     * @param  string         $desc         Optional: description for the transaction
     * @param  string           $trx          Optional: Transaction id
     * @return object Return this class
     */
    public function newBlock(string $toAddress, string $fromAddress, int $amount, string $desc = '', string $trx = '')
    {

        // Check if we received an Transaction ID
        if (!$trx) {
            // Create one
            // @todo: create on with transaction Claas
            $trx = rand(1, 1000);
        }

        // Get new block to chain
        $newBlock = $this->block->getNewBlock($toAddress, $fromAddress, $amount, $desc, $trx, $this->lastBlockHash);

        $this->chain[]       = $newBlock;
        $this->lastBlockHash = $newBlock['hash'];
        $this->totalBlocks++;

        return $this;
    }

    /**
     * Check if the block chain is valid and not modified
     * @return boolean Return an boolean to check if the blockchain is valid
     */
    public function verifyBlockChain()
    {

        $valid = false;

        for ($b = 1; $b < $this->totalBlocks; $b++) {
            $currentBlock  = $this->chain[$b];
            $previousBlock = $this->chain[$b - 1];

            // Calculate hash of current block
            if ($this->block->checkBlock($currentBlock['hash'],
                $currentBlock['previousHash'],
                $currentBlock['time'],
                $currentBlock['toAddress'],
                $currentBlock['fromAddress'],
                $currentBlock['amount'],
                $currentBlock['desc'],
                $currentBlock['trx'],
                $previousBlock['hash']
            )) {
                $valid = true;
            } else {
                break;
            }
        }

        return $valid;
    }
}
