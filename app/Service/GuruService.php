<?php

namespace IRFANM\SIASHAF\Service;

use Exception;
use IRFANM\SIASHAF\Config\Database;
use IRFANM\SIASHAF\Domain\Guru;
use IRFANM\SIASHAF\Domain\User;
use IRFANM\SIASHAF\Exception\ValidationException;
use IRFANM\SIASHAF\Model\GuruRegisterRequest;
use IRFANM\SIASHAF\Model\GuruRegisterResponse;
use IRFANM\SIASHAF\Model\GuruUpdateRequest;
use IRFANM\SIASHAF\Model\GuruUpdateResponse;
use IRFANM\SIASHAF\Repository\GuruRepository;
use IRFANM\SIASHAF\Repository\UserRepository;

class GuruService
{
    private GuruRepository $guruRepository;
    private UserRepository $userRepository;

    public function __construct(GuruRepository $guruRepository, UserRepository $userRepository)
    {
        $this->guruRepository = $guruRepository;
        $this->userRepository = $userRepository;
    }

    public function getAllGuru(): array
    {
        return $this->guruRepository->getAll();
    }

    public function addGuru(GuruRegisterRequest $request): GuruRegisterResponse
    {
        $this->validateGuruRequest($request);

        try {
            Database::beginTransaction();
            $guru = new Guru();
            $guru->user_id = uniqid();
            $guru->nama = $request->nama;
            $guru->nik = $request->nik;
            $guru->email = $request->email;
            $guru->kontak = $request->kontak;
            $guru->created_at = date("Y-m-d H:i:s");
            $guru->updated_at = date("Y-m-d H:i:s");
            $guru->deleted_at = null;
            $this->guruRepository->save($guru);
            
            $user = new User();
            $user->user_id = uniqid();
            $user->username = $request->email;
            $user->password = $request->nik;
            $user->role = "guru";
            $user->created_at = date("Y-m-d H:i:s");
            $user->updated_at = date("Y-m-d H:i:s");
            $user->deleted_at = null;
            $this->userRepository->save($user);

            $response = new GuruRegisterResponse();
            $response->guru = $guru;
            
            Database::commitTransaction();
            return $response;
        }catch(Exception $err){
            Database::rollbackTransaction();
            throw $err;
        }
    }

    private function validateGuruRequest(GuruRegisterRequest $request)
    {
        $nama = trim($request->nama);
        $nik = trim($request->nik);
        $email = trim ($request->email);
        $kontak = trim($request->kontak);

        if(empty($nama) || empty($nik) || empty($email) || empty($kontak)){
            throw new ValidationException("Semua bidang wajib diisi!.");
        }

        $guru = $this->guruRepository->findByUserId($request->nik);
        if($guru !== null) {
            throw new ValidationException("Guru dengan NIK $nik sudah terdaftar!");
        }
    }

    public function updateGuru(GuruUpdateRequest $request): GuruUpdateResponse
    {
        $this->validateGuruUpdateRequest($request);

        try {
            Database::beginTransaction();

            $guru = new Guru();
            $guru->user_id = $request->user_id;
            $guru->nama = $request->nama;
            $guru->nik = $request->nik;
            $guru->email = $request->email;
            $guru->kontak = $request->kontak;
            $guru->updated_at = date("Y-m-d H:i:s");
            $this->guruRepository->update($guru);
            
            Database::commitTransaction();
            
            $response = new GuruUpdateResponse();
            $response->guru = $guru;
            return $response;
        }catch(\Exception $err){
            Database::rollbackTransaction();
            throw $err;
        }
    }

    private function validateGuruUpdateRequest(GuruUpdateRequest $request)
    {
        $nama = trim($request->nama);
        $nik = trim($request->nik);
        $email = trim ($request->email);
        $kontak = trim($request->kontak);

        if(empty($nama) || empty($nik) || empty($email) || empty($kontak)){
            throw new ValidationException("Semua bidang wajib diisi!.");
        }

        $guru = $this->guruRepository->findByUserId($request->nik);
        if($guru === null) {
            throw new ValidationException("Guru dengan NIK $nik tidak terdaftar!");
        }
    }
}
