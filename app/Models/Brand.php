<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'imagem'];

    //Mensagens de feedback
    public function rules(){
        return [
            'nome' => 'required|unique:brands,nome,'.$this->id.'', //o nome é único na tabela DB brands(marcas)
            'imagem' => 'required|file|mimes:png',
        ];
    }
    public function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'O nome já existe!',
        ];
    }

    public function CarTemplates(){ 
        //UMA marca tem MUITOS modelos
        return $this->hasMany('App\Models\CarTemplate');
    }
    
}
