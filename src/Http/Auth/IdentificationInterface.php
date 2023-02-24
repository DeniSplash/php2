<?php
namespace GeekBrains\LevelTwo\Blog\Http\Auth;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Http\Request;

interface IdentificationInterface
{
    public function user(Request $request): User;
}