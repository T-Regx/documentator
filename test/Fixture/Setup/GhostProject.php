<?php
namespace Test\Fixture\Setup;

use Documentary\Project;

trait GhostProject
{
    public ProjectWrapper $project;

    /**
     * @before
     */
    public function ghostProject(): void
    {
        $this->project = new ProjectWrapper(new Project(''));
    }
}
