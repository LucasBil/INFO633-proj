<?php
require_once __DIR__ . '/enum/projectStatus.php';
require_once __DIR__ . '/../utils/entity.php';
require_once __DIR__ . '/user.php';

class Project extends Entity implements JsonSerializable {
    private ?int $id;
    private ?string $name;
    private ?string $description;
    private ?string $status;
    private ?int $year;
    private ?string $duration;
    private ?int $id_creator;

    private ?User $creator;

    public function __construct(string $name, string $description, string $status, int $year, string $duration, ?int $id_creator=null, ?int $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->status = $status;
        $this->year = $year;
        $this->duration = $duration;
        $this->id_creator = $id_creator;
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

    public function getStatus(): ?string {
        return $this->status;
    }

    public function setStatus(?string $status): void {
        $this->status = $status;
    }

    public function getYear(): ?int {
        return $this->year;
    }

    public function setYear(?int $year): void {
        $this->year = $year;
    }

    public function getDuration(): ?string {
        return $this->duration;
    }

    public function setDuration(?string $duration): void {
        $this->duration = $duration;
    }

    public function getIdCreator(): ?int {
        return $this->id_creator;
    }

    public function setIdCreator(?int $id_creator): void {
        $this->id_creator = $id_creator;
    }

    public function getCreator(): ?User {
        return $this->creator;
    }
    public function setCreator(?User $creator): void {
        $this->id_creator = $creator->getId();
        $this->creator = $creator;
    }

    protected static function getColumns(): array {
        return [
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
                'primary_key' => true
            ],
            'name' => [
                'type' => 'VARCHAR(100)',
                'not_null' => true
            ],
            'description' => [
                'type' => 'TEXT'
            ],
            'status' => [
                'type' => 'VARCHAR(50)',
                'not_null' => true,
                'default' => ProjectStatus::NOT_STARTED->value
            ],
            'year' => [
                'type' => 'INT',
                'not_null' => true
            ],
            'duration' => [
                'type' => 'TIME',
                'not_null' => true
            ],
            'id_creator' => [
                'type' => 'INT',
                'not_null' => true,
                'foreign_key' => [
                    'table' => User::getTableName(),
                    'column' => 'id'
                ]
            ],
        ];
    }

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'year' => $this->year,
            'duration' => $this->duration,
            'creator' => $this->creator ?? null
        ];
    }
}
