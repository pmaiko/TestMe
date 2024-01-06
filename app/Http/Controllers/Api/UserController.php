<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    function index () {
      $users = User::all();
      return response()->json($users);
    }
}
