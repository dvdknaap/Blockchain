<?php
namespace blockchain\src;

class block {

	protected $blockId      = 0;
	protected $time         = 0;
	protected $toAddress    = 0;
	protected $fromAddress  = 0;
	protected $desc         = 0;
	protected $trx          = 0;
	protected $amount       = 0;
	protected $previousHash = 0;
	protected $hash         = '';

	/**
	 * Constructor of block
	 * @param  int		$blockId      Block number
	 * @param  int		time          Time of current created block
	 * @param  string   $toAddress    Wallet adress of receiver
	 * @param  string   $fromAddress  Wallet address of sender
	 * @param  int  	$amount       Amount that needs to be sended
	 * @param  string   $desc         Optional: description for the transaction
	 * @param  string 	$trx          Optional: Transaction id
	 * @param  string 	$previousHash Hash of previous hash
	 */
	function __construct($blockId, $time, $toAddress, $fromAddress, $amount, $desc = '', $trx, $previousHash) {
		$this->blockId      = $blockId;
		$this->time         = $time;
		$this->toAddress    = $toAddress;
		$this->fromAddress  = $fromAddress;
		$this->desc         = $desc;
		$this->trx          = $trx;
		$this->amount       = $amount;
		$this->previousHash = $previousHash;
		$this->hash         = $this->calcHash();
	}

	/**
	 * Get new block
	 * @param  int		$blockId      Block number
	 * @param  int		time          Time of current created block
	 * @param  string  	$toAddress    Wallet adress of receiver
	 * @param  string  	$fromAddress  Wallet address of sender
	 * @param  int  	$amount       Amount that needs to be sended
	 * @param  string  	$desc         Optional: description for the transaction
	 * @param  string 	$trx          Transaction id
	 * @param  string 	$previousHash Hash of previous hash
	 */
	static function getNewBlock($blockId, $time, $toAddress, $fromAddress, $amount, $desc = '', $trx, $previousHash) {
		$newBlock = new self($blockId, $time, $toAddress, $fromAddress, $amount, $desc, $trx, $previousHash);

		return [
			'id'           => $newBlock->blockId,
			'time'         => $newBlock->time,
			'toAddress'    => $newBlock->toAddress,
			'fromAddress'  => $newBlock->fromAddress,
			'amount'       => $newBlock->amount,
			'desc'         => $newBlock->desc,
			'trx'          => $newBlock->trx,
			'previousHash' => $newBlock->previousHash,
			'hash'         => $newBlock->hash,
		];
	}

	/**
	 * Calculate the block hash
	 * @return string Return the block hash
	 */
	function calcHash() {
		$blockHeader = $this->blockId.'-'.$this->time.'-'.$this->toAddress.'-'.$this->fromAddress.'-'.$this->amount.'-'.$this->desc.'-'.$this->trx.'-'.$this->previousHash;

		return hash('sha256', $blockHeader);
	}
}