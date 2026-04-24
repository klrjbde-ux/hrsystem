<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    use HasFactory;
       // Specify the table name
       protected $table = 'deduction_type'; // Adjust to your actual table name

}
