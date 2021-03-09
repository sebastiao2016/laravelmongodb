<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Car;

class BlingController extends Controller
{

    public function index()
    {

        $apikey = "7d77f94a8631680f619247cf4584f68ed53552d7d319e5ed72ba65411265a1488d677478";
        $outputType = "json";
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

        $url = 'https://bling.com.br/Api/v2/produto/json/';
        $xml = 
        '
        <?xml version="1.0" encoding="UTF-8"?>
            <produto>
            <codigo>223435780</codigo>
            <descricao>Caneta 001</descricao>
            <situacao>Ativo</situacao>
            <descricaoCurta>Descrição curta da caneta</descricaoCurta>
            <descricaoComplementar>Descrição complementar da caneta</descricaoComplementar>
            <un>Pc</un>
            <vlr_unit>1.68</vlr_unit>
            <preco_custo>1.23</preco_custo>
            <peso_bruto>0.2</peso_bruto>
            <peso_liq>0.18</peso_liq>
            <class_fiscal>1000.01.01</class_fiscal>
            <marca>Marca da Caneta</marca>
            <origem>0</origem>
            <estoque>10</estoque>
            <deposito>
                <id>123456</id>
                <estoque>200</estoque>
            </deposito>
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
            <camposCustomizados>
                <memoriaRam>16</memoriaRam>
                <eDualSim>false</eDualSim>
            </camposCustomizados>
            <idCategoria>1234</idCategoria>
            </produto>
        '
        ;
        $posts = array (
            "apikey" => "7d77f94a8631680f619247cf4584f68ed53552d7d319e5ed72ba65411265a1488d677478",
            "xml" => rawurlencode($xml)
        );
        
        $retorno = $this->executeInsertProduct($url, $posts);

        echo $retorno;

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

}


