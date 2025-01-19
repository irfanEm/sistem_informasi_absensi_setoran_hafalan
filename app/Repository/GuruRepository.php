<?php

namespace IRFANM\SIASHAF\Repository;

use IRFANM\SIASHAF\Domain\Guru;
use PDO;
use PDOException;

class GuruRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    private function mapRowToGuru(array $row): Guru
    {
        $guru = new Guru();
        $guru->user_id = $row['user_id'];
        $guru->nama = $row['nama'];
        $guru->nik = $row['nik'];
        $guru->email = $row['email'];
        $guru->kontak = $row['kontak'];
        $guru->created_at = $row['created_at'];
        $guru->updated_at = $row['updated_at'];
        $guru->deleted_at = $row['deleted_at'];

        return $guru;
    }

    public function getAll(): array
    {
        try {
            $statement = $this->connection->query("SELECT * FROM teachers WHERE deleted_at IS NULL");
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            error_log("Error saat mengambil data users: " . $err->getMessage());
            return [];
        }
    }

    public function save(Guru $guru): Guru
    {
        try{
            $statement = $this->connection->prepare("INSERT INTO teachers (user_id, nama, nik, email, kontak, created_at, updated_at, deleted_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            $statement->execute([
                $guru->user_id,
                $guru->nama,
                $guru->nik,
                $guru->email,
                $guru->kontak,
                $guru->created_at,
                $guru->updated_at,
                $guru->deleted_at,
            ]);

            return $guru;
        }catch(PDOException $err) {
            error_log("Error saat menyimpan data guru: " . $err->getMessage());
            return $guru;
        }
    }

    public function findByUserId(string $user_id): ?Guru
    {
        try {
            $statement = $this->connection->prepare("SELECT * FROM teachers WHERE user_id = ? AND deleted_at IS NULL");
            $statement->execute([$user_id]);

            if($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                return $this->mapRowToGuru($row);
            }

            return null;

        }catch(PDOException $err){
            error_log("Error saat mencari data guru : " . $err->getMessage());
            return null;
        }
    }

    public function findByNik(string $nik): ?Guru
    {
        try {
            $statement = $this->connection->prepare("SELECT * FROM teachers WHERE nik = ? AND deleted_at IS NULL");
            $statement->execute([$nik]);

            if($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                return $this->mapRowToGuru($row);
            }

            return null;

        }catch(PDOException $err){
            error_log("Error saat mencari data guru : " . $err->getMessage());
            return null;
        }
    }

    public function update(Guru $guru): Guru
    {
        try{
            $statement = $this->connection->prepare("UPDATE teachers set nama = ?, nik = ?, email = ?, kontak = ?, updated_at = ? WHERE user_id = ? AND deleted_at IS NULL");
            $statement->execute([
                $guru->nama,
                $guru->nik,
                $guru->email,
                $guru->kontak,
                $guru->updated_at,
                $guru->user_id
            ]);

            return $guru;

        }catch(PDOException $err){
            error_log("Gagal memperbarui data guru : " . $err->getMessage());
            return $guru;
        }
    }

    public function deleteByUserIdSoftly(string $user_id): bool
    {
        try {
            $statement = $this->connection->prepare("
                UPDATE teachers 
                SET deleted_at = ? 
                WHERE user_id = ? AND deleted_at IS NULL
            ");

            return $statement->execute([
                date('Y-m-d H:i:s'),
                $user_id,
            ]);
        } catch (PDOException $err) {
            error_log("Error saat menghapus guru: " . $err->getMessage());
            return false;
        }
    }

    public function findDeletedGuruByUserId(string $user_id): ?Guru
    {
        try {
            $statement = $this->connection->prepare("SELECT * FROM teachers WHERE user_id = ? AND deleted_at IS NOT NULL");
            $statement->execute([$user_id]);

            if ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                return $this->mapRowToGuru($row);
            }

            return null;
        } catch (PDOException $err) {
            error_log("Error saat mencari guru: " . $err->getMessage());
            return null;
        }
    }

    public function restoreDeletedGuruByUserId(string $user_id): ?Guru
    {
        try{
            $statement = $this->connection->prepare("UPDATE teachers set deleted_at = ? WHERE user_id = ? AND deleted_at IS NOT NULL");
            $statement->execute([
                null,
                $user_id
            ]);

            return $this->findByUserId($user_id);
        }catch(PDOException $err) {
            error_log("Gagal mengembalikan guru yang telah dihapus: " . $err->getMessage());
            return null;
        }
    }

    public function deleteAllSoftly(): bool
    {
        try{
            $statement = $this->connection->prepare("UPDATE teachers set deleted_at = ? WHERE deleted_at IS NULL");
            $statement->execute([date("Y-m-d H:i:s")]);
            return true;
        }catch(PDOException $err){
            error_log("Gagal menghapus semua data guru : " . $err->getMessage());
            return false;
        }
    }

    public function getAllDeletedSoftly(): array
    {
        try {
            $statement = $this->connection->query("SELECT * FROM teachers WHERE deleted_at IS NOT NULL");
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            error_log("Error saat mengambil data users: " . $err->getMessage());
            return [];
        }
    }

    public function restoreAllDeletedSoftly(): true
    {
        try{
            $statement = $this->connection->prepare("UPDATE teachers set deleted_at = ? WHERE deleted_at IS NOT NULL");
            $statement->execute([null]);
            return true;
        }catch(PDOException $err){
            error_log("Gagal mengembalikan semua data guru yang telah dihapus: " . $err->getMessage());
            return false;
        }
    }

    public function deleteAllPermanently()
    {
        try {
            $statement = $this->connection->prepare("DELETE FROM teachers WHERE 1");
            return $statement->execute();
        } catch (PDOException $err) {
            error_log("Error saat menghapus semua data secara permanen: " . $err->getMessage());
            return false;
        }
    }
}