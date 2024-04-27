<?php
namespace Test\Fixture\Setup;

use Documentary\Project;

readonly class ProjectWrapper
{
    public function __construct(public Project $project)
    {
    }

    public function singleSummary(
        string $memberName,
        string $summary,
        string $description = null,
        string $type = null,
        string $parent = null,
    ): void
    {
        $this->project->addSummary($memberName,
            $summary, $description, $type, $parent);
        $this->project->build();
    }

    public function summary(string $memberName, string $summary, string $type = null, string $parent = null): void
    {
        $this->project->addSummary($memberName,
            $summary, null, $type, $parent);
    }

    public function hide(string $memberName, string $type = null): void
    {
        $this->project->hide($memberName, $type);
    }

    public function build(): void
    {
        $this->project->build();
    }
}
