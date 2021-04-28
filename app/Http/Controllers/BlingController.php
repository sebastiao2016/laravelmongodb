<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Car;

class BlingController extends Controller
{

    public function index()
    {

        $apikey = "577b54d765ed7fa210140e319623fd1ab3db4efbca7971d32ec439e432b4a0842852036f";
        $outputType = "xml";
        $url = 'https://bling.com.br/Api/v2/produtos/' . $outputType;

        $retorno = $this->executeGetProducts($url, $apikey);

        echo $retorno;

        function executeGetProducts($url, $apikey){
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, $url . '&apikey=' . $apikey);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
            $response = curl_exec($curl_handle);
            curl_close($curl_handle);
            return $response;
        }

    }

    function executeGetProducts($url, $apikey){
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url . '&apikey=' . $apikey);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($curl_handle);
        curl_close($curl_handle);
        return $response;
    }

    public function store()
    {

        $resultado = $this->capturar_loja();

        $produtos = json_decode($resultado, true);

        $url = 'https://bling.com.br/Api/v2/produto/xml/';

        $contador = 0;

        for ($i=0; $i < count($produtos); $i++) { 

            $codigo = $produtos[$i]['id'];
            $descricao = $produtos[$i]['name'];
            $valor_unitario = $produtos[$i]['price'] / 100;
            $estoque = $produtos[$i]['stocked_quantity'];

            $categorias = '***';

            if (isset($produtos[$i]['categories'][0])) {
                $categorias = $produtos[$i]['categories'][0]['name'].' > '.$produtos[$i]['categories'][0]['description'];
            }

            $xml = 
            '
            <?xml version="1.0" encoding="UTF-8"?>
                <produto>
                <codigo>'.$codigo.'</codigo>
                <descricao>'.$descricao.'</descricao>
                <situacao>Ativo</situacao>
                <descricaoCurta>'.$descricao.'</descricaoCurta>
                <descricaoComplementar>'.$categorias.'</descricaoComplementar>
                <un>Pc</un>
                <vlr_unit>'.$valor_unitario.'</vlr_unit>
                <preco_custo>1.23</preco_custo>
                <peso_bruto>0.2</peso_bruto>
                <peso_liq>0.18</peso_liq>
                <class_fiscal>1000.01.01</class_fiscal>
                <marca>Marca da Caneta</marca>
                <origem>0</origem>
                <estoque>10</estoque>
                <gtin>223435780</gtin>
                <gtinEmbalagem>54546</gtinEmbalagem>
                <largura>11</largura>
                <altura>21</altura>
                <profundidade>31</profundidade>
                <estoqueMinimo>1.00</estoqueMinimo>
                <estoqueMaximo>100.00</estoqueMaximo>
                <cest>28.040.00</cest>
                <idGrupoProduto>12345</idGrupoProduto>
                <condicao>Novo</condicao>
                <freteGratis>N</freteGratis>
                <linkExterno>https://minhaloja.com.br/meu-produto</linkExterno>
                <observacoes>Observações do meu produtos</observacoes>
                <producao>P</producao>
                <dataValidade>20/11/2019</dataValidade>
                <descricaoFornecedor>Descrição do fornecedor</descricaoFornecedor>
                <idFabricante>0</idFabricante>
                <codigoFabricante>123</codigoFabricante>
                <unidadeMedida>Centímetros</unidadeMedida>
                <garantia>4</garantia>
                <itensPorCaixa>2</itensPorCaixa>
                <volumes>2</volumes>
                <urlVideo>https://www.youtube.com/watch?v=zKKL-SgC5lY</urlVideo>
                <imagens>
                    <url>https://bling.com.br/bling.jpg</url>
                </imagens>
                <idCategoria>2790707</idCategoria>
                </produto>
            '
            ;
            $posts = array (
                "apikey" => "577b54d765ed7fa210140e319623fd1ab3db4efbca7971d32ec439e432b4a0842852036f",
                "xml" => rawurlencode($xml)
            );
            
            $retorno = $this->executeInsertProduct($url, $posts);

            $parsed_xml = simplexml_load_string($retorno);
            $json = json_encode($parsed_xml);
            $array = json_decode($json,TRUE);

            if (isset($array['produtos']['produto']['id'])) {
                $contador++;
            }

            // dd($array['produtos']['produto']['id']);

        }

        echo ("Foram cadastrados $contador produtos.");

    }

    function executeInsertProduct($url, $data){
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_POST, count($data));
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($curl_handle);
        curl_close($curl_handle);
        return $response;
    }

    public function capturar_loja()
    {

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.zankh.com.br/api/product?page=0&perPage=100&price_le=0&price_ge=0&ob_price=false&convert_prices=true&full_text=&peek_others=1&stock_ge=1',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Accept: application/json, text/plain, */*',
            'apartment: monteverde',
            'sec-ch-ua-mobile: ?0'
          ),
        ));
        
        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
        
    }

}


