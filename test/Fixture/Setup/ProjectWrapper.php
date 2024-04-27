<?php
namespace Test\Fixture\Setup;

use Documentary\Project;

readonly class ProjectWrapper
{
    public function __construct(public Project $project)
    {
    }

    public function singleSummary(string $memberName, string $summary, string $description = null): void
    {
        $this->project->addSummary($memberName, $summary, $description);
        $this->project->build();
    }

    public function summary(string $memberName, string $summary): void
    {
        $this->project->addSummary($memberName, $summary, null);
    }

    public function hide(string $memberName): void
    {
        $this->project->hide($memberName);
    }

    public function build(): void
    {
        $this->project->build();
    }
}
