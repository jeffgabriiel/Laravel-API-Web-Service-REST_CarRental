<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Exists;

class CarTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['brand_id' ,'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function rules(){
        return [
            'brand_id' => 'exists:brands,id',
            'nome' => 'required|unique:car_templates,nome,'.$this->id.'', //o nome é único na tabela DB car_templates(modelos)
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,7',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean',
        ];
    }   

    public function brand(){
        //UM modelo pertence a UMA marca
        return $this->belongsTo('App\Models\Brand');
    }
}
