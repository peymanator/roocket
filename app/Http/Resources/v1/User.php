<?php

namespace App\Http\Resources\v1;

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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'user_type' => $this->type,
            'api_token' => $this->api_token,
            'roles' => new RoleCollection($this->roles),
            'permissions' => new PermissionCollection($this->roles)
        ];
    }
}
