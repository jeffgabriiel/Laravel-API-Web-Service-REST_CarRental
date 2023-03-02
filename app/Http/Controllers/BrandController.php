<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\BrandRepository;

class BrandController extends Controller
{
    
    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $brandRepository = new BrandRepository($this->brand);

        //return Brand::all(); //antes do __contruct
        //return $this->brand->all();
        //return response()->json($this->brand->with('brands')->get(), 200);

        //$brand = array();

        if($request->has('atributos_modelos')){
            $atributos_modelos = 'CarTemplates:id,'.$request->atributos_modelos;
            $brandRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        }else{
            $brandRepository->selectAtributosRegistrosRelacionados('CarTemplates');
        }

        if($request->has('filtro')){
            $brandRepository->filtro($request->filtro);
        }

        if($request->has('atributos')){
            $brandRepository->selectAtributos($request->atributos);
        }

        return response()->json($brandRepository->getResultados(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$brand = Brand::create($request->all());

        $request->validate($this->brand->rules(), $this->brand->feedback()); 
        //stateless //Em Headers: Key => Accept ; Value => application/json

        /*
        dd($request->nome);
        dd($request->get('nome'));
        dd($request->imagem);
        dd($request->file('imagem'));
        */
        $image = $request->file('imagem');
        $image_urn = $image->store('imagens', 'public');

        $brand = $this->brand->create([
            'nome' => $request->nome,
            'imagem' => $image_urn
        ]);

        return response()->json($brand, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return $brand;
        $brand = $this->brand->with('CarTemplates')->find($id);

        if($brand === null){ //mensagem de erro caso não exista o id pesquisado
            return response()->json(['msg' => 'Marca pesquisada não existe!'], 404); //Status Code HTTP
        }
        
        return response()->json($brand, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /*
        $brand->update($request->all());
        return $brand;
        */
        
        $brand = $this->brand->find($id);

        if($brand === null){ //mensagem de erro caso não exista o id pesquisado
            //return ['msg' => 'O recurso da pesquisada não existe!'];
            return response()->json(['msg' => 'O recurso da pesquisada não existe!'], 404); //Status Code HTTP
        }

        if($request->method() === 'PATCH'){

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($brand->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas, $brand->feedback());

        }else{
            $request->validate($this->brand->rules(), $this->brand->feedback()); 
        }

        //remove o arquivo antigo caso um novo tenha sido enviado no request
        if($request->file('imagem')){
            Storage::disk('public')->delete($brand->imagem);
        }

        $image = $request->file('imagem');
        $image_urn = $image->store('imagens', 'public');

        /*
        $brand->update([
            'nome' => $request->nome,
            'imagem' => $image_urn
        ]);
        */

        //preencher os dados de marca com o request
        $brand->fill($request->all());
        $brand->imagem = $image_urn;
        $brand->save();

        return response()->json($brand, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /*
        $brand->delete();
        return ['msg' => 'Marca deletada com sucesso!'];
        */

        $brand = $this->brand->find($id);

        if($brand === null){ //mensagem de erro caso não exista o id pesquisado
            //return ['msg' => 'O recurso da pesquisada não existe!'];
            return response()->json(['msg' => 'O recurso da pesquisada não existe!'], 404); //Status Code HTTP
        }

        //remove o arquivo antigo caso um novo tenha sido enviado no request
            Storage::disk('public')->delete($brand->imagem);
        
        $brand->delete();
        return response()->json(['msg' => 'Marca deletada com sucesso!'], 200);
    }
}
