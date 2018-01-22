<?php
namespace blockchain\src;

include('src/blockClass.php');
include('src/transactionClass.php');

class blockchain {
	public $chain          = [];
	protected $totalBlocks = 0;

	/**
	 * Create first block (nemesis block)
	 * @return object Return this class
	 */
	function createFirstBlock() {
		return $this->newBlock('toAddress', 'fromAddress',  500, 'firstBlock', false, 0);
	}

	/**
	 * Get Last block of chain
	 * @return array Array of last block details
	 */
	function lastBlock() {
		// Total blocks in block chain
		if ($this->totalBlocks === 0) {
			return ['hash' => 'fistBlockHash'];
		}

		return $this->chain[$this->totalBlocks-1];
	}

	/**
	 * Create an new block
	 * @param  string   	  $toAddress    Wallet adress of receiver
	 * @param  string   	  $fromAddress  Wallet address of sender
	 * @param  int  		  $amount       Amount that needs to be sended
	 * @param  string   	  $desc         Optional: description for the transaction
	 * @param  string/boolean $trx          Optional: Transaction id
	 * @param  int/boolean 	  $blockId      Block number
	 * @param  int/boolean 	  $time         Time of current created block
	 * @param  string/boolean $previousHash Hash of previous hash
	 * @return object Return this class
	 */
	function newBlock($toAddress, $fromAddress, $amount, $desc = '', $trx = false, $blockId = false, $time = false, $previousHash = false) {

		// Check if we received an Transaction ID
		if (!$trx) {
			// Create one
			// @todo: create on with transaction Claas
			$trx = rand(1,1000);
		}

		// Check if we received an Block ID
		if (!$blockId) {
			$blockId = $this->totalBlocks;
		}

		// Check if we received an Transaction Time
		if (!$time) {
			$time = time();
		}

		// Check if we received an previous hash
		if (!$previousHash) {
			$previousHash = $this->lastBlock()['hash'];
		}

		// Add new block to chain
		$this->chain[] = block::getNewBlock($blockId, $time, $toAddress, $fromAddress, $amount, $desc, $trx, $previousHash);

		$this->totalBlocks++;

		return $this;
	}


	 /**
	  * Check if the block chain is valid and not modified
	  * @return boolean Return an boolean to check if the blockchain is valid
	  */
	function verifyBlockChain() {

		$valid = true;

		for ($b = 1; $b < $this->totalBlocks; $b++) {
			$currentBlock  = $this->chain[$b];
			$previousBlock = $this->chain[$b-1];
			
			// Calculate hash of current block
			$recalculateCurrentBlock = block::getNewBlock(
				$currentBlock['id'], 
				$currentBlock['time'], 
				$currentBlock['toAddress'], 
				$currentBlock['fromAddress'], 
				$currentBlock['amount'], 
				$currentBlock['desc'], 
				$currentBlock['trx'],  
				$previousBlock['hash']
			);

			// Check if current block hash is different then the recalculated hash
			if ($currentBlock['hash'] !== $recalculateCurrentBlock['hash']) {
				$valid = false;
				break;
			// Check if the previous hash is different then the previous block hash
			} elseif ($currentBlock['previousHash'] !== $previousBlock['hash']) {
				$valid = false;
				break;
			}
		}

		return $valid;
	}
}