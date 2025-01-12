<?php

namespace IRFANM\SIASHAF\Service;

use IRFANM\SIASHAF\Domain\Session;
use IRFANM\SIASHAF\Domain\User;
use IRFANM\SIASHAF\Repository\SessionRepository;
use IRFANM\SIASHAF\Repository\UserRepository;

class SessionService
{
    public static string $COOKIE_NAME = "X-SIASHAF-IEM";
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    public function __construct(SessionRepository $sessionRepository, UserRepository $userRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->userRepository = $userRepository;
    }

    public function create(User $user)
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = $user->user_id;
        $session->user_id = $user->username;

        $this->sessionRepository->save($session);

        setcookie(self::$COOKIE_NAME, $session->id, time() + (3600 * 24), "/users/login");

        return $session;
    }

    public function destroy()
    {
        $session_id = $_COOKIE[self::$COOKIE_NAME] ?? '';
        $this->sessionRepository->deleteById($session_id);
        setcookie(self::$COOKIE_NAME, '', 1, "/users/login");
    }

    public function current(): ?User
    {
        $session_id = $_COOKIE[self::$COOKIE_NAME] ?? '';
        $session = $this->sessionRepository->findById($session_id);
        if($session == NULL) {
            return null;
        }

        return $this->userRepository->findById($session->user_id);
    }
}
