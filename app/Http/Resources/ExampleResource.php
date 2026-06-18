<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Example
 */
class ExampleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'ulid' => $this->ulid,
            'string_column' => $this->string_column,
            'string_with_length' => $this->string_with_length,
            'char_column' => $this->char_column,
            'text_column' => $this->text_column,
            'medium_text_column' => $this->medium_text_column,
            'long_text_column' => $this->long_text_column,
            'integer_column' => $this->integer_column,
            'big_integer_column' => $this->big_integer_column,
            'medium_integer_column' => $this->medium_integer_column,
            'small_integer_column' => $this->small_integer_column,
            'tiny_integer_column' => $this->tiny_integer_column,
            'unsigned_integer_column' => $this->unsigned_integer_column,
            'decimal_column' => (float) $this->decimal_column,
            'double_column' => (float) $this->double_column,
            'float_column' => (float) $this->float_column,
            'boolean_column' => (bool) $this->boolean_column,
            'date_column' => $this->date_column,
            'datetime_column' => $this->datetime_column,
            'datetime_tz_column' => $this->datetime_tz_column,
            'time_column' => $this->time_column,
            'time_tz_column' => $this->time_tz_column,
            'timestamp_column' => $this->timestamp_column,
            'timestamp_tz_column' => $this->timestamp_tz_column,
            'year_column' => $this->year_column,
            'json_column' => $this->json_column,
            'jsonb_column' => $this->jsonb_column,
            'binary_column' => $this->binary_column,
            'enum_column' => $this->enum_column,
            'set_column' => $this->set_column,
            'ip_address_column' => $this->ip_address_column,
            'mac_address_column' => $this->mac_address_column,
            'geometry_column' => $this->geometry_column,
            'point_column' => $this->point_column,
            'linestring_column' => $this->linestring_column,
            'polygon_column' => $this->polygon_column,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->whenNotNull($this->deleted_at),
        ];
    }
}
