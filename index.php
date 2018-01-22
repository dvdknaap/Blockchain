<?php
include('src/blockchainClass.php');

$blockchain = new blockchain\src\blockchain();

$blockchain->createFirstBlock();

for ($i = 0; $i < 1000000; $i++) {
	$blockchain->newBlock(rand(1,2), rand(2,1), rand(10,1000), 'Transaction: '.$i);	
}

echo 'done creating test blockchain'.PHP_EOL;
// print_r($blockchain->chain);

var_dump($blockchain->verifyBlockChain());

echo 'done cerify block chain'.PHP_EOL;