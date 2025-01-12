<?php

namespace IRFANM\SIASHAF\Service;

use Exception;
use IRFANM\SIASHAF\Config\Database;
use IRFANM\SIASHAF\Domain\User;
use IRFANM\SIASHAF\Exception\ValidationException;
use IRFANM\SIASHAF\Model\UserLoginRequest;
use IRFANM\SIASHAF\Model\UserLoginResponse;
use IRFANM\SIASHAF\Model\UserRegistrationRequest;
use IRFANM\SIASHAF\Model\UserRegistrationResponse;
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
    public function createUser(UserRegistrationRequest $request): UserRegistrationResponse  
    {
        $this->validateUserRegistrationRequest($request);

        try {
            Database::beginTransaction();
            $user = new User();
            $user->user_id = uniqid();
            $user->username = $request->username;
            $user->password = $request->password;
            $user->role = $request->role;
            $user->created_at = date("Y-m-d H:i:s");
            $user->updated_at = date("Y-m-d H:i:s");
            $user->deleted_at = null;

            $this->userRepository->save($user);

            $response = new UserRegistrationResponse();
            $response->user = $user;

            Database::commitTransaction();
            return $response;
        }catch(Exception $err) {
            Database::rollbackTransaction();
            throw $err;
        }
    }

    private function validateUserRegistrationRequest(UserRegistrationRequest $request)
    {
        // Pengecekan apakah ada field yang kosong
        if (empty(trim($request->username)) || 
            empty(trim($request->password)) || 
            empty(trim($request->password_konfirmation)) || 
            empty(trim($request->role))) {
            throw new ValidationException("Username, password, dan role tidak boleh kosong!");
        }
    
        // Validasi kecocokan password dan konfirmasinya
        if ($request->password !== $request->password_konfirmation) {
            throw new ValidationException("Password dan konfirmasi password tidak cocok!");
        }
    
        // Validasi apakah username sudah digunakan
        if ($this->userRepository->findByUsername($request->username) !== null) {
            throw new ValidationException("User dengan username tersebut sudah ada!");
        }
    }

    /**
     * function login
     */
    public function login(UserLoginRequest $request): UserLoginResponse
    {
        // Validasi permintaan login
        $this->validateLoginRequest($request);
    
        // Ambil data user (dari validasi, user sudah dipastikan ada)
        $user = $this->userRepository->findByUsername($request->username);
    
        // Buat respons login
        $response = new UserLoginResponse();
        $response->user = $user;
    
        return $response;
    }

    /**
     * function validate login request
     */
    private function validateLoginRequest(UserLoginRequest $request)
    {
        // Trim dan validasi input tidak kosong
        $username = trim($request->username);
        $password = trim($request->password);

        if (empty($username) || empty($password)) {
            throw new ValidationException("Username dan password tidak boleh kosong!");
        }

        // Validasi apakah user sudah terdaftar
        $user = $this->userRepository->findByUsername($username);
        if ($user === null) {
            throw new ValidationException("User dengan username '$username' belum terdaftar, silakan registrasi.");
        }

        // Validasi password
        if (!password_verify($password, $user->password)) {
            throw new ValidationException("Username / password yang dimasukkan salah!");
        }
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
