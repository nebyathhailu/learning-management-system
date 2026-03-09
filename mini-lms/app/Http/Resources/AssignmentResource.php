<?php
namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;


class AssignmentResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'max_score' => $this->max_score,
            'lesson_id' => $this->lesson_id,
            'my_submission' => new SubmissionResource($this->whenLoaded('submissions')),
        ];
    }
}
