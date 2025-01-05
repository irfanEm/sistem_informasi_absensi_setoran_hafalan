<?php

namespace IRFANM\SIASHAF\Controller;

use IRFANM\SIASHAF\Domain\User;
use IRFANM\SIASHAF\Service\UserService;

class UserController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Menampilkan semua pengguna
     */
    public function index(): void
    {
        $users = $this->userService->getAllUsers();
        echo json_encode($users);
    }

    /**
     * Menambahkan pengguna baru
     */
    public function store(array $request): void
    {
        $user = new User();
        $user->user_id = uniqid();
        $user->username = $request['username'] ?? '';
        $user->password = password_hash($request['password'] ?? '', PASSWORD_BCRYPT);
        $user->role = $request['role'] ?? 'santri';
        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');

        $this->userService->createUser($user);
        echo json_encode(["message" => "User berhasil ditambahkan."]);
    }

    /**
     * Memperbarui data pengguna
     */
    public function update(string $user_id, array $request): void
    {
        $user = $this->userService->findUserById($user_id);

        if ($user === null) {
            echo json_encode(["error" => "User tidak ditemukan."]);
            return;
        }

        $user->username = $request['username'] ?? $user->username;
        $user->password = isset($request['password']) ? password_hash($request['password'], PASSWORD_BCRYPT) : $user->password;
        $user->role = $request['role'] ?? $user->role;
        $user->updated_at = date('Y-m-d H:i:s');

        $this->userService->updateUser($user);
        echo json_encode(["message" => "User berhasil diperbarui."]);
    }

    /**
     * Menampilkan detail pengguna tertentu
     */
    public function show(string $user_id): void
    {
        $user = $this->userService->findUserById($user_id);

        if ($user === null) {
            echo json_encode(["error" => "User tidak ditemukan."]);
            return;
        }

        echo json_encode($user);
    }

    /**
     * Soft delete pengguna tertentu
     */
    public function destroy(string $user_id): void
    {
        if ($this->userService->deleteUserById($user_id)) {
            echo json_encode(["message" => "User berhasil dihapus (soft delete)."]);
        } else {
            echo json_encode(["error" => "User tidak ditemukan."]);
        }
    }

    /**
     * Hapus pengguna tertentu secara permanen
     */
    public function destroyPermanently(string $user_id): void
    {
        if ($this->userService->deleteUserPermanently($user_id)) {
            echo json_encode(["message" => "User berhasil dihapus secara permanen."]);
        } else {
            echo json_encode(["error" => "User tidak ditemukan."]);
        }
    }

    /**
     * Soft delete semua pengguna
     */
    public function destroyAll(): void
    {
        $this->userService->deleteAllUsers();
        echo json_encode(["message" => "Semua pengguna berhasil dihapus (soft delete)."]);
    }

    /**
     * Hapus semua pengguna secara permanen
     */
    public function destroyAllPermanently(): void
    {
        $this->userService->deleteAllUsersPermanently();
        echo json_encode(["message" => "Semua pengguna berhasil dihapus secara permanen."]);
    }
}
