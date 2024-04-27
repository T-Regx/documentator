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
    ): void
    {
        $this->project->addSummary($memberName, $summary, $description, $type);
        $this->project->build();
    }

    public function summary(string $memberName, string $summary, string $type = null): void
    {
        $this->project->addSummary($memberName, $summary, null, $type);
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
