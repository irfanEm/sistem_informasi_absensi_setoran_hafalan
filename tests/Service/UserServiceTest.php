<?php

namespace IRFANM\SIASHAF\Service;

use IRFANM\SIASHAF\Config\Database;
use IRFANM\SIASHAF\Domain\User;
use IRFANM\SIASHAF\Exception\ValidationException;
use IRFANM\SIASHAF\Model\UserRegistrationRequest;
use IRFANM\SIASHAF\Repository\SessionRepository;
use IRFANM\SIASHAF\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;
    
    public function setUp(): void
    {
        $conn = Database::getConn();
        $this->userRepository = new UserRepository($conn);
        $this->userService = new UserService($this->userRepository);
        $this->sessionRepository = new SessionRepository($conn);


        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAllPermanently();
    }

    public function testCreateUserWithNonRandomUserId()
    {
        $user = new UserRegistrationRequest();
        $user->user_id = "usr0001";
        $user->username = "user_01@test.com";
        $user->password = "user_0001";
        $user->password_konfirmation = "user_0001";
        $user->role = "guru";

        $result = $this->userService->createUser($user);

        self::assertNotNull($result);
        self::assertEquals($result->user->user_id, "usr0001");
        self::assertEquals($result->user->username, "user_01@test.com");
        self::assertEquals($result->user->role, "guru");
        self::assertTrue(password_verify("user_0001", $result->user->password));
    }

    public function testCreateUserWithMethodUniqidForUserId()
    {
        $user = new UserRegistrationRequest();
        $user->username = "user_01@test.com";
        $user->password = "user_0001";
        $user->password_konfirmation = "user_0001";
        $user->role = "guru";

        $result = $this->userService->createUser($user);

        self::assertNotNull($result);
        self::assertNotNull($result->user->user_id);
        self::assertEquals($result->user->username, "user_01@test.com");
        self::assertEquals($result->user->role, "guru");
        self::assertTrue(password_verify("user_0001", $result->user->password));
    }

    public function testCreateUserValidationExceptionFieldCantEmpty()
    {
        $user = new UserRegistrationRequest();

        self::expectException(ValidationException::class);
        self::expectExceptionMessage("Username, password, dan role tidak boleh kosong!");

        $this->userService->createUser($user);
    }

    public function testCreateUserValidationExceptionPasswordNotMatch()
    {
        $user = new UserRegistrationRequest();
        $user->username = "user_01@test.com";
        $user->password = "user_0001";
        $user->password_konfirmation = "user_0002";
        $user->role = "guru";

        self::expectException(ValidationException::class);
        self::expectExceptionMessage("Password dan konfirmasi password tidak cocok!");

        $this->userService->createUser($user);
    }

    public function testCreateUserValidationExceptionUserHasBeenUsed()
    {
        $user = new User();
        $user->user_id = "test_user001";
        $user->username = "user_01@test.com";
        $user->password = password_hash("user_0001", PASSWORD_BCRYPT);
        $user->role = "guru";
        $user->created_at = date("Y-m-d H:i:s");
        $user->updated_at = date("Y-m-d H:i:s");
        $user->deleted_at = null;
        $this->userRepository->save($user);

        $user = new UserRegistrationRequest();
        $user->username = "user_01@test.com";
        $user->password = "user_0001";
        $user->password_konfirmation = "user_0001";
        $user->role = "guru";

        self::expectException(ValidationException::class);
        self::expectExceptionMessage("User dengan username tersebut sudah ada!");

        $this->userService->createUser($user);
    }
}
