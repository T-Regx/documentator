<?php
namespace Documentary;

readonly class ProjectClass
{
    public function __construct(private Project $project, private string $parentName)
    {
    }

    public function addSummary(string $summary): void
    {
        $this->project->addSummary($this->parentName, $summary, type:'class');
    }

    public function addMethodSummary(string $name, string $summary): void
    {
        $this->addMemberSummary($name, $summary, 'method');
    }

    public function addConstantSummary(string $name, string $summary): void
    {
        $this->addMemberSummary($name, $summary, 'constant');
    }

    public function addPropertySummary(string $name, string $summary): void
    {
        $this->addMemberSummary($name, $summary, 'property');
    }

    private function addMemberSummary(string $name, string $summary, string $type): void
    {
        $this->project->addSummary(
            $name,
            $summary,
            type:$type,
            parent:$this->parentName);
    }
}
