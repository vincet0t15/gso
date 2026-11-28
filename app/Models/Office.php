<?php

namespace App\Models;

use Database\Factories\OfficeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['office_name', 'office_code'])]
class Office extends Model
{
    /** @use HasFactory<OfficeFactory> */
    use HasFactory;
}
