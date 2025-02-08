<?php

namespace IRFANM\SIASHAF\Repository;

use IRFANM\SIASHAF\Config\Database;
use IRFANM\SIASHAF\Domain\Guru;
use IRFANM\SIASHAF\Domain\Teacher;
use IRFANM\SIASHAF\Domain\User;
use PHPUnit\Framework\TestCase;

class TeacherRepositoryTest extends TestCase
{
    private UserRepository $userRepository;
    private TeacherRepository $teacherRepository;

    public function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConn());
        $this->teacherRepository = new TeacherRepository(Database::getConn());

        $this->teacherRepository->deleteAllPermanently();
        $this->userRepository->deleteAllPermanently();
    }

    public function testGetAllExistTeachers()
    {
        $user1 = new User();
        $user1->user_id = "ustadz001";
        $user1->name = "Zaidun";
        $user1->username = "zaidun_97@ustadz.com";
        $user1->password = password_hash("zaidun_97", PASSWORD_BCRYPT);
        $user1->role = "Ustadz";
        $user1->created_at = date("Y-m-d H:i:s");        
        $user1->updated_at = date("Y-m-d H:i:s");
        $this->userRepository->save($user1);

        $user2 = new User();
        $user2->user_id = "ustadz002";
        $user2->name = "Umarun";
        $user2->username = "umarun_96@ustadz.com";
        $user2->password = password_hash("umarun_96", PASSWORD_BCRYPT);
        $user2->role = "Ustadz";
        $user2->created_at = date("Y-m-d H:i:s");        
        $user2->updated_at = date("Y-m-d H:i:s");
        $this->userRepository->save($user2);

        $user3 = new User();
        $user3->user_id = "ustadz003";
        $user3->name = "Bakrun";
        $user3->username = "bakrun_99@ustadz.com";
        $user3->password = password_hash("bakrun_99", PASSWORD_BCRYPT);
        $user3->role = "Ustadz";
        $user3->created_at = date("Y-m-d H:i:s");        
        $user3->updated_at = date("Y-m-d H:i:s");
        $this->userRepository->save($user3);

        $users = $this->userRepository->getAllActive();
        self::assertNotNull($users);

        $teacher1 = new Teacher();
        $teacher1->user_id = $user1->user_id;
        $teacher1->teacher_code = "ustadz001";
        $teacher1->first_name = "Zaidun";
        $teacher1->last_name = "bin Ahmad";
        $teacher1->email = $user1->username;
        $teacher1->phone = "088221915000";
        $teacher1->address = "Desa Damai, Kec. Tentrem, Kab. Bima";
        $teacher1->date_of_birth = date("1997-11-27");
        $teacher1->hire_date = date("2023-01-01");
        $teacher1->department = "Ustadz Hufadz";
        $teacher1->status = "active";
        $teacher1->created_at = date("Y-m-d H:i:s");
        $teacher1->updated_at = date("Y-m-d H:i:s");
        $this->teacherRepository->save($teacher1);

        $teacher2 = new Teacher();
        $teacher2->user_id = $user2->user_id;
        $teacher2->teacher_code = "ustadz002";
        $teacher2->first_name = "Umarun";
        $teacher2->last_name = "bin Hamdan";
        $teacher2->email = $user2->username;
        $teacher2->phone = "085726091996";
        $teacher2->address = "Desa Konoha, Kec. Kirigakure, Kab. Anbu";
        $teacher2->date_of_birth = date("1997-11-27");
        $teacher2->hire_date = date("2023-06-01");
        $teacher2->department = "Ustadz Hufadz";
        $teacher2->status = "active";
        $teacher2->created_at = date("Y-m-d H:i:s");
        $teacher2->updated_at = date("Y-m-d H:i:s");
        $this->teacherRepository->save($teacher2);

        $teacher3 = new Teacher();
        $teacher3->user_id = $user3->user_id;
        $teacher3->teacher_code = "ustadz003";
        $teacher3->first_name = "Bakrun";
        $teacher3->last_name = "bin Ubaid";
        $teacher3->email = $user3->username;
        $teacher3->phone = "085720072000";
        $teacher3->address = "Desa Northblue, Kec. Grandline, Kab. New World";
        $teacher3->date_of_birth = date("1997-11-27");
        $teacher3->hire_date = date("2023-06-01");
        $teacher3->department = "Ustadz Hufadz";
        $teacher3->status = "active";
        $teacher3->created_at = date("Y-m-d H:i:s");
        $teacher3->updated_at = date("Y-m-d H:i:s");
        $this->teacherRepository->save($teacher3);

        $teacherExists = $this->teacherRepository->getAllActive();
        self::assertNotNull($teacherExists);
        self::assertCount(3, $teacherExists);
        var_dump($teacherExists);
    }
}
