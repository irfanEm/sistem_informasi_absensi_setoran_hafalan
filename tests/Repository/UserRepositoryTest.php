<?php

namespace IRFANM\SIASHAF\Repository;

use IRFANM\SIASHAF\Config\Database;
use IRFANM\SIASHAF\Domain\User;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;

    public function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConn());

        $this->userRepository->deleteAllPermanently();
    }

    public function testSave()
    {
        $user = new User();
        $user->user_id = uniqid();
        $user->username = 'user_save@test.com';
        $user->password = 'password';
        $user->role = 'santri';
        $user->created_at = date("Y-m-d H:i:s");
        $user->updated_at = date("Y-m-d H:i:s");

        $savedUser = $this->userRepository->save($user);

        self::assertEquals($user->user_id, $savedUser->user_id);
        self::assertEquals($user->username, $savedUser->username);
    }

    public function testGetAllDeletedUsers()
    {
        $user = new User();
        $user->user_id = uniqid();
        $user->username = 'user_deleted@test.com';
        $user->password = 'password';
        $user->role = 'santri';
        $user->created_at = date("Y-m-d H:i:s");
        $user->updated_at = date("Y-m-d H:i:s");
        $user->deleted_at = date("Y-m-d H:i:s");

        $this->userRepository->save($user);
        $this->userRepository->deleteById($user);

        $deletedUsers = $this->userRepository->getAllDeletedUser();

        self::assertCount(1, $deletedUsers);
        self::assertEquals($user->user_id, $deletedUsers[0]['user_id']);
    }

    public function testRestoreDeletedUserById()
    {
        $user = new User();
        $user->user_id = uniqid();
        $user->username = 'user_restore@test.com';
        $user->password = 'password';
        $user->role = 'santri';
        $user->created_at = date("Y-m-d H:i:s");
        $user->updated_at = date("Y-m-d H:i:s");
        $user->deleted_at = date("Y-m-d H:i:s");

        $this->userRepository->save($user);
        $this->userRepository->deleteById($user);

        $user->deleted_at = null; // Restore deleted_at ke null
        $restoredUser = $this->userRepository->restoreDeletedUserById($user);

        self::assertNull($restoredUser->deleted_at);

        $result = $this->userRepository->findById($user->user_id);
        self::assertNotNull($result);
    }

    public function testDeleteAll()
    {
        $user1 = new User();
        $user1->user_id = uniqid();
        $user1->username = 'user1@test.com';
        $user1->password = 'password';
        $user1->role = 'santri';

        $user2 = new User();
        $user2->user_id = uniqid();
        $user2->username = 'user2@test.com';
        $user2->password = 'password';
        $user2->role = 'santri';

        $this->userRepository->save($user1);
        $this->userRepository->save($user2);

        $this->userRepository->deleteAll();

        $deletedUsers = $this->userRepository->getAllDeletedUser();
        self::assertCount(2, $deletedUsers);
    }

    public function testDeleteAllPermanently()
    {
        $user1 = new User();
        $user1->user_id = uniqid();
        $user1->username = 'user_perm1@test.com';
        $user1->password = 'password';
        $user1->role = 'santri';

        $this->userRepository->save($user1);
        $this->userRepository->deleteAllPermanently();

        $result = $this->userRepository->getAll();
        self::assertCount(0, $result);
    }

    public function testDeletePermanentlyById()
    {
        $user = new User();
        $user->user_id = uniqid();
        $user->username = 'user_perm@test.com';
        $user->password = 'password';
        $user->role = 'santri';

        $this->userRepository->save($user);
        $this->userRepository->deletePermanentlyById($user->user_id);

        $result = $this->userRepository->findById($user->user_id);
        self::assertNull($result);
    }

    public function testRestoreAll()
    {
        $user1 = new User();
        $user1->user_id = uniqid();
        $user1->username = 'user1@test.com';
        $user1->password = 'password';
        $user1->role = 'santri';
        $user1->deleted_at = date("Y-m-d H:i:s");

        $user2 = new User();
        $user2->user_id = uniqid();
        $user2->username = 'user2@test.com';
        $user2->password = 'password';
        $user2->role = 'santri';
        $user2->deleted_at = date("Y-m-d H:i:s");

        $this->userRepository->save($user1);
        $this->userRepository->save($user2);
        $this->userRepository->restoreAll();

        $result = $this->userRepository->getAll();
        self::assertCount(2, $result);
    }
}
