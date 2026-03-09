<?php
namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;


class CourseResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'thumbnail_url' => $this->thumbnail ? asset('storage/'.$this->thumbnail) : null,
            'status' => $this->status,
            'price' => $this->price,
            'category' => $this->category,
            'level' => $this->level,
            'instructor' => ['id' => $this->instructor->id, 'name' => $this->instructor->name],
            'modules_count' => $this->whenCounted('modules'),
            'modules' => ModuleResource::collection($this->whenLoaded('modules')),
            'created_at' => $this->created_at,
        ];
    }
}