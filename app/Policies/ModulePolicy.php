<?php

namespace App\Policies;

use App\Models\User;

abstract class ModulePolicy
{
    abstract protected function module(): string;

    public function viewAny(User $user): bool
    {
        return $user->canModule($this->module(), 'view');
    }

    public function view(User $user, mixed $model): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->canModule($this->module(), 'manage');
    }

    public function update(User $user, mixed $model): bool
    {
        return $user->canModule($this->module(), 'manage');
    }

    public function delete(User $user, mixed $model): bool
    {
        return $user->canModule($this->module(), 'delete');
    }
}
