<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnimalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'id'          => $this->id,
            'type_id'     => isset($this->type) ? $this->type_id : null,
            'type_name'   => isset($this->type) ? $this->type->name : null,
            'name'        => $this->name,
            'birthday'    => $this->birthday,
            'age'         => $this->age,
            'area'        => $this->area,
            'fix'         => $this->fix,
            'description' => $this->description,
            'personality' => $this->personality,
            'created_at'  => (string)$this->created_at, //(string)強制轉string
            'updated_at'  => (string)$this->updated_at,
            'user_id'     => $this->user_id,
        ];
    }
}
