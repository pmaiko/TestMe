<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseResourceCollection extends ResourceCollection
{
  /**
   * Customize pagination information.
   *
   * @param \Illuminate\Http\Request $request
   * @return array
   */

  public $with = [
    'success' => true
  ];

  public function paginationInformation($request, $paginated, $default)
  {
//    return [
//      'pagination' => [
//        'currentPage' => $paginated['current_page'],
//        'from' => $paginated['from'],
//        'lastPage' => $paginated['last_page'],
//        'perPage' => $paginated['per_page'],
//        'to' => $paginated['to'],
//        'total' => $paginated['total'],
//      ]
//    ];

    dd(12);
    return $default;
  }
}