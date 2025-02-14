<?php

namespace IRFANM\SIASHAF\Repository;

use IRFANM\SIASHAF\Config\Database;
use IRFANM\SIASHAF\Factory\AttendanceFactory;
use IRFANM\SIASHAF\Factory\ClassFactory;
use IRFANM\SIASHAF\Factory\UserFactory;
use IRFANM\SIASHAF\Repository\AttendanceRepository;
use IRFANM\SIASHAF\Repository\ClassRepository;
use IRFANM\SIASHAF\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class AttendanceRepositoryTest extends TestCase
{
    private UserRepository $userRepository;
    private ClassRepository $classRepository;
    private AttendanceRepository $attendanceRepository;

    public function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConn());
        $this->classRepository = new ClassRepository(Database::getConn());
        $this->attendanceRepository = new AttendanceRepository(Database::getConn());

        $this->attendanceRepository->deleteAllPermanently();
        $this->classRepository->deleteAllPermanently();
        $this->userRepository->deleteAllPermanently();
    }

    public function testSaveAttendances()
    {
        $user1 = UserFactory::createUser("user_001", "Zaidun Qoimun", "zaidun_98@ustadz.com", "zaidun_98", "Ustadz");
        $this->userRepository->save($user1);

        $class1 = ClassFactory::createClass("class_7a", "Class 7A", "Class 7A");
        $this->classRepository->save($class1);

        $attendance1 = AttendanceFactory::createAttendance("attn01", $user1->user_id, $class1->class_id, "2025-02-14", "present", 'Hadir Tepat Waktu');
        $this->attendanceRepository->save($attendance1);

        $attendance2 = AttendanceFactory::createAttendance("attn02", $user1->user_id, $class1->class_id, "2025-02-13", "present", 'Hadir Tepat Waktu');
        $this->attendanceRepository->save($attendance2);

        $attendance3 = AttendanceFactory::createAttendance("attn03", $user1->user_id, $class1->class_id, "2025-02-12", "present", 'Hadir Tepat Waktu');
        $this->attendanceRepository->save($attendance3);

        $attendances = $this->attendanceRepository->getAll();
        self::assertNotNull($attendances);
        self::assertCount(3, $attendances);
    }
}
