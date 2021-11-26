<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Bot;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Enums\UserType;

class BotPolicy
{
    use HandlesAuthorization;

    /**
     * Determine  if the given bot can be deleted by user
     *
     * @param App\Models\User
     * @param App\Models\Bot
     * @return bool
     */
    public function delete(User $user, Bot $bot)
    {
        return $user->id === $bot->user_id;
    }

    /**
     * Determine  if the given bot can be updated by user
     *
     * @param App\Models\User
     * @param App\Models\Bot
     * @return bool
     */
    public function update(User $user, Bot $bot)
    {
        return $user->id === $bot->user_id;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can get room of bot.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function getRoom(User $user, $bot)
    {
        return $user->id === $bot->user_id;
    }

    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user, $bot)
    {
        if ($user->role == UserType::ADMIN) {
            return true;
        }
    }
}
