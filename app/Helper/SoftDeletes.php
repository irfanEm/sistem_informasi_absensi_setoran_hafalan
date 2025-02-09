<?php

namespace IRFANM\SIASHAF\Helper;

use IRFANM\SIASHAF\Domain\Teacher;
use IRFANM\SIASHAF\Domain\User;
use PDO;
use SebastianBergmann\Type\MixedType;

trait SoftDeletes
{
    private function getTableName(): string
    {
        if (!defined('static::TABLE_NAME')) {
            throw new \Exception("Constant TABLE_NAME is not defined in the class using SoftDeletes.");
        }
        return static::TABLE_NAME;
    }

    public function getAllActive(): array
    {
        return $this->getAll("WHERE deleted_at IS NULL");
    }

    public function getAllDeleted(): array
    {
        return $this->getAll("WHERE deleted_at IS NOT NULL");
    }

    public function deleteSoftly(string $user_id): bool
    {
        try {
            $statement = $this->connection->prepare("
                UPDATE " . $this->getTableName() . " 
                SET deleted_at = ? 
                WHERE user_id = ? AND deleted_at IS NULL
            ");
            return $statement->execute([date('Y-m-d H:i:s'), $user_id]);
        } catch (\PDOException $err) {
            error_log("Error saat soft delete: " . $err->getMessage());
            return false;
        }
    }
    public function findSoftDeleted(string $user_id)
    {
        try {
            $statement = $this->connection->prepare("
                SELECT * FROM " . $this->getTableName() . " 
                WHERE user_id = ? AND deleted_at IS NOT NULL
            ");
            $statement->execute([$user_id]);
    
            if($row = $statement->fetch(PDO::FETCH_ASSOC)){

                return $this->mapRowToData($row);
            }

            return null;
        } catch (\PDOException $err) {
            error_log("Error saat mencari user yang terhapus: " . $err->getMessage());
            return null;
        }
    }
    
    public function restoreSoftDeleted(string $user_id): bool
    {
        try {
            $statement = $this->connection->prepare("
                UPDATE " . $this->getTableName() . " 
                SET deleted_at = NULL 
                WHERE user_id = ? AND deleted_at IS NOT NULL
            ");
            return $statement->execute([$user_id]);
        } catch (\PDOException $err) {
            error_log("Error saat restore data: " . $err->getMessage());
            return false;
        }
    }

    public function deleteAllSoftly(): bool
    {
        try {
            $statement = $this->connection->prepare("
                UPDATE " . $this->getTableName() . " 
                SET deleted_at = ?
                WHERE deleted_at IS NULL
            ");
            return $statement->execute([date('Y-m-d H:i:s')]);
        } catch (\PDOException $err) {
            error_log("Error saat soft delete semua data: " . $err->getMessage());
            return false;
        }
    }

    public function restoreAllSoftDeleted(): bool
    {
        try {
            $statement = $this->connection->prepare("
                UPDATE " . $this->getTableName() . " 
                SET deleted_at = NULL 
                WHERE deleted_at IS NOT NULL
            ");
            return $statement->execute();
        } catch (\PDOException $err) {
            error_log("Error saat restore semua data: " . $err->getMessage());
            return false;
        }
    }
}
