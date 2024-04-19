<?php
namespace Test\Fixture\PhpDocumentor;

use Test\Fixture\PhpDocumentor\Internal\Directory;
use Test\Fixture\PhpDocumentor\Internal\Process;

readonly class PhpDocumentor
{
    private string $phpDocumentor;
    private Directory $working;

    public function __construct(string $workingDirectory)
    {
        $this->phpDocumentor = __DIR__ . '/../../lib/phpDocumentor.phar';
        $this->working = new Directory($workingDirectory);
    }

    public function documentString(string $sourceCode): string
    {
        $this->working->write('file.php', $sourceCode);
        return $this->document($this->working->join('file.php'));
    }

    public function document(string $path): string
    {
        $this->phpDocumentorXml($path);
        return \file_get_contents($this->working->join('output/structure.xml'));
    }

    private function phpDocumentorXml(string $input): void
    {
        if (\is_file($input)) {
            $inputArgs = ['-d', \dirName($input), '-f', \baseName($input)];
        } else {
            $inputArgs = ['-d', $input];
        }
        $this->run([
            $this->phpExecutablePath(),
            $this->phpDocumentor, 'run',
            ...$inputArgs,
            '-t', $this->working->join('output'),
            '--template', 'xml',
        ]);
    }

    private function run(array $shellArguments): void
    {
        $process = new Process($this->working->path, $this->shell($shellArguments));
        if ($process->returnCode !== 0) {
            throw new \RuntimeException("Failed to generate phpDocumentor structure.xml.\n\n$process->stdOutput\n\n$process->stdError");
        }
    }

    private function shell(array $args): string
    {
        return \implode(' ', \array_map('\escapeShellArg', $args));
    }

    private function phpExecutablePath(): string
    {
        if (\DIRECTORY_SEPARATOR === '/') {
            return '/usr/bin/php8.1';
        }
        return 'C:\Program Files\PHP\php-8.1.3\php.exe';
    }
}
