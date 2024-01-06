<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
      return [
        'id' => $this->id,
        'answer' => $this->answer,
        'description' => $this->description,
        'correct' => $this->correct
//        'questionId' => $this->question_id,
//        'testId' => $this->test_id,
//        'userId' => $this->user_id,
//        'createdAt' => $this->created_at,
//        'updatedAt' => $this->updated_at,
      ];
    }
}
