<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TweetResource extends JsonResource
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
            'id' => $this->id,
            'text_en' => $this->text_en,
            'text_ar' => $this->text_ar,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
