<?php

namespace IRFANM\SIASHAF\Repository;

use IRFANM\SIASHAF\Domain\User;
use PDO;
use PDOException;

class UserRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAll(): array
    {
        try {
            $statement = $this->connection->query("SELECT * FROM users WHERE deleted_at IS NULL");
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            echo "Error saat mengambil data users: " . $err->getMessage();
            return [];
        }
    }

    public function save(User $user): User
    {
        try {
            $statement = $this->connection->prepare("
                INSERT INTO users (user_id, username, password, role, created_at, updated_at, deleted_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $statement->execute([
                $user->user_id,
                $user->username,
                $user->password,
                $user->role,
                $user->created_at ?? date('Y-m-d H:i:s'),
                $user->updated_at ?? date('Y-m-d H:i:s'),
                $user->deleted_at,
            ]);

            return $user;
        } catch (PDOException $err) {
            echo "Error saat menyimpan user: " . $err->getMessage();
            return $user;
        }
    }

    public function update(User $user): User
    {
        try {
            $statement = $this->connection->prepare("
                UPDATE users 
                SET username = ?, password = ?, role = ?, updated_at = ? 
                WHERE user_id = ? AND deleted_at IS NULL
            ");

            $statement->execute([
                $user->username,
                $user->password,
                $user->role,
                $user->updated_at ?? date('Y-m-d H:i:s'),
                $user->user_id,
            ]);

            return $user;
        } catch (PDOException $err) {
            echo "Error saat memperbarui user: " . $err->getMessage();
            return $user;
        }
    }

    public function findById(string $user_id): ?User
    {
        try {
            $statement = $this->connection->prepare("
                SELECT * FROM users WHERE user_id = ? AND deleted_at IS NULL
            ");
            $statement->execute([$user_id]);

            if ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $user = new User();
                $user->user_id = $row['user_id'];
                $user->username = $row['username'];
                $user->password = $row['password'];
                $user->role = $row['role'];
                $user->created_at = $row['created_at'];
                $user->updated_at = $row['updated_at'];
                $user->deleted_at = $row['deleted_at'];

                return $user;
            } else {
                return null;
            }
        } catch (PDOException $err) {
            echo "Error saat mencari user: " . $err->getMessage();
            return null;
        }
    }

    public function deleteById(User $user): bool
    {
        try {
            $statement = $this->connection->prepare("
                UPDATE users 
                SET deleted_at = ? 
                WHERE user_id = ? AND deleted_at IS NULL
            ");

            return $statement->execute([
                $user->deleted_at ?? date('Y-m-d H:i:s'),
                $user->user_id,
            ]);
        } catch (PDOException $err) {
            echo "Error saat menghapus user: " . $err->getMessage();
            return false;
        }
    }

    public function deleteAll(): bool
    {
        try {
            return $this->connection->exec("UPDATE users SET deleted_at = NOW() WHERE deleted_at IS NULL") !== false;
        } catch (PDOException $err) {
            echo "Error saat menghapus semua user: " . $err->getMessage();
            return false;
        }
    }

    public function deletePermanentlyById(string $user_id): bool
    {
        try {
            $statement = $this->connection->prepare("DELETE FROM users WHERE user_id = ?");
            return $statement->execute([$user_id]);
        } catch (PDOException $err) {
            echo "Error saat menghapus data secara permanen: " . $err->getMessage();
            return false;
        }
    }

    public function deleteAllPermanently(): bool
    {
        try {
            return $this->connection->exec("DELETE FROM users") !== false;
        } catch (PDOException $err) {
            echo "Error saat menghapus semua data secara permanen: " . $err->getMessage();
            return false;
        }
    }
}
