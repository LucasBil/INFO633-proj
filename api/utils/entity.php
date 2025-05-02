<?php
require_once __DIR__ . '/pdo.php';

abstract class Entity
{
    /**
     * Crée la table correspondante à l'entité
     * 
     * @return bool True si la table a été créée avec succès
     * @throws PDOException Si la création échoue
     */
    public static function createTable(): bool
    {
        $tableName = self::getTableName();
    
        $query = "CREATE TABLE IF NOT EXISTS `$tableName` (";
        $primaryKeys = [];
        $uniqueColumns = [];

        foreach (static::getColumns() as $columnName => $options) {
            $query .= "`$columnName` " . $options['type'];

            // Gestion des options de colonne
            if (isset($options['auto_increment']) && $options['auto_increment']) {
                $query .= " AUTO_INCREMENT";
            }
            if (isset($options['default'])) {
                $default = is_array($options['default']) 
                    ? "'" . json_encode($options['default']) . "'"
                    : "'" . $options['default'] . "'";
                $query .= " DEFAULT " . $default;
            }
            if (isset($options['not_null']) && $options['not_null']) {
                $query .= " NOT NULL";
            }
            if (isset($options['primary_key']) && $options['primary_key']) {
                $primaryKeys[] = $columnName;
            }
            if (isset($options['unique']) && $options['unique']) {
                $uniqueColumns[] = $columnName;
            }
            $query .= ", ";
        }

        // Ajout des clés primaires
        if (!empty($primaryKeys)) {
            $query .= "PRIMARY KEY (`" . implode("`, `", $primaryKeys) . "`), ";
        }
        // Ajout des contraintes UNIQUE
        foreach ($uniqueColumns as $uniqueColumn) {
            $query .= "UNIQUE KEY `uniq_$uniqueColumn` (`$uniqueColumn`), ";
        }
        $query = rtrim($query, ', ');
        $query .= ");";
        return DBAManager::getInstance()->exec($query) !== false;
    }

    protected static function camelToSnakeCase(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    public static function getTableName(): string
    {
        $reflection = new ReflectionClass(static::class);
        return self::camelToSnakeCase($reflection->getShortName());
    }

    protected abstract static function getColumns(): array;
}