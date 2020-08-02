<?php


namespace App\Services;

use VK\Client\Enums\VKLanguage;
use VK\Client\VKApiClient;

class VkUsersData
{
    private $vkClient;
    private $token;

    public function __construct(VKApiClient $vkClient, string $token)
    {
        $this->vkClient = $vkClient;
        $this->token = $token;
    }

    public function getUserData(string $vkId): array
    {
        $data = $this->getUserDataFromApi($vkId);
        return [
            'vkid' => $vkId,
            'img' => $data['photo_50'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ];
    }

    private function getUserDataFromApi(string $vkId): array
    {
        $data = $this->vkClient->users()->get($this->token, [
            'user_ids' => $vkId,
            'fields' => 'first_name,last_name,photo_50'
        ]);
        return $data[0];
    }
}
