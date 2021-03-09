<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Car;

class FreteController extends Controller
{

    public function index()
    {

        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, 'https://robotapitest.clickentregas.com/api/business/1.1'); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['X-DV-Auth-Token: C2155421895080225DF5D82001C83D3636356B35']); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        
        $result = curl_exec($curl); 
        if ($result === false) { 
            throw new Exception(curl_error($curl), curl_errno($curl)); 
        } 
        
        echo $result; 

    }

    public function calcular()
    {

        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, 'https://robotapitest.clickentregas.com/api/business/1.1/calculate-order'); 
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST'); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['X-DV-Auth-Token: C2155421895080225DF5D82001C83D3636356B35']); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
         
        $data = [ 
            'matter' => 'Documents', 
            'points' => [ 
                [ 
                    'address' => 'R. Guamiranga, 1140 - Vila Independencia, São Paulo - SP, 04220-020', 
                ], 
                [ 
                    'address' => 'Av. Paulista, 1439 - 12 - Bela Vista, São Paulo - SP, 01310-100', 
                ], 
            ], 
        ]; 
         
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); 
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json); 
         
        $result = curl_exec($curl); 
        if ($result === false) { 
            throw new Exception(curl_error($curl), curl_errno($curl)); 
        } 
         

        echo '<pre>';
        print_r(json_decode($result));
        echo  '</pre>';


    }

    public function criar()
    {

        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, 'https://robotapitest.clickentregas.com/api/business/1.1/create-order'); 
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST'); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['X-DV-Auth-Token: C2155421895080225DF5D82001C83D3636356B35']); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
         
        $data = [ 
            'matter' => 'Documents', 
            'points' => [ 
                [ 
                    'address' => 'R. Guamiranga, 1140 - Vila Independencia, São Paulo - SP, 04220-020', 
                    'contact_person' => [ 
                        'phone' => '5511900000001', 
                    ], 
                ], 
                [ 
                    'address' => 'Av. Paulista, 1439 - 12 - Bela Vista, São Paulo - SP, 01310-100', 
                    'contact_person' => [ 
                        'phone' => '5511900000001', 
                    ], 
                ], 
            ], 
        ]; 
         
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); 
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json); 
         
        $result = curl_exec($curl); 
        if ($result === false) { 
            throw new Exception(curl_error($curl), curl_errno($curl)); 
        } 
         
        echo '<pre> $result';
        print_r(json_decode($result, JSON_PRETTY_PRINT));
        echo  '</pre>';

    }

    public function listar()
    {

        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, 'https://robotapitest.clickentregas.com/api/business/1.1/orders?status=available'); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['X-DV-Auth-Token: C2155421895080225DF5D82001C83D3636356B35']); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
         
        $result = curl_exec($curl); 
        if ($result === false) { 
            throw new Exception(curl_error($curl), curl_errno($curl)); 
        } 
         
        echo '<pre>';
        print_r(json_decode($result));
        echo  '</pre>';

    }
  
    public function calcular_z()
    {


        $order_teste = 
        '{
        "_id": "6031718e696cf67df1623834",
        "shipping_method": "pac",
        "storekeeper_id": "60317186696cf663a73b86a3",
        "status": "AWAITING_PAYMENT",
        "shipping_destination_address": {
            "number": "665",
            "zipcode": "05634020",
            "address": "Rua Osiris Magalhães de Almeida",
            "neighborhood": "Jardim Monte Kemel",
            "city": "São Paulo",
            "state": "SP",
            "complement": "141",
            "id": null,
            "_id": null
        },
        "items": [{
            "product": {
                "_id": "5f60120d696cf6538e1bb873",
                "external_id": "MA069",
                "name": "Sandália Rasteira Cobra  - Beá",
                "price": 16900,
                "stocked_quantity": 2,
                "id": "5f60120d696cf6538e1bb873",
                "category_ids": [
                    "5f53816a696cf61015665082",
                    "5f538208696cf61015665083",
                    "5fac0a68696cf66be82192a3",
                    "60084af1696cf61bde69fb82"
                ],
                "published": true,
                "box": {
                    "weight": "1000",
                    "height": "17",
                    "width": "20",
                    "length": "30"
                }
            }
        }],
        "supplier_entries": [{
            "shipping_source_address": {
                "zipcode": "02403011",
                "number": "468",
                "address": "R. Francisca Júlia",
                "city": "São Paulo",
                "state": "SP",
                "neighborhood": "Santana",
                "complement": ""
            },
            "shipping_cost": "2397",
            "delivery_date": "2021-02-25T20:31:10.000+0000",
            "delivery_days": "5"
    
        }]
      
        }'
        ;

        $array = json_decode($order_teste, true);

        // dd($array['items'][0]['product']['box']['width']);

        $largura = $array['items'][0]['product']['box']['width'] / 100;
        $altura = $array['items'][0]['product']['box']['height'] / 100;
        $comprimento = $array['items'][0]['product']['box']['length'] / 100;
        $fator = 300;

        $cubagem = $largura * $altura * $comprimento * $fator;

        // echo ($cubagem);

        $cep1 = substr($array['supplier_entries'][0]['shipping_source_address']['zipcode'],0,5);
        $cep2 = substr($array['supplier_entries'][0]['shipping_source_address']['zipcode'],5,3);
        $cep = $cep1.'-'.$cep2;

        $origem = $array['supplier_entries'][0]['shipping_source_address']['address'];
        $origem = $origem.', ';
        $origem = $origem.$array['supplier_entries'][0]['shipping_source_address']['number'];
        $origem = $origem.', ';
        $origem = $origem.$array['supplier_entries'][0]['shipping_source_address']['neighborhood'];
        $origem = $origem.', ';
        $origem = $origem.$array['supplier_entries'][0]['shipping_source_address']['city'];
        $origem = $origem.' - ';
        $origem = $origem.$array['supplier_entries'][0]['shipping_source_address']['state'];
        $origem = $origem.' , ';
        $origem = $origem.$cep;

        // dd($origem);

        $destino = $array['shipping_destination_address']['address'];
        $destino = $destino.', ';
        $destino = $destino.$array['shipping_destination_address']['number'];
        $destino = $destino.', ';
        $destino = $destino.$array['shipping_destination_address']['neighborhood'];
        $destino = $destino.', ';
        $destino = $destino.$array['shipping_destination_address']['city'];
        $destino = $destino.' - ';
        $destino = $destino.$array['shipping_destination_address']['state'];
        $destino = $destino.' , ';
        $destino = $destino.$cep;

        // dd($destino);

        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, "https://robotapitest.clickentregas.com/api/business/1.1/calculate-order"); 
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST'); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['X-DV-Auth-Token: C2155421895080225DF5D82001C83D3636356B35']); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
         
        $data = [ 
            'matter' => 'Documents', 
            'total_weight_kg' => $cubagem, 
            'points' => [ 
                [ 
                    'address' => $origem, 
                ], 
                [ 
                    'address' => $destino,  
                ], 
            ], 
        ]; 
         
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); 
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json); 
         
        $result = curl_exec($curl); 
        if ($result === false) { 
            throw new Exception(curl_error($curl), curl_errno($curl)); 
        } 
         
        echo '<pre>';
        print_r(json_decode($result));
        echo  '</pre>';

    }


}


