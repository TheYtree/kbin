<?php declare(strict_types=1);

namespace App\Service;

use App\DTO\UserProfileSettingsDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserProfileSettingsManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createDto(User $user): UserProfileSettingsDto
    {
        return new UserProfileSettingsDto($user->isNotifyOnNewEntry(), $user->isNotifyOnNewPost());
    }

    public function update(User $user, UserProfileSettingsDto $dto)
    {
        $user->setNotifyOnNewEntry($dto->isNotifyOnNewEntry());
        $user->setNotifyOnNewPost($dto->isNotifyOnNewPost());

        $this->entityManager->flush();
    }
}