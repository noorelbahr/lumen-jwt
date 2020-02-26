<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
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
            'id'            => $this->id,
            'username'      => $this->username,
            'fullname'      => $this->fullname,
            'picture_url'   => $this->picture ? url('storage/profile/' . $this->picture) : null,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at
        ];
    }
}
