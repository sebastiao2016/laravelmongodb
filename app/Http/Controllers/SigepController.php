<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Car;

class SigepController extends Controller
{

    public function startConfig () {

        $accessDataParaAmbienteDeHomologacao = new \PhpSigep\Model\AccessDataHomologacao();

        $config = new \PhpSigep\Config();
        $config->setAccessData($accessDataParaAmbienteDeHomologacao);
        $config->setEnv(\PhpSigep\Config::ENV_PRODUCTION);
        $config->setCacheOptions(
            array(
                'storageOptions' => array(
                    // Qualquer valor setado neste atributo será mesclado ao atributos das classes 
                    // "\PhpSigep\Cache\Storage\Adapter\AdapterOptions" e "\PhpSigep\Cache\Storage\Adapter\FileSystemOptions".
                    // Por tanto as chaves devem ser o nome de um dos atributos dessas classes.
                    'enabled' => false,
                    'ttl' => 10,// "time to live" de 10 segundos
                    'cacheDir' => sys_get_temp_dir(), // Opcional. Quando não inforado é usado o valor retornado de "sys_get_temp_dir()"
                ),
            )
        );

        \PhpSigep\Bootstrap::start($config);

    }

    public function buscaCliente (Request $request){

        $this->startConfig();

        $accessData = new \PhpSigep\Model\AccessDataHomologacao();

        $phpSigep = new \PhpSigep\Services\SoapClient\Real();
        $result = $phpSigep->buscaCliente($accessData);
        
        if (!$result->hasError()) {
            /** @var $buscaClienteResult \PhpSigep\Model\BuscaClienteResult */
            $buscaClienteResult = $result->getResult();
            
            // Anula as chancelas antes de imprimir o resultado, porque as chancelas não estão é liguagem humana
            $servicos = $buscaClienteResult->getContratos()->cartoesPostagem->servicos;
            foreach ($servicos as &$servico) {
                    $servico->servicoSigep->chancela->chancela = 'Chancelas anulada via código.';
            }
        }
        
        echo '<pre>';
        var_dump($result);
        echo '</pre>';
        
    }

    public function calcPrecoPrazo (Request $request) {

        $this->startConfig();

        $dimensao = new \PhpSigep\Model\Dimensao();
        $dimensao->setTipo(\PhpSigep\Model\Dimensao::TIPO_PACOTE_CAIXA);
        $dimensao->setAltura(15); // em centímetros
        $dimensao->setComprimento(17); // em centímetros
        $dimensao->setLargura(12); // em centímetros
        
        $params = new \PhpSigep\Model\CalcPrecoPrazo();
        $params->setAccessData(new \PhpSigep\Model\AccessDataHomologacao());
        $params->setCepOrigem('30170-010');
        $params->setCepDestino('04538-132');
        $params->setServicosPostagem(\PhpSigep\Model\ServicoDePostagem::getAll());
        $params->setAjustarDimensaoMinima(true);
        $params->setDimensao($dimensao);
        $params->setPeso(0.150);// 150 gramas
        
        
        $phpSigep = new \PhpSigep\Services\SoapClient\Real();
        $result = $phpSigep->calcPrecoPrazo($params);
        
        var_dump((array)$result);
        
    }

    public function consultaCep (Request $request){

        $this->startConfig();

        $cep = isset($request->cep) ? $request->cep : '30170-010';

        $phpSigep = new \PhpSigep\Services\SoapClient\Real();
        $result = $phpSigep->consultaCep($cep);
        
        var_dump((array)$result);

    }

