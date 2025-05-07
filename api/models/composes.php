<?php
require_once __DIR__ . '/../utils/entity.php';
require_once __DIR__ . '/enum/condition.php';
require_once __DIR__ . '/project.php';
require_once __DIR__ . '/asset.php';

class Composes extends Entity implements JsonSerializable {
    private ?int $id_project;
    private ?int $id_asset;
    private ?Condition $condition;
    private ?string $comment;

    private ?Project $project;
    private ?Asset $asset;

    public function __construct(int $id_project, int $id_asset, Condition $condition, ?string $comment) {
        $this->id_project = $id_project;
        $this->id_asset = $id_asset;
        $this->condition = $condition;
        $this->comment = $comment;
    }

    public function getIdProject(): ?int
    {
        return $this->id_project;
    }

    public function setIdProject(?int $id_project): void
    {
        $this->id_project = $id_project;
    }

    public function getIdAsset(): ?int
    {
        return $this->id_asset;
    }

    public function setIdAsset(?int $id_asset): void
    {
        $this->id_asset = $id_asset;
    }

    public function getCondition(): ?Condition
    {
        return $this->condition;
    }

    public function setCondition(?Condition $condition): void
    {
        $this->condition = $condition;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): void
    {
        $this->project = $project;
    }

    public function getAsset(): ?Asset
    {
        return $this->asset;
    }

    public function setAsset(?Asset $asset): void
    {
        $this->asset = $asset;
    }

    protected static function getColumns(): array {
        return [
            'id_project' => [
                'type' => 'INT',
                'primary_key' => true,
                'foreign_key' => [
                    'table' => Project::getTableName(),
                    'column' => 'id',
                ],
            ],
            'id_asset' => [
                'type' => 'INT',
                'primary_key' => true,
                'foreign_key' => [
                    'table' => Asset::getTableName(),
                    'column' => 'id',
                ],
            ],
            'condition' => [
                'type' => 'VARCHAR(100)',
                'not_null' => true,
            ],
            'comment' => [
                'type' => 'TEXT',
                'not_null' => false,
            ],
        ];
    }

    public function jsonSerialize(): array {
        return [
            'project' => $this->project,
            'asset' => $this->asset,
            'condition' => $this->condition,
            'comment' => $this->comment
        ];
    }
}