<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    
    protected $fillable = ['car_template_id', 'placa', 'disponivel', 'km'];

    public function rules() {
        return [
            'car_template_id' => 'exists:car_templates,id',
            'placa' => 'required',
            'disponivel' => 'required',
            'km' => 'required'
        ];
    }

    public function CarTemplate() {
        return $this->belongsTo('App\Models\CarTemplate');
    }
}
