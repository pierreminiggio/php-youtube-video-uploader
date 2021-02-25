<?php

use Illuminate\Support\Str;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use PierreMiniggio\GoogleTokenRefresher\AccessTokenProvider;

$useLikeThis = 'Use like this:'
    . PHP_EOL
    . 'composer run-script pull-categories [locale] [clientId] [clientSecret] [refreshToken]'
;

if (! isset($argv[1])) {
    die($useLikeThis);
}

$locale = $argv[1];

if (! isset($argv[2])) {
    die($useLikeThis);
}

$clientId = $argv[2];

if (! isset($argv[3])) {
    die($useLikeThis);
}

$clientSecret = $argv[3];

if (! isset($argv[4])) {
    die($useLikeThis);
}

$refreshToken = $argv[4];

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$provider = new AccessTokenProvider();
$accessToken = $provider->get($clientId, $clientSecret, $refreshToken);

$curl = curl_init('https://www.googleapis.com/youtube/v3/videoCategories?regionCode=' . $locale);
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json' , 'Authorization: Bearer ' . $accessToken]
]);

$response = curl_exec($curl);
curl_close($curl);

if (empty($response)) {
    die('Error: No response');
}

$jsonResponse = json_decode($response, true);

if (empty($jsonResponse)) {
    die('Error: Bad JSON response');
}

if (empty($jsonResponse['items'])) {
    die('Error: No "items" key in JSON response');
}

$categories = [];

$items = $jsonResponse['items'];

foreach ($items as $item) {
    $categories[strtoupper(Str::slug($item['snippet']['title'], '_'))] = (int) $item['id'];
}

$psr4 = json_decode(
    file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'composer.json'),
    true
)['autoload']['psr-4'];

foreach ($psr4 as $namespace => $folder) {
    break;
}

if (empty($namespace) || empty($folder)) {
    die('Bad composer.json autoload psr-4');
}

$namespace = substr($namespace, 0, -1);
$folder = __DIR__ . DIRECTORY_SEPARATOR . $folder . strtoupper($locale);

$namespace = new PhpNamespace($namespace);
$className = 'CategoryEnum';
$class = $namespace->addClass($className)->setFinal();

foreach ($categories as $constantName => $constantValue) {
    $class->addConstant($constantName, $constantValue);
}

$classString = preg_replace('/[\t]/', '    ', str_replace('const', 'public const', $namespace));

$classFileString = <<<PHP
<?php

$classString
PHP;

if (! file_exists($folder)) {
    mkdir($folder);
}

$classFilename = $folder . DIRECTORY_SEPARATOR . $className . '.php';

file_put_contents($classFilename, $classFileString);
