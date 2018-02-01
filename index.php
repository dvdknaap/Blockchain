<?php
include 'src/autoload.php';
autoLoader::register();

$blockchain = new blockChain\blockchain();

$time_start = microtime(true);
echo 'done creating first block' . PHP_EOL;
$firstWalletInfo = $blockchain->createFirstBlock(20000000, [
    'email' => 'dannyvdknaap@gmail.com',
]);
print_r($firstWalletInfo);


//execution time of the script
echo 'Total Execution Time: ' . ((microtime(true) - $time_start)) . ' Mins';

$time_start = microtime(true);

echo 'start creating test blockchain' . PHP_EOL;
for ($i = 0; $i < 1000000; $i++) {
    $blockchain->newBlock(rand(1, 2), rand(2, 1), rand(10, 1000), 'Transaction: ' . $i);
}

//execution time of the script
echo 'Total Execution Time: ' . ((microtime(true) - $time_start)) . ' Mins';
echo 'done creating test blockchain' . PHP_EOL;
// print_r($blockchain->chain);

var_dump($blockchain->verifyBlockChain());

echo 'done cerify block chain' . PHP_EOL;
