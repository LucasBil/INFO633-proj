<?php
require_once __DIR__ . '/../utils/entity.php';
require_once __DIR__ . '/user.php';
require_once __DIR__ . '/project.php';

class Work extends Entity implements JsonSerializable {
    private ?int $id_user;
    private ?int $id_project;

    private ?User $user = null;
    private ?Project $project = null;

    public function __construct(?int $id_user, ?int $id_project) {
        $this->id_user = $id_user;
        $this->id_project = $id_project;
    }

    public function getIdUser(): ?int {
        return $this->id_user;
    }

    public function setIdUser(?int $id_user): void {
        $this->id_user = $id_user;
    }

    public function getIdProject(): ?int {
        return $this->id_project;
    }

    public function setIdProject(?int $id_project): void {
        $this->id_project = $id_project;
    }

    public function getProject(): ?Project {
        return $this->project;
    }

    public function setProject(?Project $project): void {
        $this->project = $project;
    }

    public function getUser(): ?User {
        return $this->user;
    }

    public function setUser(?User $user): void {
        $this->user = $user;
    }

    protected static function getColumns(): array {
        return [
            'id_user' => [
                'type' => 'INT',
                'primary_key' => true,
                'not_null' => true,
                'foreign_key' => [
                    'table' => User::getTableName(),
                    'column' => 'id'
                ]
            ],
            'id_project' => [
                'type' => 'INT',
                'primary_key' => true,
                'not_null' => true,
                'foreign_key' => [
                    'table' => Project::getTableName(),
                    'column' => 'id'
                ]
            ]
        ];
    }

    public function jsonSerialize(): array {
        return [
            'user' => $this->user,
            'project' => $this->project,
        ];
    }
}
