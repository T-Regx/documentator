<?php
namespace Test\Fixture\PhpDocumentor\Internal;

readonly class Process
{
    public int $returnCode;
    public string $stdOutput;
    public string $stdError;

    public function __construct(
        private string $workingDirectory,
        private string $shellCommand,
    )
    {
        $directory = $this->pipeDirectory();
        $stdOut = $directory . 'stdout.txt';
        $stdError = $directory . 'stderr.txt';
        $this->returnCode = $this->execute($stdOut, $stdError);
        $this->stdOutput = $this->contents($stdOut);
        $this->stdError = $this->contents($stdError);
    }

    private function pipeDirectory(): string
    {
        $directory = \sys_get_temp_dir() . DIRECTORY_SEPARATOR . \uniqId() . DIRECTORY_SEPARATOR;
        \mkDir($directory);
        return $directory;
    }

    private function execute(string $outputFile, string $errorFile): int
    {
        $descriptors = [
            1 => ['file', $outputFile, 'w'],
            2 => ['file', $errorFile, 'w'],
        ];
        $process = \proc_open($this->shellCommand, $descriptors, $pipes, cwd:$this->workingDirectory);
        if ($process === false) {
            throw new \Exception("Failed to spawn process: $this->shellCommand");
        }
        return \proc_close($process);
    }

    private function contents(string $filename): string
    {
        return \file_get_contents($filename);
    }
}