    public function clique_retire (Request $request) {

        $this->startConfig();

        $accessData = new \PhpSigep\Model\AccessDataHomologacao();

        $config = new \PhpSigep\Config();
        $config->setAccessData($accessData);

        \PhpSigep\Bootstrap::start($config);

        $preListaDePostagem = new \PhpSigep\Model\PreListaDePostagem();
        $preListaDePostagem->setAccessData($accessData);

        $dimensao_por_cubagem = 1000 ** (1/3);
        $dimensao = new \PhpSigep\Model\Dimensao();
        $dimensao->setAltura($dimensao_por_cubagem);
        $dimensao->setLargura($dimensao_por_cubagem);
        $dimensao->setComprimento($dimensao_por_cubagem);
        $dimensao->setDiametro(0);
        $dimensao->setTipo(\PhpSigep\Model\Dimensao::TIPO_PACOTE_CAIXA);

        // *** DADOS DO REMETENTE *** //
        $remetente = new \PhpSigep\Model\Remetente();
        $remetente->setNome('Google São Paulo');
        $remetente->setLogradouro('Av. Brigadeiro Faria Lima');
        $remetente->setNumero('3900');
        $remetente->setComplemento('5º andar');
        $remetente->setBairro('Itaim');
        $remetente->setCep('04538-132');
        $remetente->setUf('SP');
        $remetente->setCidade('São Paulo');
        // *** FIM DOS DADOS DO REMETENTE *** //

        $destinatario = new \PhpSigep\Model\Destinatario();
        $destinatario->setNome('Google Belo Horizonte');
        $destinatario->setLogradouro('Av. Bias Fortes');
        $destinatario->setNumero('382');
        $destinatario->setComplemento('6º andar');
        $destinatario->setIsCliqueRetire(true);

        $destino = new \PhpSigep\Model\DestinoNacional();
        $destino->setAgencia('Agencia BH');
        $destino->setBairro('Lourdes');
        $destino->setCep('30170-010');
        $destino->setCidade('Belo Horizonte');  $this->startConfig();
        $destino->setUf('MG');

        $servicoAdicional = new \PhpSigep\Model\ServicoAdicional();
        $servicoAdicional->setCodigoServicoAdicional(\PhpSigep\Model\ServicoAdicional::SERVICE_REGISTRO);
        // Se não tiver valor declarado informar 0 (zero)
        $servicoAdicional->setValorDeclarado(250);

        $etiqueta = new \PhpSigep\Model\Etiqueta();
        $etiqueta->setEtiquetaComDv('EC373812299BR');

        $encomenda = new \PhpSigep\Model\ObjetoPostal();
        $encomenda->setServicosAdicionais(array($servicoAdicional));
        $encomenda->setDestinatario($destinatario);
        $encomenda->setDestino($destino);
        $encomenda->setDimensao($dimensao);
        $encomenda->setEtiqueta($etiqueta);
        $encomenda->setPeso(1.2);
        $encomenda->setServicoDePostagem(new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA));

        $preListaDePostagem->setEncomendas(array($encomenda));
        $preListaDePostagem->setRemetente($remetente);

        $phpSigep = new \PhpSigep\Services\SoapClient\Real();

        $idPlp = 0;
        try {
            $result = $phpSigep->fechaPlpVariosServicos($preListaDePostagem);
            if (!$result->hasError()) {
                $idPlp = $result->getResult()->getIdPlp();
            } else {
                var_dump($result->getErrorMsg());
            }
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
        }
    
        if ($idPlp) {
            $pdf = new \PhpSigep\Pdf\CartaoDePostagem2016($preListaDePostagem, $idPlp, false);
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="doc.pdf"');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');
            echo $pdf->render();
        }

    }

    public function imprimirEtiquetas2016 (Request $request) {

        $this->startConfig();

        $params = include '/home/sebastiao/laravelmongodbv3/vendor/stavarengo/php-sigep/exemplos/helper-criar-pre-lista-a4.php';

        // Logo da empresa remetente
        $logoFile = '/home/sebastiao/laravelmongodbv3/vendor/stavarengo/php-sigep/exemplos/logo-etiqueta-2016.png';

        //Parametro opcional indica qual layout utilizar para a chancela. Ex.: CartaoDePostagem::TYPE_CHANCELA_CARTA, CartaoDePostagem::TYPE_CHANCELA_CARTA_2016
        $layoutChancela = array(); //array(\PhpSigep\Pdf\CartaoDePostagem2016::TYPE_CHANCELA_SEDEX_2016);

        $pdf = new \PhpSigep\Pdf\CartaoDePostagem2016($params, time(), $logoFile, $layoutChancela);
        $pdf->render();

    }

    public function imprimirPlp (Request $request) {

        $this->startConfig();

        $params = include '/home/sebastiao/laravelmongodbv3/vendor/stavarengo/php-sigep/exemplos/helper-criar-pre-lista.php';

        $pdf  = new \PhpSigep\Pdf\ListaDePostagem($params, time());
        $pdf->render('I');
    }

}


