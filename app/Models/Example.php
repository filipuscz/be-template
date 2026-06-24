<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Example extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tb_examples';

    protected $fillable = [
        'uuid',
        'ulid',
        'string_column',
        'string_with_length',
        'char_column',
        'text_column',
        'medium_text_column',
        'long_text_column',
        'integer_column',
        'big_integer_column',
        'medium_integer_column',
        'small_integer_column',
        'tiny_integer_column',
        'unsigned_integer_column',
        'decimal_column',
        'double_column',
        'float_column',
        'boolean_column',
        'date_column',
        'datetime_column',
        'datetime_tz_column',
        'time_column',
        'time_tz_column',
        'timestamp_column',
        'timestamp_tz_column',
        'year_column',
        'json_column',
        'jsonb_column',
        'binary_column',
        'enum_column',
        'set_column',
        'ip_address_column',
        'mac_address_column',
        'geometry_column',
        'point_column',
        'linestring_column',
        'polygon_column',
        'user_id',
    ];
}
