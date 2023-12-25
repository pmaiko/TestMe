<?php

namespace App\Helpers;
use Illuminate\Pagination\LengthAwarePaginator;

class Pagination {
  function __construct (LengthAwarePaginator $paginated) {
    $this->meta = [
      'total' => $paginated->total(),
      'perPage' => $paginated->perPage(),
      'currentPage' => $paginated->currentPage(),
      'lastPage' => $paginated->lastPage()
    ];
  }
}