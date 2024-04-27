<?php
namespace Test\Fixture\Setup;

use Documentary\Project;
use Test\Fixture\File\File;

trait SingleFileProject
{
    public FileWrapper $file;
    public ProjectWrapper $project;
    public Preview $preview;

    /**
     * @before
     */
    public function singleFileProject(): void
    {
        $file = File::temporaryDirectory()->join('file.php');
        $this->file = new FileWrapper($file);
        $this->project = new ProjectWrapper(new Project($file->path));
        $this->preview = new Preview($file);
    }
}
