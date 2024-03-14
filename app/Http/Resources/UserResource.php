<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request, $token = ''): array
    {
        $response =  [
            'name'  => $this->name,
            'email' => $this->email,
        ];

        $this->token ? $response['accessToken'] = $this->token :null;

        return $response;
    }
}
