<?php
namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;


class ModuleResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'sort_order' => $this->sort_order,
            'lessons' => LessonResource::collection($this->whenLoaded('lessons')),
        ];
    }
}
