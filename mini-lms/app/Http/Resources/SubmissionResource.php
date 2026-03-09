<?php
namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;


class SubmissionResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'file_name' => $this->file_name,
            'score' => $this->score,
            'feedback' => $this->feedback,
            'status' => $this->status,
            'submitted_at' => $this->submitted_at,
            'graded_at' => $this->graded_at,
            'student' => $this->whenLoaded('student', fn() => ['id'=>$this->student->id,'name'=>$this->student->name]),
        ];
    }
}
