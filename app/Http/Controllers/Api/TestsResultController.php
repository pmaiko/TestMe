<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TestsResult;

class TestsResultController extends Controller
{
  function index () {
    $results = TestsResult::query()->where('user_id', auth()->user()->id);
    dd($results);
  }
}
