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
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'fullname' => $this->fullname,
            'email' => $this->email,
        ];
    }



    //   داتا اضافية ثابتة برسلها مع الطلب   with=static
    // public function with($request)
    // {
    //     return [
    //         'meta' => [

    //             'status' => 1,
    //             'token' => $request->user()->createToken("API TOKEN")->plainTextToken,
    //             'message' => 'User logged in successfully'

    //         ],
    //     ];
    // }
}
