<?php

namespace App\Http\Resources;

class QuestionResource extends BaseJsonResource
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
        'userId' => $this->user_id,
        'testId' => $this->test_id,
        'question' => $this->question,
        'description' => $this->description,
        'createdAt' => $this->created_at,
        'updatedAt' => $this->updated_at,
//        'answers' => new AnswerCollection($this->answers()->select(['id', 'answer', 'description', 'correct'])->get())
        'answers' => new AnswerCollection($this->whenLoaded('answers'))
      ];
    }
}
