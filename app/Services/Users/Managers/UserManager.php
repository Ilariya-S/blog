<?php
namespace App\Services\Users\Managers;

use App\Services\Users\Repositories\UserRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Services\Users\Models\User;

class UserManager
{

    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function getProfile(int $id)
    {
        return $this->userRepository->getUserWithPosts($id);
    }

    public function uploadAvatar(User $user, UploadedFile $file): string
    {
        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }
        $path = $file->store('avatars', 'public');
        $this->userRepository->updateAvatar($user->id, $path);

        return $path;
    }
}