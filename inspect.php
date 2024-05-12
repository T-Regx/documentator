<?php
require 'vendor/autoload.php';

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('C:\Users\Daniel\PhpstormProjects\pattern\src'));
$files = [];

/** @var SplFileInfo $file */
foreach ($rii as $file) {
    if ($file->isDir()) {
        continue;
    }

    $docum = new \Documentary\Inspect\Documentation();
    $dupa = $docum->documented(\file_get_contents($file->getPathname()));

    $files[] = $dupa;
}

$dupa = array_merge(...$files);
uasort($dupa, function (array $arr1, array $arr2): int {
    return \count($arr2) - \count($arr1);
});
$dupa2 = [];
foreach ($dupa as $className => $methods) {
    if (\strPos($className, 'Internal') === false) {
        $dupa2[$className] = $methods;
    }
}
unset($dupa);

var_dump($dupa2);
