<?php

namespace App\Http\Controllers;

use App\Models\Concurso;
use App\Models\DetalleConcurso;
use DateTime;
use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class ConcursoController extends Controller
{
    public $url = "http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml";

    public function index()
    {
        // Obtener la lista de concursos ordenados por fecha de creación descendente
        $concursos = Concurso::latest()->get();

        // Pasar la lista de concursos a la vista "index"
        return view('concursos.index', compact('concursos'));
    }

    public function data(Concurso $model)
    {
        // Obtener las cookies y otros datos necesarios para realizar scrapping
        $data = $this->getCookies();
        $cookie = $data['cookies'];
        $view_state = $data['view_state'];
        $id_captcha = $data['id_captcha'];

        // Generar una imagen de captcha utilizando los datos de la cookie y el ID del captcha
        $image_captcha = $this->getCaptchaCode($cookie, $id_captcha);

        // Pasar la imagen de captcha, la cookie y el view_state a la vista "scrapping"
        return view('concursos.scrapping', compact('image_captcha', 'cookie', 'view_state'));
    }

    public function getCookies()
    {
        // Realizar una solicitud HTTP GET a la URL especificada
        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        $response = curl_exec($curl);

        // Obtener el ID del captcha a partir de la respuesta HTTP
        $find = 'pfdrid=';
        $id_captcha = substr(strstr($response, $find), strlen($find), 43);

        // Obtener las cookies a partir de la respuesta HTTP
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
        $cookies = implode("; ", $matches[1]);

        // Obtener el viewstate a partir de la respuesta HTTP utilizando el cliente Symfony
        static $client = null;
        if (!$client) {
            $client = new Client(HttpClient::create(array(
                'headers' => array(
                    'Host' => 'procesos.seace.gob.pe',
                    'Referer' => $this->url,
                ),
            )));
        }
        $crawler = $client->request('GET', $this->url);
        $view_state = $crawler->filterXPath("//input[contains(@id,'javax.faces.ViewState')]")->attr("value");

        // Devolver los datos útiles en un arreglo
        $data['cookies'] = $cookies;
        $data['view_state'] = $view_state;
        $data['id_captcha'] = $id_captcha;
        return $data;
    }

    public function getCaptchaCode($cookie, $id_captcha)
    {
        // Realizar una segunda solicitud HTTP GET para obtener la imagen de captcha
        $url = 'http://procesos.seace.gob.pe/seacebus-uiwd-pub/javax.faces.resource/dynamiccontent.properties.xhtml?ln=primefaces&pfdrid=' . $id_captcha;
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => 'Cookie: ' . $cookie . "\r\n",
            ),
        ));
        $response = file_get_contents($url, false, $context);

        // Devolver la imagen de captcha codificada en base64
        return base64_encode($response);
    }

    public function concurso()
    {
        // Redirigir al usuario a la ruta "concurso"
        return redirect()->route('concurso');
    }

    public function saveData(Request $request)
    {
        $model = new Concurso;
        Concurso::query()->truncate();
        DetalleConcurso::query()->truncate();

        $client = new Client();
        $fecha_inicio = $this->fromStringToDate($request->fecha_inicio_publicacion);
        $fecha_final = $this->fromStringToDate($request->fecha_inicio_publicacion);
        $cookies = $request->cookie;
        $view_state = $request->view_state;
        $captcha = $request->captcha;
        $postFieldsArray = [
            'javax.faces.partial.ajax' => 'true',
            'javax.faces.source' => 'tbBuscador:idFormBuscarProceso:btnBuscarSel',
            'javax.faces.partial.execute' => '@all',
            'javax.faces.partial.render' => 'tbBuscador:idFormBuscarProceso:pnlGrdResultadosProcesos tbBuscador:idFormBuscarProceso:footerBuscador tbBuscador:idFormBuscarProceso:captchaImg tbBuscador:idFormBuscarProceso:codigoCaptcha frmMesajes:gPrincipal tbBuscador:idFormBuscarProceso:btnBuscarSel tbBuscador:idFormBuscarProceso:pnlBuscarProceso',
            'tbBuscador:idFormBuscarProceso:btnBuscarSel' => 'tbBuscador:idFormBuscarProceso:btnBuscarSel',
            'submit' => 'S',
            'tbBuscador:idFormBuscarProceso' => 'tbBuscador:idFormBuscarProceso',
            'tbBuscador:idFormBuscarProceso:hddNumeroRuc' => '',
            'tbBuscador:idFormBuscarProceso:nombreEntidad' => '',
            'tbBuscador:idFormBuscarProceso:j_idt32_input' => '',
            'tbBuscador:idFormBuscarProceso:j_idt32_focus' => '',
            'tbBuscador:idFormBuscarProceso:j_idt41_input' => '',
            'tbBuscador:idFormBuscarProceso:j_idt41_focus' => '',
            'tbBuscador:idFormBuscarProceso:numeroSeleccion' => '',
            'tbBuscador:idFormBuscarProceso:descripcionObjeto' => '',
            'tbBuscador:idFormBuscarProceso:anioConvocatoria_input' => '2023',
            'tbBuscador:idFormBuscarProceso:anioConvocatoria_focus' => '',
            'tbBuscador:idFormBuscarProceso:j_idt67_input' => '3',
            'tbBuscador:idFormBuscarProceso:j_idt67_focus' => '',
            'tbBuscador:idFormBuscarProceso:codigoSnip' => '',
            'tbBuscador:idFormBuscarProceso:CUI' => '',
            'tbBuscador:idFormBuscarProceso:siglasEntidad' => '',
            'tbBuscador:idFormBuscarProceso:j_idt95_input' => '',
            'tbBuscador:idFormBuscarProceso:j_idt95_focus' => '',
            'tbBuscador:idFormBuscarProceso:departamento_input' => '',
            'tbBuscador:idFormBuscarProceso:departamento_focus' => '',
            'tbBuscador:idFormBuscarProceso:provincia_input' => '',
            'tbBuscador:idFormBuscarProceso:provincia_focus' => '',
            'tbBuscador:idFormBuscarProceso:distrito_input' => '',
            'tbBuscador:idFormBuscarProceso:distrito_focus' => '',
            'tbBuscador:idFormBuscarProceso:numeroConvocatoria' => '',
            'tbBuscador:idFormBuscarProceso:j_idt122_input' => '',
            'tbBuscador:idFormBuscarProceso:j_idt122_focus' => '',
            'tbBuscador:idFormBuscarProceso:dfechaInicio_input' => $fecha_inicio,
            'tbBuscador:idFormBuscarProceso:dfechaFin_input' => $fecha_final,
            'tbBuscador:idFormBuscarProceso:j_idt85_collapsed' => 'false',
            'tbBuscador:idFormBuscarProceso:codigoCaptcha' => $captcha,
            'tbBuscador:idFormBuscarProceso:txtNombreEntidad' => '',
            'tbBuscador:idFormBuscarProceso:txtRucEntidad' => '',
            'tbBuscador:idFormBuscarProceso:txtsigla' => '',
            'javax.faces.ViewState' => $view_state,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($postFieldsArray),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/xml, text/xml, */*; q=0.01',
                'Accept-Language: es-CO,es;q=0.9',
                'Connection: keep-alive',
                'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                'Faces-Request: partial/ajax',
                'Origin: http://procesos.seace.gob.pe',
                'Referer: ' . $this->url,
                'Sec-Fetch-Dest: empty',
                'Sec-Fetch-Mode: cors',
                'Sec-Fetch-Site: same-origin',
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
                'X-Requested-With: XMLHttpRequest',
                'sec-ch-ua: "Chromium";v="104", " Not A;Brand";v="99", "Google Chrome";v="104"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Windows"',
                'Cookie: ' . $cookies,
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $client = new Client();
        $client = new Client(HttpClient::create(array(
            'headers' => array(
                'Host' => 'procesos.seace.gob.pe',
                'Referer' => $this->url,
                'Cookie' => $cookies,
            ),
        )));
        $crawler = $client->request('GET', $this->url);

        //PAGINADOR
        $view_state_post_contratos = "";
        $view = $crawler->filterXPath("//input[contains(@id,'javax.faces.ViewState')]")->each(function ($node) use (&$view_state_post_contratos) {
            $view_state_post_contratos = $node->attr("value");
        });

        $paginator = "";
        $view = $crawler->filterXPath("//div[contains(@id,'tbBuscador:idFormBuscarProceso:dtProcesos_paginator_bottom')]/span[1]")->each(function ($node) use (&$paginator) {
            $paginator = $this->textValidation($node);
        });

        $find_total_page = "1/";
        $pos_total_page = strpos($paginator, $find_total_page) + strlen($find_total_page);
        $total_page = substr($paginator, $pos_total_page, 3);
        $total_page = str_replace(' ', '', $total_page);
        $total_page = str_replace(']', '', $total_page);
        // echo $total_page;
        $data = array();
        $crawler->filter('table[id="tbBuscador:idFormBuscarProceso:pnlGrdResultadosProcesos"] tbody tbody tr')->each(function ($node) use (&$data, &$url, &$cookies, &$fecha_inicio, &$fecha_final, &$view_state_post_contratos) {
            $model = new Concurso;
            $model->acciones = $this->textValidation($node, 'td:nth-child(13) a:nth-child(1)', 'onclick');
            $acciones = $model->acciones;
            $find_proceso = "nidProceso':'";
            $pos_proceso = strpos($acciones, $find_proceso) + strlen($find_proceso);
            $proceso = substr($acciones, $pos_proceso, 6);
            $find_convocatoria = "','nidConvocatoria':'";
            $pos_proceso = strpos($acciones, $find_convocatoria) + strlen($find_convocatoria);
            $convocatoria = substr($acciones, $pos_proceso, 6);
            $model->convocatoria = $convocatoria;
            $model->proceso = $proceso;
            $model->numero = $this->textValidation($node, 'td:nth-child(1)');
            $model->nombre_o_sigla_de_la_entidad = $this->textValidation($node, 'td:nth-child(2)');
            $model->fecha_y_hora_de_publicacion = $this->textValidation($node, 'td:nth-child(3)');
            $model->nomenclatura = $this->textValidation($node, 'td:nth-child(4)');
            $model->reiniciado_desde = $this->textValidation($node, 'td:nth-child(5)');
            $model->objeto_de_contratacion = $this->textValidation($node, 'td:nth-child(6)');
            $model->descripcion_de_objeto = $this->textValidation($node, 'td:nth-child(7)');
            $model->codigo_snip = $this->textValidation($node, 'td:nth-child(8)');
            $model->codigo_unico_de_inversion = $this->textValidation($node, 'td:nth-child(9)');
            $model->valor_estimado = $this->textValidation($node, 'td:nth-child(10)');
            $model->moneda = $this->textValidation($node, 'td:nth-child(11)');
            $model->version_seace = $this->textValidation($node, 'td:nth-child(12)');
            if ($this->findModel($model->convocatoria)) {
                $model->save();
                $this->saveDetail($model, $fecha_inicio, $fecha_final, $cookies, $view_state_post_contratos);
            } else {
                echo "Este concurso ya esta guardado.";
            }
        });
        //INICIO PAGINADOR LA PRIMERA PETICIÓN QUE SE HIZO, FUÉ NECESARIA PARA OBTENER TOTAL DE PAGINAS
        if (intval($total_page) > 1) {
            echo intval($total_page);
            $counter = 15;
            for ($i = 1; $i <= intval($total_page); $i++) {
                $counter += 15;
                $postFieldsArray = [
                    'javax.faces.partial.ajax' => 'true',
                    'javax.faces.source' => 'tbBuscador:idFormBuscarProceso:dtProcesos',
                    'javax.faces.partial.execute' => 'tbBuscador:idFormBuscarProceso:dtProcesos',
                    'javax.faces.partial.render' => 'tbBuscador:idFormBuscarProceso:dtProcesos',
                    'javax.faces.behavior.event' => 'page',
                    'javax.faces.partial.event' => 'page',
                    'tbBuscador:idFormBuscarProceso:dtProcesos_pagination' => 'true',
                    'tbBuscador:idFormBuscarProceso:dtProcesos_first' => $counter,
                    'tbBuscador:idFormBuscarProceso:dtProcesos_rows' => '15',
                    'tbBuscador:idFormBuscarProceso:dtProcesos_encodeFeature' => 'true',
                    'tbBuscador:idFormBuscarProceso' => 'tbBuscador:idFormBuscarProceso',
                    'tbBuscador:idFormBuscarProceso:hddNumeroRuc' => '',
                    'tbBuscador:idFormBuscarProceso:nombreEntidad' => '',
                    'tbBuscador:idFormBuscarProceso:j_idt32_input' => '',
                    'tbBuscador:idFormBuscarProceso:j_idt32_focus' => '',
                    'tbBuscador:idFormBuscarProceso:j_idt41_input' => '',
                    'tbBuscador:idFormBuscarProceso:j_idt41_focus' => '',
                    'tbBuscador:idFormBuscarProceso:numeroSeleccion' => '',
                    'tbBuscador:idFormBuscarProceso:descripcionObjeto' => '',
                    'tbBuscador:idFormBuscarProceso:anioConvocatoria_input' => '2023',
                    'tbBuscador:idFormBuscarProceso:anioConvocatoria_focus' => '',
                    'tbBuscador:idFormBuscarProceso:j_idt67_input' => '3',
                    'tbBuscador:idFormBuscarProceso:j_idt67_focus' => '',
                    'tbBuscador:idFormBuscarProceso:codigoSnip' => '',
                    'tbBuscador:idFormBuscarProceso:CUI' => '',
                    'tbBuscador:idFormBuscarProceso:siglasEntidad' => '',
                    'tbBuscador:idFormBuscarProceso:j_idt95_input' => '',
                    'tbBuscador:idFormBuscarProceso:j_idt95_focus' => '',
                    'tbBuscador:idFormBuscarProceso:departamento_input' => '',
                    'tbBuscador:idFormBuscarProceso:departamento_focus' => '',
                    'tbBuscador:idFormBuscarProceso:provincia_input' => '',
                    'tbBuscador:idFormBuscarProceso:provincia_focus' => '',
                    'tbBuscador:idFormBuscarProceso:distrito_input' => '',
                    'tbBuscador:idFormBuscarProceso:distrito_focus' => '',
                    'tbBuscador:idFormBuscarProceso:numeroConvocatoria' => '',
                    'tbBuscador:idFormBuscarProceso:j_idt122_input' => '',
                    'tbBuscador:idFormBuscarProceso:j_idt122_focus' => '',
                    'tbBuscador:idFormBuscarProceso:dfechaInicio_input' => $fecha_inicio,
                    'tbBuscador:idFormBuscarProceso:dfechaFin_input' => $fecha_final,
                    'tbBuscador:idFormBuscarProceso:j_idt85_collapsed' => 'false',
                    'tbBuscador:idFormBuscarProceso:codigoCaptcha' => '',
                    'tbBuscador:idFormBuscarProceso:txtNombreEntidad' => '',
                    'tbBuscador:idFormBuscarProceso:txtRucEntidad' => '',
                    'tbBuscador:idFormBuscarProceso:txtsigla' => '',
                    'javax.faces.ViewState' => $view_state,
                ];

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $this->url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => http_build_query($postFieldsArray),
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/xml, text/xml, */*; q=0.01',
                        'Accept-Language: es-CO,es;q=0.9,es-419;q=0.8,en;q=0.7',
                        'Connection: keep-alive',
                        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                        'Cookie: ' . $cookies,
                        'Faces-Request: partial/ajax',
                        'Origin: http://procesos.seace.gob.pe',
                        'Referer: ' . $this->url,
                        'Sec-Fetch-Dest: empty',
                        'Sec-Fetch-Mode: cors',
                        'Sec-Fetch-Site: same-origin',
                        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
                        'X-Requested-With: XMLHttpRequest',
                        'sec-ch-ua: "Chromium";v="104", " Not A;Brand";v="99", "Google Chrome";v="104"',
                        'sec-ch-ua-mobile: ?0',
                        'sec-ch-ua-platform: "Windows"',
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);

                $client = new Client();
                $client = new Client(HttpClient::create(array(
                    'headers' => array(
                        'Host' => 'procesos.seace.gob.pe',
                        'Referer' => $url,
                        'Cookie' => $cookies,
                    ),
                )));
                $crawler = $client->request('GET', $url);
                $crawler->filter('table[id="tbBuscador:idFormBuscarProceso:pnlGrdResultadosProcesos"] tbody tbody tr')->each(function ($node) use (&$url, &$cookies, &$fecha_inicio, &$fecha_final, &$view_state_post_contratos) {
                    $model = new Concurso;
                    $model->acciones = $this->textValidation($node, 'td:nth-child(13) a:nth-child(1)', 'onclick');
                    $acciones = $model->acciones;
                    $find_proceso = "nidProceso':'";
                    $pos_proceso = strpos($acciones, $find_proceso) + strlen($find_proceso);
                    $proceso = substr($acciones, $pos_proceso, 6);
                    $find_convocatoria = "','nidConvocatoria':'";
                    $pos_proceso = strpos($acciones, $find_convocatoria) + strlen($find_convocatoria);
                    $convocatoria = substr($acciones, $pos_proceso, 6);
                    $model->convocatoria = $convocatoria;
                    $model->proceso = $proceso;
                    $model->numero = $this->textValidation($node, 'td:nth-child(1)');
                    $model->nombre_o_sigla_de_la_entidad = $this->textValidation($node, 'td:nth-child(2)');
                    $model->fecha_y_hora_de_publicacion = $this->textValidation($node, 'td:nth-child(3)');
                    $model->nomenclatura = $this->textValidation($node, 'td:nth-child(4)');
                    $model->reiniciado_desde = $this->textValidation($node, 'td:nth-child(5)');
                    $model->objeto_de_contratacion = $this->textValidation($node, 'td:nth-child(6)');
                    $model->descripcion_de_objeto = $this->textValidation($node, 'td:nth-child(7)');
                    $model->codigo_snip = $this->textValidation($node, 'td:nth-child(8)');
                    $model->codigo_unico_de_inversion = $this->textValidation($node, 'td:nth-child(9)');
                    $model->valor_estimado = $this->textValidation($node, 'td:nth-child(10)');
                    $model->moneda = $this->textValidation($node, 'td:nth-child(11)');
                    $model->version_seace = $this->textValidation($node, 'td:nth-child(12)');
                    if ($this->findModel($model->convocatoria)) {
                        $model->save();
                        $this->saveDetail($model, $fecha_inicio, $fecha_final, $cookies, $view_state_post_contratos);
                    } else {
                        echo "Este concurso ya esta guardado.";
                    }
                });
            }
        }
        //FIN PAGINADOR
    }

    public function show($convocatoria)
    {
        // Buscar los detalles del concurso a partir de su convocatoria y tipos de detalle específicos
        $detalle_concurso = DetalleConcurso::where('convocatoria', $convocatoria)
            ->whereIn('tipo_id', [1, 2, 3, 4, 5])
            ->get();

        // Buscar los documentos del concurso a partir de su convocatoria y tipo de detalle específico
        $documentos = DetalleConcurso::where('convocatoria', $convocatoria)
            ->whereIn('tipo_id', [6])
            ->get();

        // Buscar los botones de imágenes del concurso a partir de su convocatoria y tipo de detalle específico
        $botoneria_imagenes = DetalleConcurso::where('convocatoria', $convocatoria)
            ->whereIn('tipo_id', [7])
            ->where('key', '')
            ->get();

        // Buscar los botones de enlaces del concurso a partir de su convocatoria y tipo de detalle específico
        $botoneria_enlaces = DetalleConcurso::where('convocatoria', $convocatoria)
            ->whereIn('tipo_id', [7])
            ->where('key', '<>', '')
            ->get();

        // Decodificar el valor de los documentos, que se almacenan en formato JSON
        foreach ($documentos as $key => $documento) {
            $documentos[$key]->value = json_decode($documentos[$key]->value);
        }

        // Pasar la información del concurso y los documentos a la vista "show"
        return view('concursos.show', compact('detalle_concurso', 'documentos', 'convocatoria', 'botoneria_imagenes', 'botoneria_enlaces'));
    }

    public function botoneria($convocatoria, $btn_url)
    {
        // Generar el identificador de formulario a partir de la URL del botón
        $find_tb_ficha = "tbFicha:j_idt";
        $pos_tb_ficha = strpos($btn_url, $find_tb_ficha) + strlen($find_tb_ficha);
        $tb_ficha = $find_tb_ficha . substr($btn_url, $pos_tb_ficha, 4);

        // Obtener las cookies y la imagen de captcha para la vista "botoneria"
        $model = new Concurso;
        $cookie = $this->getCookies();
        $image_captcha = $this->getCaptchaCode($cookie, "id_captcha //PENDIENTE");

        // Pasar la información necesaria a la vista "botoneria"
        return view('concursos.botoneria', compact('image_captcha', 'cookie', 'convocatoria', 'tb_ficha'));
    }

    public function printPant(Request $request)
    {
        // Crear una nueva instancia de la clase "Concurso"
        $model = new Concurso;

        // Enviar una solicitud HTTP POST a la URL utilizando la información del usuario
        $model->AllRequest($this->url, $request->cookie, $request->view_state, $request->captcha, $request->convocatoria, $request->tb_ficha);
    }

    public function fromStringToDate($string_date)
    {
        // Crear una nueva instancia de la clase "DateTime" a partir de la cadena de texto
        $datetime = new DateTime($string_date);

        // Formatear la fecha en un formato legible y devolverla como una cadena de texto
        return $datetime->format('d/m/Y');
    }

    public function textValidation($node, $selector = "", $attr = null)
    {
        if ($selector == "") {
            if (is_null($attr)) {
                // Si no se especifica ningún atributo, devolver el texto del nodo si existe
                if ($node->count()) {
                    return $node->text();
                } else {
                    return "";
                }
            } else {
                // Si se especifica un atributo, devolver el valor del atributo si existe
                if ($node->count()) {
                    if ($node->attr($attr) == "") {
                        return $node->attr("href");
                    }
                    return $node->attr($attr);
                } else {
                    return "";
                }
            }
        }
        if (!is_null($attr)) {
            // Si se especifica un selector y un atributo, devolver el valor del atributo si existe
            if ($node->filter($selector)->count()) {
                return $node->filter($selector)->attr($attr);
            } else {
                return '';
            }
        }
        // Si se especifica solo un selector, devolver el texto del nodo si existe
        if ($node->filter($selector)->count()) {
            return $node->filter($selector)->text();
        } else {
            return '';
        }
    }

    public function findModel($convocatoria)
    {
        // Busca el modelo en la base de datos
        $model = Concurso::where('convocatoria', $convocatoria)->first();

        // Si se encuentra el modelo, devuelve "false", de lo contrario, devuelve "true"
        return (!$model) ? true : false;
    }

    public function saveDetail($model, $fecha_inicio, $fecha_final, $cookies, $view_state)
    {
        $proceso = $model->proceso;
        $convocatoria = $model->convocatoria;

        $postFieldsArray = [
            'tbBuscador:idFormBuscarProceso' => 'tbBuscador:idFormBuscarProceso',
            'tbBuscador:idFormBuscarProceso:hddNumeroRuc' => '',
            'tbBuscador:idFormBuscarProceso:nombreEntidad' => '',
            'tbBuscador:idFormBuscarProceso:j_idt32_input' => '',
            'tbBuscador:idFormBuscarProceso:j_idt32_focus' => '',
            'tbBuscador:idFormBuscarProceso:j_idt41_input' => '',
            'tbBuscador:idFormBuscarProceso:j_idt41_focus' => '',
            'tbBuscador:idFormBuscarProceso:numeroSeleccion' => '',
            'tbBuscador:idFormBuscarProceso:descripcionObjeto' => '',
            'tbBuscador:idFormBuscarProceso:anioConvocatoria_input' => '2023',
            'tbBuscador:idFormBuscarProceso:anioConvocatoria_focus' => '',
            'tbBuscador:idFormBuscarProceso:j_idt67_input' => '3',
            'tbBuscador:idFormBuscarProceso:j_idt67_focus' => '',
            'tbBuscador:idFormBuscarProceso:codigoSnip' => '',
            'tbBuscador:idFormBuscarProceso:CUI' => '',
            'tbBuscador:idFormBuscarProceso:siglasEntidad' => '',
            'tbBuscador:idFormBuscarProceso:j_idt95_input' => '',
            'tbBuscador:idFormBuscarProceso:j_idt95_focus' => '',
            'tbBuscador:idFormBuscarProceso:departamento_input' => '',
            'tbBuscador:idFormBuscarProceso:departamento_focus' => '',
            'tbBuscador:idFormBuscarProceso:provincia_input' => '',
            'tbBuscador:idFormBuscarProceso:provincia_focus' => '',
            'tbBuscador:idFormBuscarProceso:distrito_input' => '',
            'tbBuscador:idFormBuscarProceso:distrito_focus' => '',
            'tbBuscador:idFormBuscarProceso:numeroConvocatoria' => '',
            'tbBuscador:idFormBuscarProceso:j_idt122_input' => '',
            'tbBuscador:idFormBuscarProceso:j_idt122_focus' => '',
            'tbBuscador:idFormBuscarProceso:dfechaInicio_input' => urlencode($fecha_inicio),
            'tbBuscador:idFormBuscarProceso:dfechaFin_input' => urlencode($fecha_final),
            'tbBuscador:idFormBuscarProceso:j_idt85_collapsed' => false,
            'tbBuscador:idFormBuscarProceso:codigoCaptcha' => '',
            'tbBuscador:idFormBuscarProceso:txtNombreEntidad' => '',
            'tbBuscador:idFormBuscarProceso:txtRucEntidad' => '',
            'tbBuscador:idFormBuscarProceso:txtsigla' => '',
            'javax.faces.ViewState' => $view_state,
            'nidSistema' => '3',
            'ptoRetorno' => 'LOCAL',
            'tbBuscador:idFormBuscarProceso:dtProcesos:0:j_idt229' => 'tbBuscador:idFormBuscarProceso:dtProcesos:0:j_idt229',
            'ntipo' => '1',
            'nidProceso' => $proceso,
            'nidConvocatoria' => $convocatoria,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($postFieldsArray),
            CURLOPT_HTTPHEADER => array(
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
                'Cache-Control: max-age=0',
                'Connection: keep-alive',
                'Content-Type: application/x-www-form-urlencoded',
                'Origin: http://procesos.seace.gob.pe',
                'Referer: ' . $this->url,
                'Upgrade-Insecure-Requests: 1',
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
                'Cookie: ' . $cookies,
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;

        $crawler = new Crawler($response);
        //Información General
        $crawler->filterXpath("//fieldset/legend[contains(text(),'Convocatoria')]//following-sibling::div/table/tbody/tr[2]/td/table/tbody/tr")->each(function ($node) use (&$convocatoria) {
            $key = $this->textValidation($node, 'td:nth-child(1)');
            $value = $this->textValidation($node, 'td:nth-child(2)');
            $model = new DetalleConcurso;
            $model->key = $key;
            $model->value = $value;
            $model->convocatoria = $convocatoria;
            $model->tipo_id = 1;
            $model->save();
            echo "Guardando Información General - Convocatoria: " . $convocatoria . "<br>";
        });
        // // Información general de la Entidad
        $crawler->filterXpath("//fieldset/legend[contains(text(),'Convocatoria')]//following-sibling::div/table/tbody/tr[6]/td/table/tbody/tr")->each(function ($node) use (&$convocatoria) {
            $key = $this->textValidation($node, 'td:nth-child(1)');
            $value = $this->textValidation($node, 'td:nth-child(2)');
            $model = new DetalleConcurso;
            $model->key = $key;
            $model->value = $value;
            $model->convocatoria = $convocatoria;
            $model->tipo_id = 2;
            $model->save();
            echo "Guardando Información general de la Entidad - Convocatoria: " . $convocatoria . "<br>";
        });
        // //Información general del procedimiento
        $crawler->filterXpath("//fieldset/legend[contains(text(),'Convocatoria')]//following-sibling::div/table/tbody/tr[9]/td/table/tbody/tr")->each(function ($node) use (&$convocatoria) {
            $key = $this->textValidation($node, 'td:nth-child(1)');
            $value = $this->textValidation($node, 'td:nth-child(2)');
            $model = new DetalleConcurso;
            $model->key = $key;
            $model->value = $value;
            $model->convocatoria = $convocatoria;
            $model->tipo_id = 3;
            $model->save();
            echo "Guardando Información general del procedimiento - Convocatoria: " . $convocatoria . "<br>";
        });

        // //Entidad Contratante
        $crawler->filterXpath("//tbody[contains(@id,'tbFicha:dtEntidadContrata_data')]/tr")->each(function ($node) use (&$convocatoria) {
            $key = $this->textValidation($node, 'td:nth-child(1)');
            $value = $this->textValidation($node, 'td:nth-child(2)');
            $model = new DetalleConcurso;
            $model->key = $key;
            $model->value = $value;
            $model->convocatoria = $convocatoria;
            $model->tipo_id = 5;
            $model->save();
            echo "Guardando Entidad Contratante - Convocatoria: " . $convocatoria . "<br>";
        });

        // //Lista de Documentos
        $crawler->filterXpath("//tbody[contains(@id,'tbFicha:dtDocumentos_data')]/tr")->each(function ($node) use (&$convocatoria) {
            $key = "Lista de Documentos";
            $nro = $this->textValidation($node, 'td:nth-child(1)');
            $etapa = $this->textValidation($node, 'td:nth-child(2)');
            $documento = $this->textValidation($node, 'td:nth-child(3)');
            $archivo = $this->textValidation($node, 'td:nth-child(4)');
            $fecha_y_hora_de_publicacion = $this->textValidation($node, 'td:nth-child(5)');
            $acciones = $this->textValidation($node, 'td:nth-child(6)');

            $value["nro"] = $nro;
            $value["etapa"] = $etapa;
            $value["documento"] = $documento;
            $value["archivo"] = $archivo;
            $value["fecha_y_hora_de_publicacion"] = $fecha_y_hora_de_publicacion;
            $value["acciones"] = $acciones;

            $model = new DetalleConcurso;
            $model->key = $key;
            $model->value = json_encode($value);
            $model->convocatoria = $convocatoria;
            $model->tipo_id = 6;
            $model->save();
            echo "Guardando Lista de Documentos - Convocatoria: " . $convocatoria . "<br>";
        });

        sleep(3);
    }
}
