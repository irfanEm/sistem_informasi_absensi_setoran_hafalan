<?php

namespace IRFANM\SIASHAF\Repository;

use IRFANM\SIASHAF\Config\Database;
use IRFANM\SIASHAF\Domain\Guru;
use IRFANM\SIASHAF\Repository\GuruRepository;
use PHPUnit\Framework\TestCase;

class GuruRepositoryTest extends TestCase
{
    private GuruRepository $guruRepository;

    public function setUp(): void
    {
        $conn = Database::getConn();
        $this->guruRepository = new GuruRepository($conn);

        $this->guruRepository->deleteAllPermanently();
    }

    public function testGetAllGuru()
    {
        $guru0 = new Guru();
        $guru0->user_id = uniqid();
        $guru0->nama = "Nama Test-0";
        $guru0->nik = "0102030405";
        $guru0->email = "email_test0@guru.com";
        $guru0->kontak = "088229918978";
        $guru0->created_at = date("Y-m-d H:i:s");
        $guru0->updated_at = date("Y-m-d H:i:s");
        $this->guruRepository->save($guru0);

        $guru1 = new Guru();
        $guru1->user_id = uniqid();
        $guru1->nama = "Nama Test-0";
        $guru1->nik = "0102030405";
        $guru1->email = "email_test0@guru.com";
        $guru1->kontak = "088229918978";
        $guru1->created_at = date("Y-m-d H:i:s");
        $guru1->updated_at = date("Y-m-d H:i:s");
        $this->guruRepository->save($guru1);

        $guru2 = new Guru();
        $guru2->user_id = uniqid();
        $guru2->nama = "Nama Test-0";
        $guru2->nik = "0102030405";
        $guru2->email = "email_test0@guru.com";
        $guru2->kontak = "088229918978";
        $guru2->created_at = date("Y-m-d H:i:s");
        $guru2->updated_at = date("Y-m-d H:i:s");
        $this->guruRepository->save($guru2);

        $results = $this->guruRepository->getAll();

        self::assertNotNull($results);
        self::assertCount(3, $results);
    }

    public function testFindById()
    {
        $guru2 = new Guru();
        $guru2->user_id = "guru00";
        $guru2->nama = "Nama Test-0";
        $guru2->nik = "0102030405";
        $guru2->email = "email_test0@guru.com";
        $guru2->kontak = "088229918978";
        $guru2->created_at = date("Y-m-d H:i:s");
        $guru2->updated_at = date("Y-m-d H:i:s");
        $this->guruRepository->save($guru2);

        $result = $this->guruRepository->findByUserId("guru00");

        self::assertNotNull($result);
        self::assertEquals("guru00", $result->user_id);
        self::assertEquals("Nama Test-0", $result->nama);
        self::assertEquals("0102030405", $result->nik);
        self::assertEquals("email_test0@guru.com", $result->email);
        self::assertEquals("088229918978", $result->kontak);

        $resultByNik = $this->guruRepository->findByNik('0102030405');
        self::assertNotNull($resultByNik);
        self::assertEquals("guru00", $resultByNik->user_id);
        self::assertEquals("Nama Test-0", $resultByNik->nama);
        self::assertEquals("0102030405", $resultByNik->nik);
        self::assertEquals("email_test0@guru.com", $resultByNik->email);
        self::assertEquals("088229918978", $resultByNik->kontak);
    }

    public function testUpdate()
    {
        $guru2 = new Guru();
        $guru2->user_id = "guru00";
        $guru2->nama = "Nama Test-0";
        $guru2->nik = "0102030405";
        $guru2->email = "email_test0@guru.com";
        $guru2->kontak = "088229918978";
        $guru2->created_at = date("Y-m-d H:i:s");
        $guru2->updated_at = date("Y-m-d H:i:s");
        $this->guruRepository->save($guru2);

        $guru2->nama = "Nama Test-1";
        $guru2->nik = "0305080204";
        $guru2->email = "testupdate@mail.com";
        $guru2->kontak = "088934456775";
        $guru2->updated_at = date("2025-01-27 23:59:59");
        $this->guruRepository->update($guru2);

        $result = $this->guruRepository->findByUserId("guru00");

        self::assertNotNull($result);
        self::assertEquals("Nama Test-1", $result->nama);
        self::assertEquals("0305080204", $result->nik);
        self::assertEquals("testupdate@mail.com", $result->email);
        self::assertEquals("088934456775", $result->kontak);
        self::assertEquals("2025-01-27 23:59:59", $result->updated_at);
    }

