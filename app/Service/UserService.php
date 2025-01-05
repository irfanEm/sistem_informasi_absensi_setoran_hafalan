<?php

namespace IRFANM\SIASHAF\Service;

use IRFANM\SIASHAF\Domain\User;
use IRFANM\SIASHAF\Repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Mengambil semua data pengguna
     */
    public function getAllUsers(): array
    {
        return $this->userRepository->getAll();
    }

    /**
     * Menyimpan data pengguna baru
     */
    public function createUser(User $user): User
    {
        return $this->userRepository->save($user);
    }

    /**
     * Memperbarui data pengguna
     */
    public function updateUser(User $user): User
    {
        return $this->userRepository->update($user);
    }

    /**
     * Mencari pengguna berdasarkan ID
     */
    public function findUserById(string $user_id): ?User
    {
        return $this->userRepository->findById($user_id);
    }

    /**
     * Menghapus pengguna secara soft delete
     */
    public function deleteUserById(string $user_id): bool
    {
        $user = $this->userRepository->findById($user_id);

        if ($user === null) {
            return false;
        }

        $user->deleted_at = date('Y-m-d H:i:s');
        return $this->userRepository->deleteById($user);
    }

    /**
     * Menghapus pengguna secara permanen
     */
    public function deleteUserPermanently(string $user_id): bool
    {
        return $this->userRepository->deletePermanentlyById($user_id);
    }

    /**
     * Soft delete semua pengguna
     */
    public function deleteAllUsers(): bool
    {
        return $this->userRepository->deleteAll();
    }

    /**
     * Menghapus semua pengguna secara permanen
     */
    public function deleteAllUsersPermanently(): bool
    {
        return $this->userRepository->deleteAllPermanently();
    }
}
