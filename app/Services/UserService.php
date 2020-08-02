<?php


namespace App\Services;


use App\User;

class UserService
{
    public $vkUsersData;

    public function __construct(VkUsersData $vkUsersData)
    {
        $this->vkUsersData = $vkUsersData;
    }

    public function updateUser(User $user): User
    {
        $user->update(
            $this->vkUsersData->getUserData($user->{$user->getVkIdFieldName()})
        );
        return $user;
    }

    public function createNewUser(string $vkId): User
    {
        $user = new User(
            $this->vkUsersData->getUserData($vkId)
        );
        $user->save();
        return $user;
    }
}
