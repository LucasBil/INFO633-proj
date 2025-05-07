<?php
require_once __DIR__ . '/../utils/entity.php';
require_once __DIR__ . '/enum/assetState.php';

class Asset extends Entity implements JsonSerializable {
    private ?int $id;
    private ?string $name;
    private ?AssetState $state;
    private ?string $numSerie;

    public function __construct(string $name, AssetState $state, ?string $numSerie, ?int $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->state = $state;
        $this->numSerie = $numSerie;
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
    public function getState(): ?AssetState {
        return $this->state;
    }
    public function setState(?AssetState $state): void {
        $this->state = $state;
    }
    public function getNumSerie(): ?string {
        return $this->numSerie;
    }
    public function setNumSerie(?string $numSerie): void {
        $this->numSerie = $numSerie;
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
            'state' => [
                'type' => 'VARCHAR(100)',
                'not_null' => true,
            ],
            'numSerie' => [
                'type' => 'VARCHAR(100)',
                'not_null' => false,
            ],
        ];
    }

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'state' => $this->state,
            'numSerie' => $this->numSerie
        ];
    }
}