<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class File
 * @package App\Http\Resources
 * @OA\Schema(
 *     description="File resource",
 *     title="File",
 *     required={"id", "youtube_id", "uploaded"}
 * )
 * @mixin \App\Models\File
 */
class File extends JsonResource
{
    /**
     * @OA\Property( property="id", title="ID", description="ID asd asd", format="int64"),
     * @OA\Property( property="title", title="Title", description="Title", format="string"),
     * @OA\Property( property="description", title="Description", description="Description", format="string"),
     * @OA\Property( property="uploaded", title="Uploaded", description="Uploaded status", format="boolean"),
     * @OA\Property( property="youtube_id", title="YouTube ID", description="Unique YouTube ID of Video", format="string"),
     */

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
            'title' => $this->title,
            'description' => $this->description,
            'uploaded' => $this->uploaded,
            'left_days' => $this->left_days,
            'left_minutes' => $this->left_minutes,
            'video_url' => $this->left_minutes,
            'audio_url' => $this->left_minutes,
            'callback_url' => $this->callback_url,
            'youtube_id' => $this->youtube_id
        ];
    }
}
