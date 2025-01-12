<?php

namespace IRFANM\SIASHAF\Controller;

use Exception;
use IRFANM\SIASHAF\App\View;
use IRFANM\SIASHAF\Config\Database;
use IRFANM\SIASHAF\Domain\User;
use IRFANM\SIASHAF\Exception\ValidationException;
use IRFANM\SIASHAF\Model\UserLoginRequest;
use IRFANM\SIASHAF\Model\UserRegistrationRequest;
use IRFANM\SIASHAF\Repository\UserRepository;
use IRFANM\SIASHAF\Service\UserService;

class UserController
{
    private UserService $userService;

    public function __construct()
    {
        $connection = Database::getConn();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);
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
     * Mengakses halaman register
     *
     */
    public function register()
    {
        View::render("User/register", [
            "title" => "Tambah user",
        ]);
    }

    /**
     * Menambahkan pengguna baru
     */
    public function postRegister(): void
    {
        $request = new UserRegistrationRequest();
        $request->username = $_POST['username'];
        $request->password = $_POST['password'];
        $request->password_konfirmation = $_POST['password_konfirmation'];
        $request->role = $_POST['role'];

        try {
            $this->userService->createUser($request);
            View::redirect("/users/index");
        }catch(ValidationException $err){
            View::render("User/register", [
                "title" => "Tambah user baru.",
                "error" => $err->getMessage(),
            ]);
        }
    }

    /**
     * login user
     */
    public function login()
    {
        View::render("User/login", [
            "title" => "Login user",
        ]);
    }

    /**
     * handle login post
     */
    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->username = $_POST['username'];
        $request->password = $_POST['password'];

        // var_dump($request);
        try {
            $this->userService->login($request);
            View::redirect("/admin/beranda");
        }catch(ValidationException $err) {
            View::render("User/login", [
                "title" => "Login user",
                "error" => $err->getMessage(),
            ]);

        }
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