    public function testDeleteByUserIdSoftly()
    {
        $guru2 = new Guru();
        $guru2->user_id = "guru00";
        $guru2->nama = "Nama Test-0";
        $guru2->nik = "0102030405";
        $guru2->email = "email_test0@guru.com";
        $guru2->kontak = "088229918978";
        $guru2->created_at = date("Y-m-d H:i:s");
        $guru2->updated_at = date("Y-m-d H:i:s");
        $this->guruRepository->save($guru2);

        $this->guruRepository->deleteByUserIdSoftly("guru00");

        $result = $this->guruRepository->findByUserId("guru00");
        self::assertNull($result);

        $deletedGuru = $this->guruRepository->findDeletedGuruByUserId("guru00");
        self::assertNotNull($deletedGuru);
        self::assertEquals("guru00", $deletedGuru->user_id);
    }

    public function testRestoreDeletedGuruSoftly()
    {
        $guru0 = new Guru();
        $guru0->user_id = uniqid();
        $guru0->nama = "Nama Test-0";
        $guru0->nik = "0102030405";
        $guru0->email = "email_test0@guru.com";
        $guru0->kontak = "088229918978";
        $guru0->created_at = date("Y-m-d H:i:s");
        $guru0->updated_at = date("Y-m-d H:i:s");
        $this->guruRepository->save($guru0);

        $this->guruRepository->deleteByUserIdSoftly($guru0->user_id);

        $result = $this->guruRepository->findDeletedGuruByUserId($guru0->user_id);

        self::assertNotNull($result);
        self::assertNotNull($result->deleted_at);

        $notFoundData = $this->guruRepository->findByUserId($guru0->user_id);
        self::assertNull($notFoundData);
    }

    public function testDeleteAllSoftly()
    {
        $guru0 = new Guru();
        $guru0->user_id = uniqid();
        $guru0->nama = "Nama Test-0";
        $guru0->nik = "0102030405";
        $guru0->email = "email_test0@guru.com";
        $guru0->kontak = "088229918978";
        $guru0->created_at = date("Y-m-d H:i:s");
        $guru0->updated_at = date("Y-m-d H:i:s");
        $this->guruRepository->save($guru0);

        $guru1 = new Guru();
        $guru1->user_id = uniqid();
        $guru1->nama = "Nama Test-0";
        $guru1->nik = "0102030405";
        $guru1->email = "email_test0@guru.com";
        $guru1->kontak = "088229918978";
        $guru1->created_at = date("Y-m-d H:i:s");
        $guru1->updated_at = date("Y-m-d H:i:s");
        $this->guruRepository->save($guru1);

        $guru2 = new Guru();
        $guru2->user_id = uniqid();
        $guru2->nama = "Nama Test-0";
        $guru2->nik = "0102030405";
        $guru2->email = "email_test0@guru.com";
        $guru2->kontak = "088229918978";
        $guru2->created_at = date("Y-m-d H:i:s");
        $guru2->updated_at = date("Y-m-d H:i:s");
        $this->guruRepository->save($guru2);

        $result = $this->guruRepository->deleteAllSoftly();
        self::assertTrue($result);

        $teachersDeleted = $this->guruRepository->getAllDeletedSoftly();
        self::assertNotNull($teachersDeleted);
        self::assertCount(3, $teachersDeleted);

        $restoreDeletedTeachers = $this->guruRepository->restoreAllDeletedSoftly();
        self::assertTrue($restoreDeletedTeachers);
    }
}
