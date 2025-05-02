<?php
require_once __DIR__ . '/../utils/entity.php';

class Document extends Entity implements JsonSerializable {
    private ?int $id;
    private ?string $name;
    private ?string $date_deposition;
    private ?string $data;
    private ?string $file_type;
    private ?int $id_user;
    private ?int $id_deliverable;

    public function __construct(string $name, string $date_deposition, string $data, string $file_type, int $id_user, int $id_deliverable, ?int $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->date_deposition = $date_deposition ?? date('Y-m-d H:i:s');
        $this->data = $data;
        $this->file_type = $file_type;
        $this->id_user = $id_user;
        $this->id_deliverable = $id_deliverable;
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

    public function getDateDeposition(): ?string {
        return $this->date_deposition;
    }

    public function setDateDeposition(?string $date_deposition): void {
        $this->date_deposition = $date_deposition;
    }

    public function getData(): ?string {
        return $this->data;
    }

    public function setData(?string $data): void {
        $this->data = $data;
    }

    public function getFileType(): ?string {
        return $this->file_type;
    }

    public function setFileType(?string $file_type): void {
        $this->file_type = $file_type;
    }

    public function getIdUser(): ?int {
        return $this->id_user;
    }

    public function setIdUser(?int $id_user): void {
        $this->id_user = $id_user;
    }

    public function getIdDeliverable(): ?int {
        return $this->id_deliverable;
    }

    public function setIdDeliverable(?int $id_deliverable): void {
        $this->id_deliverable = $id_deliverable;
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
            'date_deposition' => [
                'type' => 'DATETIME',
                'not_null' => true,
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'data' => [
                'type' => 'VARCHAR(150)',
                'not_null' => true,
            ],
            'file_type' => [
                'type' => 'VARCHAR(50)',
                'not_null' => false,
            ],
            'id_user' => [
                'type' => 'INT',
                'not_null' => true,
            ],
            'id_deliverable' => [
                'type' => 'INT',
                'not_null' => true,
            ],
        ];
    }

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'date_deposition' => $this->date_deposition,
            'data' => $this->data,
            'file_type' => $this->file_type,
            'id_user' => $this->id_user,
            'id_deliverable' => $this->id_deliverable,
        ];
    }
}
