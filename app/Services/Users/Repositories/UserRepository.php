<?php

namespace App\Services\Users\Repositories;

use App\Services\Users\Contacts\UserRepositoryInterface;
use App\Services\Users\Models\User;
use Prettus\Repository\Eloquent\BaseRepository;
//подивтися чи є ще якісь способи щоб зберегти пароль і захешувтаи його
use Illuminate\Support\Facades\Hash;
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function model()
    {
        return User::class;
    }
    public function create(array $data)
    {
        // 1. Хешуємо пароль перед збереженням
        $data['password'] = Hash::make($data['password']);

        // 2. Видаляємо 'password_confirmation', щоб не передавати його в модель
        unset($data['password_confirmation']);

        // 3. Викликаємо батьківський метод для збереження
        // Завдяки тому, що 'password' було ВИДАЛЕНО з $fillable в моделі,
        // це буде безпечно, оскільки Eloquent не дозволить mass assignment
        // для password, навіть якщо ви його передали тут (якщо ви використовуєте $fillable).
        // Але оскільки Prettus використовує $forceFill (або ви маєте правильно налаштувати)
        // або використовує model()->create() з fillable, краще уникнути передачі незахешованого
        // пароля, видаливши його з $fillable в моделі, і додати хешований тут.

        return parent::create($data); // Або $this->model->create($data);
    }

}