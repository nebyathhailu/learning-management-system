<?php
namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;


class LessonResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->when(!$request->routeIs('courses.*'), $this->content),
            'video_url' => $this->video_url,
            'duration_minutes' => $this->duration_minutes,
            'sort_order' => $this->sort_order,
            'is_free_preview' => $this->is_free_preview,
            'is_completed' => $this->when($request->user(), fn() => 
                $this->completedByUsers->contains($request->user()->id)),
        ];
    }
}
