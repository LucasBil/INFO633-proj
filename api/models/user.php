<?php
require_once __DIR__ . '/enum/role.php';
require_once __DIR__ . '/../utils/entity.php';

class User extends Entity implements JsonSerializable {
    private ?int $id;
    private ?string $email;
    private ?string $password;
    private ?string $first_name;
    private ?string $last_name;
    private ?array $roles;

    public function __construct(string $email, string $password, string $first_name, string $last_name, array $roles, ?int $id = null) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->roles = $roles;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = hash('sha256', $password);
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): void
    {
        $this->last_name = $last_name;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): void
    {
        $this->roles = $roles;
    }

    protected static function getColumns(): array {
        return [
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
                'primary_key' => true,
            ],
            'email' => [
                'type' => 'VARCHAR(255)',
                'unique' => true,
                'not_null' => true,
            ],
            'password' => [
                'type' => 'VARCHAR(255)',
                'not_null' => true,
            ],
            'first_name' => [
                'type' => 'VARCHAR(255)',
                'not_null' => true,
            ],
            'last_name' => [
                'type' => 'VARCHAR(255)',
                'not_null' => true,
            ],
            'roles' => [
                'type' => 'JSON',
                'default' => json_encode([Role::STUDENT->value]),
                'not_null' => true,
            ],
        ];
    }

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'roles' => $this->roles,
        ];
    }
}