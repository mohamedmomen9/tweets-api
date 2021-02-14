<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FollowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'follower_id' => $this->follower_id,
            'followed_id'=>$this->followed_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
