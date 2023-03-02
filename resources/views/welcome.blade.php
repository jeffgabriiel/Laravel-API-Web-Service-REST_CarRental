<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

        <style>
            /*
            body {
                font-family: 'Nunito', sans-serif;
            }

            .relative{
                width: 100%;
                height: 100%;
            }

            .form-brand{
                display: flex;
                flex-direction: column;
                align-items: left;
                background-color: gray;
                width: 700px;
                height: 700px;
            }

            .nome_marca{
                width: 300px;
                height: 30px;
                text-align: left;
                align-items: center;
                margin: 20px;
            }

            .button_div{
                display: flex;
                align-items: right;
                align-items: left;
                margin-left: 100px;
                margin-top: 100px;
            }

            .button-form-brnd{
                width: 50px;
                background-color: black;
                color: rgb(175, 19, 19);
                align-items: flex-start;
            }

            #div_principal{
                display: flex;
                align-items: center;
            }

            .div_input{
                width: 400px;
                height: 70px;
                display: flex;
                flex-direction: column;
                margin-left: 100px;
                align-items: center;
                background-color: green;
            }

            .nome_marca_section{
                display: flex;
                align-items: left;
                flex-direction: column;
                margin: 100px;
                background-color: black;
            }
            */

            html, body {
                display: flex;
                justify-content: center;
                font-family: Roboto, Arial, sans-serif;
                font-size: 15px;
                width: 100%;
                height: 99%;
            }
            form {
                border: 5px solid #f1f1f1;
            }
            input[type=text], input[type=password] {
                width: 100%;
                padding: 16px 8px;
                margin: 8px 0;
                display: inline-block;
                border: 1px solid #ccc;
                box-sizing: border-box;
            }
            button {
                background: rgb(14,10,87);
                background: linear-gradient(90deg, rgba(14,10,87,1) 0%, rgba(17,104,136,1) 35%, rgba(0,212,255,1) 100%);
                color: white;
                padding: 14px 0;
                margin: 10px 0;
                border: none;
                cursor: grabbing;
                width: 100%;
            }
            h1 {
                text-align:center;
                font-size:18;
            }

            
            button:hover {
                opacity: 0.8;
            }
            .formcontainer {
                
                text-align: left;
                margin: 24px 50px 12px;
            }
            .container {
                padding: 16px 0;
                text-align:left;
            }
            span.psw {
                float: right;
                padding-top: 0;
                padding-right: 15px;
            }
            /* Change styles for span on extra small screens */
            @media screen and (max-width: 300px) {
            span.psw {
                display: block;
                float: none;
            }}

            .container{
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: left;
            }

            .div_form{
                width: 500px;
                height: 400px;
            }

            .form_add{
                width: 100%;
                height: 100%; /*vertical*/
                display: flex;
                flex-direction: column;
                justify-content: left;
            }

            .antialiased{
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                /*margin-left: 2%;*/
            }
        </style>

    </head>
    <body class="antialiased">
        {{--
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0" id="div_principal"> 
            <form name="add-marca-post-form" id="add-marca-post-form" method="post" action="{{ route('brand.store') }}" class="form-brand">

                @csrf

                <section class="nome_marca_section">
                    <input name="nome" value="" type="text" placeholder="Nome da marca" class="nome_marca">
                </section>

                <div class="div_input">
                    <input name="imagem" type="file" value="" src="" class="imagem_marca">
                </div>
                    
                {{-- 
                <div>
                    <select name="motivo_contatos_id" class="">
                        <option value="">Quantos dias de aluguel?</option>

                        <option value="1" >1</option>
                        <option value="2" >2</option>
                        <option value="+" >+</option>
                        
                    </select>
                </div>
                }}
                <div class="button_div">
                    <button type="submit" class="button-form-brnd">Enviar</button>
                </div>
                
            </form>
        </div>
        --}}
        
        <div class="div_form">
            <form method="post" action="{{ route('brand.store') }}" class="form_add">

                @csrf
    
                <h1>Adicionar marcas</h1>
    
                <div class="formcontainer">
                <hr/>
                
                <div class="container">
                  <label for="name"><strong>Nome da marca</strong></label>
                  <input type="text" placeholder="Nome" name="nome" value="">
                  <label for="psw"><strong>Logo</strong></label>
                  <br/>
                  <input type="file" name="imagem" value="">
                </div>
    
                <button type="submit">Adicionar</button>
    
                {{--
                <div class="container" style="background-color: #eee">
                  <label style="padding-left: 15px">
                  <input type="checkbox"  checked="checked" name="remember"> Remember me
                  </label>
                  <span class="psw"><a href="#"> Forgot password?</a></span>
                </div>
                --}}
    
              </form>
        </div>
        
    </body>
</html>
