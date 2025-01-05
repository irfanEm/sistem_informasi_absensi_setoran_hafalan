<?php

namespace IRFANM\SIASHAF\Repository;

use IRFANM\SIASHAF\Domain\User;
use \PDO;

class UserRepository
{
    private PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function simpan(User $user): User
    {
        $statement = $this->connection->prepare("INSERT INTO users (user_id, username, password, role, created_at, updated_at, deleted_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $statement->execute([
            $user->user_id,
            $user->username,
            $user->password,
            $user->role,
            $user->created_at,
            $user->updated_at,
            $user->deleted_at,
        ]);

        return $user;
    }

    public function update(User $user): User 
    {
        $statement = $this->connection->prepare("UPDATE users set username = ?, password = ?, role = ?, updated_at = ? WHERE user_id = ?");
        $statement->execute([
            $user->username,
            $user->password,
            $user->role,
            $user->updated_at,
            $user->user_id
        ]);

        return $user;
    }

    public function findById(string $user_id): ?User
    {
        $statement = $this->connection->prepare("SELECT * FROM users WHERE user_id = ? ");
        $statement->execute([$user_id]);

        try {
            if($row = $statement->fetch()) {
                $user = new User();
                $user->user_id = $row['user_id'];
                $user->username = $row['username'];
                $user->password = $row['password'];
                $user->role = $row['role'];
                $user->created_at = $row['created_at'];
                $user->updated_at = $row['updated_at'];
                $user->deleted_at = $row['deleted_at'];

                return $user;
            }else{
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function deleteById(User $user)
    {
        $statement = $this->connection->prepare("UPDATE users SET deleted_at = ? WHERE user_id = ?");
        $statement->execute([
            $user->deleted_at,
            $user->user_id
        ]);
    }

    public function deleteAll()
    {
        $this->connection->exec("DELETE FROM users");
    }

}
