<?php
require_once __DIR__ . '/../utils/entity.php';

class Deliverable extends Entity implements JsonSerializable {
    private ?int $id;
    private ?string $name;
    private ?string $description;
    private ?DateTime $date_creation;
    private ?DateTime $date_closure;
    private ?int $id_project;

    public function __construct(string $name, string $description, DateTime $date_creation, DateTime $date_closure, int $id_project, ?int $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->date_creation = $date_creation;
        $this->date_closure = $date_closure;
        $this->id_project = $id_project;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): void {
        $this->name = $name;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    public function getDateCreation(): ?DateTime {
        return $this->date_creation;
    }

    public function setDateCreation(?DateTime $date_creation): void {
        $this->date_creation = $date_creation;
    }

    public function getDateClosure(): ?DateTime {
        return $this->date_closure;
    }

    public function setDateClosure(?DateTime $date_closure): void {
        $this->date_closure = $date_closure;
    }

    public function getIdProject(): ?int {
        return $this->id_project;
    }

    public function setIdProject(?int $id_project): void {
        $this->id_project = $id_project;
    }

    protected static function getColumns(): array {
        return [
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
                'primary_key' => true,
            ],
            'name' => [
                'type' => 'VARCHAR(100)',
                'not_null' => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'not_null' => false,
            ],
            'date_creation' => [
                'type' => 'DATETIME',
                'not_null' => true,
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'date_closure' => [
                'type' => 'DATETIME',
                'not_null' => false,
            ],
            'id_project' => [
                'type' => 'INT',
                'not_null' => true,
            ],
        ];
    }

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'date_creation' => $this->date_creation?->format('Y-m-d H:i:s'),
            'date_closure' => $this->date_closure?->format('Y-m-d H:i:s'),
            'id_project' => $this->id_project,
        ];
    }
}
