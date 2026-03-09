<?php
namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;


class CommentResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'user' => ['id' => $this->user->id, 'name' => $this->user->name],
            'replies' => CommentResource::collection($this->whenLoaded('replies')),
            'created_at' => $this->created_at,
        ];
    }
}
