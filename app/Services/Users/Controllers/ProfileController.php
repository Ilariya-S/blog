<?php
namespace App\Services\Users\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Users\Requests\UpdateAvatarRequest;
use App\Services\Users\Managers\UserManager;
use App\Services\Users\Resources\UserProfileResource;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function __construct(
        private UserManager $userManager,
    ) {
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->userManager->getProfile($id);
        return response()->json([
            'data' => new UserProfileResource($user)
        ]);
    }

    public function updateAvatar(UpdateAvatarRequest $request): JsonResponse
    {
        $user = auth()->user();
        $payload = $request->validated();
        $path = $this->userManager->uploadAvatar($user, $payload['avatar']);

        return response()->json([
            'message' => 'Avatar updated successfully',
            'avatar_url' => \Storage::url($path)
        ]);
    }
}