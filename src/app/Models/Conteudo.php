<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conteudo extends Model
{
    use HasFactory;

    // ADICIONE ESTA LINHA:
    protected $fillable = ['ticker', 'conteudo', 'status'];
}