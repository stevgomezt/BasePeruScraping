<?php

namespace App\Models;

use App\Models\DetalleConcurso;
use DateTime;
use Goutte\Client;
// use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class Concurso extends Model
{
    use HasFactory;

    public function textValidation($node, $selector = "", $attr = null)
    {
        if ($selector == "") {
            if (is_null($attr)) {
                if ($node->count()) {
                    return $node->text();
                } else {
                    return "";
                }
            } else {
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
            if ($node->filter($selector)->count()) {
                return $node->filter($selector)->attr($attr);
            } else {
                return '';
            }
        }
        if ($node->filter($selector)->count()) {
            return $node->filter($selector)->text();
        } else {
            return '';
        }
    }

    public function fromStringToDate($string_date)
    {
        $datetime = new DateTime($string_date);
        return $datetime->format('d/m/Y');
    }

    public function getCaptchaCode($cookie)
    {
        $url = 'http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml';
        $id_captcha = $this->getIdCaptcha($url, $cookie);
        return $this->printImageCaptcha($id_captcha, $cookie);
    }

    public function getIdCaptcha($url, $cookie)
    {
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        $response = curl_exec($curl);
        $find = 'pfdrid=';
        $pos = strpos($response, $find);
        $total = strlen($response);
        $pos = $pos + strlen($find) - $total;
        return substr($response, $pos, 43);
    }

    public function printImageCaptcha($id_captcha, $cookie)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, 'http://procesos.seace.gob.pe/seacebus-uiwd-pub/javax.faces.resource/dynamiccontent.properties.xhtml?ln=primefaces&pfdrid=' . $id_captcha);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        $response = curl_exec($curl);
        curl_close($curl);
        $image_data = base64_encode($response);
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
        $cookies = "";
        foreach ($matches[1] as $item) {
            $cookies .= $item . "; ";
        }
        $data[0] = $image_data;
        $data[1] = $cookies;
        return $data;
    }

    public function getCookies($url)
    {
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($curl);

        $client = new Client();
        $client = new Client(HttpClient::create(array(
            'headers' => array(
                'Host' => 'procesos.seace.gob.pe',
                'Referer' => $url,
                // 'Cookie' => $cookies,
            ),
        )));
        $crawler = $client->request('GET', $url);
        $view_state = "";
        $view = $crawler->filterXPath("//input[contains(@id,'javax.faces.ViewState')]")->each(function ($node) use (&$view_state) {
            $view_state = $node->attr("value");
            // dd($view_state);
            // los valores que retorna no coinciden con los de la pagina
        });

        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
        $cookies = "";
        foreach ($matches[1] as $item) {
            $cookies .= $item . "; ";
        }

        $data['cookies'] = $cookies;
        $data['view_state'] = $view_state;

        return ($data);

        echo ($view_state);
        echo '</br>';

    }

    public function saveData($url, $cookies, $view_state, $captcha, $fecha_inicio, $fecha_final)
    {

        Concurso::query()->truncate();
        DetalleConcurso::query()->truncate();

        $url = 'http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml';

        $client = new Client();
        $fecha_inicio = $this->fromStringToDate($fecha_inicio);
        $fecha_final = $this->fromStringToDate($fecha_final);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'javax.faces.partial.ajax=true&javax.faces.source=tbBuscador%3AidFormBuscarProceso%3AbtnBuscarSel&javax.faces.partial.execute=%40all&javax.faces.partial.render=tbBuscador%3AidFormBuscarProceso%3ApnlGrdResultadosProcesos+tbBuscador%3AidFormBuscarProceso%3AfooterBuscador+tbBuscador%3AidFormBuscarProceso%3AcaptchaImg+tbBuscador%3AidFormBuscarProceso%3AcodigoCaptcha+frmMesajes%3AgPrincipal+tbBuscador%3AidFormBuscarProceso%3AbtnBuscarSel+tbBuscador%3AidFormBuscarProceso%3ApnlBuscarProceso&tbBuscador%3AidFormBuscarProceso%3AbtnBuscarSel=tbBuscador%3AidFormBuscarProceso%3AbtnBuscarSel&submit=S&tbBuscador%3AidFormBuscarProceso=tbBuscador%3AidFormBuscarProceso&tbBuscador%3AidFormBuscarProceso%3AhddNumeroRuc=&tbBuscador%3AidFormBuscarProceso%3AnombreEntidad=&tbBuscador%3AidFormBuscarProceso%3Aj_idt32_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt32_focus=&tbBuscador%3AidFormBuscarProceso%3Aj_idt41_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt41_focus=&tbBuscador%3AidFormBuscarProceso%3AnumeroSeleccion=&tbBuscador%3AidFormBuscarProceso%3AdescripcionObjeto=&tbBuscador%3AidFormBuscarProceso%3AanioConvocatoria_input=2023&tbBuscador%3AidFormBuscarProceso%3AanioConvocatoria_focus=&tbBuscador%3AidFormBuscarProceso%3Aj_idt67_input=3&tbBuscador%3AidFormBuscarProceso%3Aj_idt67_focus=&tbBuscador%3AidFormBuscarProceso%3AcodigoSnip=&tbBuscador%3AidFormBuscarProceso%3ACUI=&tbBuscador%3AidFormBuscarProceso%3AsiglasEntidad=&tbBuscador%3AidFormBuscarProceso%3Aj_idt95_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt95_focus=&tbBuscador%3AidFormBuscarProceso%3Adepartamento_input=&tbBuscador%3AidFormBuscarProceso%3Adepartamento_focus=&tbBuscador%3AidFormBuscarProceso%3Aprovincia_input=&tbBuscador%3AidFormBuscarProceso%3Aprovincia_focus=&tbBuscador%3AidFormBuscarProceso%3Adistrito_input=&tbBuscador%3AidFormBuscarProceso%3Adistrito_focus=&tbBuscador%3AidFormBuscarProceso%3AnumeroConvocatoria=&tbBuscador%3AidFormBuscarProceso%3Aj_idt122_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt122_focus=&tbBuscador%3AidFormBuscarProceso%3AdfechaInicio_input=' . $fecha_inicio . '&tbBuscador%3AidFormBuscarProceso%3AdfechaFin_input=' . $fecha_final . '&tbBuscador%3AidFormBuscarProceso%3Aj_idt85_collapsed=false&tbBuscador%3AidFormBuscarProceso%3AcodigoCaptcha=' . $captcha . '&tbBuscador%3AidFormBuscarProceso%3AtxtNombreEntidad=&tbBuscador%3AidFormBuscarProceso%3AtxtRucEntidad=&tbBuscador%3AidFormBuscarProceso%3Atxtsigla=&javax.faces.ViewState=' . $view_state,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/xml, text/xml, */*; q=0.01',
                'Accept-Language: es-CO,es;q=0.9',
                'Connection: keep-alive',
                'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                'Faces-Request: partial/ajax',
                'Origin: http://procesos.seace.gob.pe',
                'Referer: http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml',
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
        // la libreria curl si funciona buscar como extraer la información
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
        // $view_state = "";
        // $view = $crawler->filterXPath("//input[contains(@id,'javax.faces.ViewState')]")->each(function ($node) use (&$view_state) {
        //     $view_state = $node->attr("value");
        //     // dd($view_state);
        //     // los valores que retorna no coinciden con los de la pagina
        // });

        //PAGINADOR
        $paginator = "";
        $view = $crawler->filterXPath("//div[contains(@id,'tbBuscador:idFormBuscarProceso:dtProcesos_paginator_bottom')]/span[1]")->each(function ($node) use (&$paginator) {
            $paginator = $this->textValidation($node);
        });
        $find_total_page = "1/";
        $pos_total_page = strpos($paginator, $find_total_page) + strlen($find_total_page);
        $total_page = substr($paginator, $pos_total_page, 3);
        $total_page = str_replace(' ', '', $total_page);
        $total_page = str_replace(']', '', $total_page);
        echo $total_page;
        $data = array();
        $crawler->filter('table[id="tbBuscador:idFormBuscarProceso:pnlGrdResultadosProcesos"] tbody tbody tr')->each(function ($node) use (&$data, &$url, &$cookies, &$fecha_inicio, &$fecha_final, &$view_state) {
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
                $this->saveDetail($model, $url, $cookies, $fecha_inicio, $fecha_final, $view_state);
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

                $curl = curl_init();

                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => 'javax.faces.partial.ajax=true&javax.faces.source=tbBuscador%3AidFormBuscarProceso%3AdtProcesos&javax.faces.partial.execute=tbBuscador%3AidFormBuscarProceso%3AdtProcesos&javax.faces.partial.render=tbBuscador%3AidFormBuscarProceso%3AdtProcesos&javax.faces.behavior.event=page&javax.faces.partial.event=page&tbBuscador%3AidFormBuscarProceso%3AdtProcesos_pagination=true&tbBuscador%3AidFormBuscarProceso%3AdtProcesos_first=' . $counter . '&tbBuscador%3AidFormBuscarProceso%3AdtProcesos_rows=15&tbBuscador%3AidFormBuscarProceso%3AdtProcesos_encodeFeature=true&tbBuscador%3AidFormBuscarProceso=tbBuscador%3AidFormBuscarProceso&tbBuscador%3AidFormBuscarProceso%3AhddNumeroRuc=&tbBuscador%3AidFormBuscarProceso%3AnombreEntidad=&tbBuscador%3AidFormBuscarProceso%3Aj_idt32_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt32_focus=&tbBuscador%3AidFormBuscarProceso%3Aj_idt41_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt41_focus=&tbBuscador%3AidFormBuscarProceso%3AnumeroSeleccion=&tbBuscador%3AidFormBuscarProceso%3AdescripcionObjeto=&tbBuscador%3AidFormBuscarProceso%3AanioConvocatoria_input=2023&tbBuscador%3AidFormBuscarProceso%3AanioConvocatoria_focus=&tbBuscador%3AidFormBuscarProceso%3Aj_idt67_input=3&tbBuscador%3AidFormBuscarProceso%3Aj_idt67_focus=&tbBuscador%3AidFormBuscarProceso%3AcodigoSnip=&tbBuscador%3AidFormBuscarProceso%3ACUI=&tbBuscador%3AidFormBuscarProceso%3AsiglasEntidad=&tbBuscador%3AidFormBuscarProceso%3Aj_idt95_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt95_focus=&tbBuscador%3AidFormBuscarProceso%3Adepartamento_input=&tbBuscador%3AidFormBuscarProceso%3Adepartamento_focus=&tbBuscador%3AidFormBuscarProceso%3Aprovincia_input=&tbBuscador%3AidFormBuscarProceso%3Aprovincia_focus=&tbBuscador%3AidFormBuscarProceso%3Adistrito_input=&tbBuscador%3AidFormBuscarProceso%3Adistrito_focus=&tbBuscador%3AidFormBuscarProceso%3AnumeroConvocatoria=&tbBuscador%3AidFormBuscarProceso%3Aj_idt122_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt122_focus=&tbBuscador%3AidFormBuscarProceso%3AdfechaInicio_input=' . $fecha_inicio . '&tbBuscador%3AidFormBuscarProceso%3AdfechaFin_input=' . $fecha_final . '&tbBuscador%3AidFormBuscarProceso%3Aj_idt85_collapsed=false&tbBuscador%3AidFormBuscarProceso%3AcodigoCaptcha=&tbBuscador%3AidFormBuscarProceso%3AtxtNombreEntidad=&tbBuscador%3AidFormBuscarProceso%3AtxtRucEntidad=&tbBuscador%3AidFormBuscarProceso%3Atxtsigla=&javax.faces.ViewState=' . $view_state,
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/xml, text/xml, */*; q=0.01',
                        'Accept-Language: es-CO,es;q=0.9,es-419;q=0.8,en;q=0.7',
                        'Connection: keep-alive',
                        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                        'Cookie: ' . $cookies,
                        'Faces-Request: partial/ajax',
                        'Origin: http://procesos.seace.gob.pe',
                        'Referer: http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml',
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
                $crawler->filter('table[id="tbBuscador:idFormBuscarProceso:pnlGrdResultadosProcesos"] tbody tbody tr')->each(function ($node) use (&$url, &$cookies, &$fecha_inicio, &$fecha_final, &$view_state) {
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
                        $this->saveDetail($model, $url, $cookies, $fecha_inicio, $fecha_final, $view_state);
                    } else {
                        echo "Este concurso ya esta guardado.";
                    }
                });
            }
        }
        //FIN PAGINADOR

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'javax.faces.partial.ajax=true&javax.faces.source=tbBuscador%3AidFormBuscarProceso%3AbtnBuscarSel&javax.faces.partial.execute=%40all&javax.faces.partial.render=tbBuscador%3AidFormBuscarProceso%3ApnlGrdResultadosProcesos+tbBuscador%3AidFormBuscarProceso%3AfooterBuscador+tbBuscador%3AidFormBuscarProceso%3AcaptchaImg+tbBuscador%3AidFormBuscarProceso%3AcodigoCaptcha+frmMesajes%3AgPrincipal+tbBuscador%3AidFormBuscarProceso%3AbtnBuscarSel+tbBuscador%3AidFormBuscarProceso%3ApnlBuscarProceso&tbBuscador%3AidFormBuscarProceso%3AbtnBuscarSel=tbBuscador%3AidFormBuscarProceso%3AbtnBuscarSel&submit=S&tbBuscador%3AidFormBuscarProceso=tbBuscador%3AidFormBuscarProceso&tbBuscador%3AidFormBuscarProceso%3AhddNumeroRuc=&tbBuscador%3AidFormBuscarProceso%3AnombreEntidad=&tbBuscador%3AidFormBuscarProceso%3Aj_idt32_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt32_focus=&tbBuscador%3AidFormBuscarProceso%3Aj_idt41_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt41_focus=&tbBuscador%3AidFormBuscarProceso%3AnumeroSeleccion=&tbBuscador%3AidFormBuscarProceso%3AdescripcionObjeto=&tbBuscador%3AidFormBuscarProceso%3AanioConvocatoria_input=2023&tbBuscador%3AidFormBuscarProceso%3AanioConvocatoria_focus=&tbBuscador%3AidFormBuscarProceso%3Aj_idt67_input=3&tbBuscador%3AidFormBuscarProceso%3Aj_idt67_focus=&tbBuscador%3AidFormBuscarProceso%3AcodigoSnip=&tbBuscador%3AidFormBuscarProceso%3ACUI=&tbBuscador%3AidFormBuscarProceso%3AsiglasEntidad=&tbBuscador%3AidFormBuscarProceso%3Aj_idt95_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt95_focus=&tbBuscador%3AidFormBuscarProceso%3Adepartamento_input=&tbBuscador%3AidFormBuscarProceso%3Adepartamento_focus=&tbBuscador%3AidFormBuscarProceso%3Aprovincia_input=&tbBuscador%3AidFormBuscarProceso%3Aprovincia_focus=&tbBuscador%3AidFormBuscarProceso%3Adistrito_input=&tbBuscador%3AidFormBuscarProceso%3Adistrito_focus=&tbBuscador%3AidFormBuscarProceso%3AnumeroConvocatoria=&tbBuscador%3AidFormBuscarProceso%3Aj_idt122_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt122_focus=&tbBuscador%3AidFormBuscarProceso%3AdfechaInicio_input=' . $fecha_inicio . '&tbBuscador%3AidFormBuscarProceso%3AdfechaFin_input=' . $fecha_final . '&tbBuscador%3AidFormBuscarProceso%3Aj_idt85_collapsed=true&tbBuscador%3AidFormBuscarProceso%3AcodigoCaptcha=' . $captcha . '&tbBuscador%3AidFormBuscarProceso%3AtxtNombreEntidad=&tbBuscador%3AidFormBuscarProceso%3AtxtRucEntidad=&tbBuscador%3AidFormBuscarProceso%3Atxtsigla=&javax.faces.ViewState=' . $view_state,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/xml, text/xml, */*; q=0.01',
                'Accept-Language: es-CO,es;q=0.9',
                'Connection: keep-alive',
                'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                'Faces-Request: partial/ajax',
                'Origin: http://procesos.seace.gob.pe',
                'Referer: http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml',
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
                'Referer' => $url,
                'Cookie' => $cookies,
            ),
        )));
        $crawler = $client->request('GET', $url);
    }

    public function findModel($convocatoria)
    {
        $model = Concurso::where('convocatoria', $convocatoria)->first();
        if ($model) {
            return false;
        } else {
            return true;
        }
    }

    public function saveDetail($model, $url, $cookies, $fecha_inicio, $fecha_final, $view_state)
    {
        {
            //$model = Concurso::first();
            $proceso = $model->proceso;
            $convocatoria = $model->convocatoria;
            $post_fields_string = 'tbBuscador%3AidFormBuscarProceso=tbBuscador%3AidFormBuscarProceso&tbBuscador%3AidFormBuscarProceso%3AhddNumeroRuc=&tbBuscador%3AidFormBuscarProceso%3AnombreEntidad=&tbBuscador%3AidFormBuscarProceso%3Aj_idt32_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt32_focus=&tbBuscador%3AidFormBuscarProceso%3Aj_idt41_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt41_focus=&tbBuscador%3AidFormBuscarProceso%3AnumeroSeleccion=&tbBuscador%3AidFormBuscarProceso%3AdescripcionObjeto=&tbBuscador%3AidFormBuscarProceso%3AanioConvocatoria_input=2023&tbBuscador%3AidFormBuscarProceso%3AanioConvocatoria_focus=&tbBuscador%3AidFormBuscarProceso%3Aj_idt67_input=3&tbBuscador%3AidFormBuscarProceso%3Aj_idt67_focus=&tbBuscador%3AidFormBuscarProceso%3AcodigoSnip=&tbBuscador%3AidFormBuscarProceso%3ACUI=&tbBuscador%3AidFormBuscarProceso%3AsiglasEntidad=&tbBuscador%3AidFormBuscarProceso%3Aj_idt95_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt95_focus=&tbBuscador%3AidFormBuscarProceso%3Adepartamento_input=&tbBuscador%3AidFormBuscarProceso%3Adepartamento_focus=&tbBuscador%3AidFormBuscarProceso%3Aprovincia_input=&tbBuscador%3AidFormBuscarProceso%3Aprovincia_focus=&tbBuscador%3AidFormBuscarProceso%3Adistrito_input=&tbBuscador%3AidFormBuscarProceso%3Adistrito_focus=&tbBuscador%3AidFormBuscarProceso%3AnumeroConvocatoria=&tbBuscador%3AidFormBuscarProceso%3Aj_idt122_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt122_focus=&tbBuscador%3AidFormBuscarProceso%3AdfechaInicio_input=' . urlencode($fecha_inicio) . '&tbBuscador%3AidFormBuscarProceso%3AdfechaFin_input=' . urlencode($fecha_final) . '&tbBuscador%3AidFormBuscarProceso%3Aj_idt85_collapsed=false&tbBuscador%3AidFormBuscarProceso%3AcodigoCaptcha=&tbBuscador%3AidFormBuscarProceso%3AtxtNombreEntidad=&tbBuscador%3AidFormBuscarProceso%3AtxtRucEntidad=&tbBuscador%3AidFormBuscarProceso%3Atxtsigla=&javax.faces.ViewState=' . urldecode($view_state) . '&nidSistema=3&ptoRetorno=LOCAL&tbBuscador%3AidFormBuscarProceso%3AdtProcesos%3A0%3Aj_idt229=tbBuscador%3AidFormBuscarProceso%3AdtProcesos%3A0%3Aj_idt229&ntipo=1&nidProceso=' . $proceso . '&nidConvocatoria=' . $convocatoria;
            $http_headers_array = array(
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'Accept-Language: es-CO,es;q=0.9,es-419;q=0.8,en;q=0.7',
                'Cache-Control: max-age=0',
                'Connection: keep-alive',
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: ' . $cookies,
                'Origin: http://procesos.seace.gob.pe',
                'Referer: http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml',
                'Sec-Fetch-Dest: document',
                'Sec-Fetch-Mode: navigate',
                'Sec-Fetch-Site: same-origin',
                'Sec-Fetch-User: ?1',
                'Upgrade-Insecure-Requests: 1',
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
                'sec-ch-ua: "Chromium";v="104", " Not A;Brand";v="99", "Google Chrome";v="104"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Windows"',
            );

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $post_fields_string,
                CURLOPT_HTTPHEADER => $http_headers_array,
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
            //Información general de la Entidad
            // $crawler->filterXpath("//fieldset/legend[contains(text(),'Convocatoria')]//following-sibling::div/table/tbody/tr[6]/td/table/tbody/tr")->each(function ($node) use (&$convocatoria) {
            //     $key = $this->textValidation($node, 'td:nth-child(1)');
            //     $value = $this->textValidation($node, 'td:nth-child(2)');
            //     $model = new DetalleConcurso;
            //     $model->key = $key;
            //     $model->value = $value;
            //     $model->convocatoria = $convocatoria;
            //     $model->tipo_id = 2;
            //     $model->save();
            //     echo "Guardando Información general de la Entidad - Convocatoria: " . $convocatoria . "<br>";
            // });
            // //Información general del procedimiento
            // $crawler->filterXpath("//fieldset/legend[contains(text(),'Convocatoria')]//following-sibling::div/table/tbody/tr[9]/td/table/tbody/tr")->each(function ($node) use (&$convocatoria) {
            //     $key = $this->textValidation($node, 'td:nth-child(1)');
            //     $value = $this->textValidation($node, 'td:nth-child(2)');
            //     $model = new DetalleConcurso;
            //     $model->key = $key;
            //     $model->value = $value;
            //     $model->convocatoria = $convocatoria;
            //     $model->tipo_id = 3;
            //     $model->save();
            //     echo "Guardando Información general del procedimiento - Convocatoria: " . $convocatoria . "<br>";
            // });

            // //Cronograma
            // $crawler->filterXpath("//tbody[contains(@id,'tbFicha:dtCronograma_data')]/tr")->each(function ($node) use (&$convocatoria) {
            //     $key = $this->textValidation($node, 'td:nth-child(1)');
            //     $value = $this->textValidation($node, 'td:nth-child(2)');
            //     $value .= "|";
            //     $value .= $this->textValidation($node, 'td:nth-child(3)');
            //     $model = new DetalleConcurso;
            //     $model->key = $key;
            //     $model->value = $value;
            //     $model->convocatoria = $convocatoria;
            //     $model->tipo_id = 4;
            //     $model->save();
            //     echo "Guardando Cronograma - Convocatoria: " . $convocatoria . "<br>";
            // });

            // //Entidad Contratante
            // $crawler->filterXpath("//tbody[contains(@id,'tbFicha:dtEntidadContrata_data')]/tr")->each(function ($node) use (&$convocatoria) {
            //     $key = $this->textValidation($node, 'td:nth-child(1)');
            //     $value = $this->textValidation($node, 'td:nth-child(2)');
            //     $model = new DetalleConcurso;
            //     $model->key = $key;
            //     $model->value = $value;
            //     $model->convocatoria = $convocatoria;
            //     $model->tipo_id = 5;
            //     $model->save();
            //     echo "Guardando Entidad Contratante - Convocatoria: " . $convocatoria . "<br>";
            // });

            // //Lista de Documentos
            // $crawler->filterXpath("//tbody[contains(@id,'tbFicha:dtDocumentos_data')]/tr")->each(function ($node) use (&$convocatoria) {
            //     $key = "Lista de Documentos";
            //     $nro = $this->textValidation($node, 'td:nth-child(1)');
            //     $etapa = $this->textValidation($node, 'td:nth-child(2)');
            //     $documento = $this->textValidation($node, 'td:nth-child(3)');
            //     $archivo = $this->textValidation($node, 'td:nth-child(4)');
            //     $fecha_y_hora_de_publicacion = $this->textValidation($node, 'td:nth-child(5)');
            //     $acciones = $this->textValidation($node, 'td:nth-child(6)');

            //     $value["nro"] = $nro;
            //     $value["etapa"] = $etapa;
            //     $value["documento"] = $documento;
            //     $value["archivo"] = $archivo;
            //     $value["fecha_y_hora_de_publicacion"] = $fecha_y_hora_de_publicacion;
            //     $value["acciones"] = $acciones;

            //     $model = new DetalleConcurso;
            //     $model->key = $key;
            //     $model->value = json_encode($value);
            //     $model->convocatoria = $convocatoria;
            //     $model->tipo_id = 6;
            //     $model->save();
            //     echo "Guardando Lista de Documentos - Convocatoria: " . $convocatoria . "<br>";
            // });

            // //Opciones del Procedimiento
            // //Botones con imagenes
            // $crawler->filterXpath("//fieldset/legend[contains(text(),'Opciones del procedimiento')]//following-sibling::div/table/tbody/tr[1]/td/a")->each(function ($node) use (&$convocatoria) {
            //     $key = $this->textValidation($node);
            //     $key = $this->textValidation($node);
            //     $value = $this->textValidation($node, '', 'onclick');
            //     $model = new DetalleConcurso;
            //     $model->key = $key;
            //     $model->value = $value;
            //     $model->convocatoria = $convocatoria;
            //     $model->tipo_id = 7;
            //     $model->save();
            //     echo "Guardando Opciones del Procedimiento - Convocatoria: " . $convocatoria . "<br>";
            // });
            // //Botones con imagenes
            // $crawler->filterXpath("//fieldset/legend[contains(text(),'Opciones del procedimiento')]//following-sibling::div/table/tbody/tr[3]/td/a")->each(function ($node) use (&$convocatoria) {
            //     $key = $this->textValidation($node);
            //     $key = $this->textValidation($node);
            //     $value = $this->textValidation($node, '', 'onclick');
            //     $model = new DetalleConcurso;
            //     $model->key = $key;
            //     $model->value = $value;
            //     $model->convocatoria = $convocatoria;
            //     $model->tipo_id = 7;
            //     $model->save();
            //     echo "Guardando Opciones del Procedimiento - Convocatoria: " . $convocatoria . "<br>";
            // });
            // //Botones con imagenes
            // $crawler->filterXpath("//fieldset/legend[contains(text(),'Opciones del procedimiento')]//following-sibling::div/table/tbody/tr[2]/td/a")->each(function ($node) use (&$convocatoria) {
            //     $key = $this->textValidation($node);
            //     $key = $this->textValidation($node);
            //     $value = $this->textValidation($node, '', 'onclick');
            //     $model = new DetalleConcurso;
            //     $model->key = $key;
            //     $model->value = $value;
            //     $model->convocatoria = $convocatoria;
            //     $model->tipo_id = 7;
            //     $model->save();
            //     echo "Guardando Opciones del Procedimiento - Convocatoria: " . $convocatoria . "<br>";
            // });
            // $crawler->filterXpath("//fieldset/legend[contains(text(),'Opciones del procedimiento')]//following-sibling::div/table/tbody/tr[4]/td/a")->each(function ($node) use (&$convocatoria) {
            //     $key = $this->textValidation($node);
            //     $key = $this->textValidation($node);
            //     $value = $this->textValidation($node, '', 'onclick');
            //     $model = new DetalleConcurso;
            //     $model->key = $key;
            //     $model->value = $value;
            //     $model->convocatoria = $convocatoria;
            //     $model->tipo_id = 7;
            //     $model->save();
            //     echo "Guardando Opciones del Procedimiento - Convocatoria: " . $convocatoria . "<br>";
            // });

            sleep(3);
        }
    }

    public function AllRequest($url, $cookies, $view_state, $captcha, $convocatoria, $tb_ficha)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'javax.faces.partial.ajax=true&javax.faces.source=tbBuscador%3AidFormBuscarProceso%3AbtnBuscarSel&javax.faces.partial.execute=%40all&javax.faces.partial.render=tbBuscador%3AidFormBuscarProceso%3ApnlGrdResultadosProcesos+tbBuscador%3AidFormBuscarProceso%3AfooterBuscador+tbBuscador%3AidFormBuscarProceso%3AcaptchaImg+tbBuscador%3AidFormBuscarProceso%3AcodigoCaptcha+frmMesajes%3AgPrincipal+tbBuscador%3AidFormBuscarProceso%3AbtnBuscarSel+tbBuscador%3AidFormBuscarProceso%3ApnlBuscarProceso&tbBuscador%3AidFormBuscarProceso%3AbtnBuscarSel=tbBuscador%3AidFormBuscarProceso%3AbtnBuscarSel&submit=S&tbBuscador%3AidFormBuscarProceso=tbBuscador%3AidFormBuscarProceso&tbBuscador%3AidFormBuscarProceso%3AhddNumeroRuc=&tbBuscador%3AidFormBuscarProceso%3AnombreEntidad=&tbBuscador%3AidFormBuscarProceso%3Aj_idt32_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt32_focus=&tbBuscador%3AidFormBuscarProceso%3Aj_idt41_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt41_focus=&tbBuscador%3AidFormBuscarProceso%3AnumeroSeleccion=&tbBuscador%3AidFormBuscarProceso%3AdescripcionObjeto=&tbBuscador%3AidFormBuscarProceso%3AanioConvocatoria_input=2023&tbBuscador%3AidFormBuscarProceso%3AanioConvocatoria_focus=&tbBuscador%3AidFormBuscarProceso%3Aj_idt67_input=3&tbBuscador%3AidFormBuscarProceso%3Aj_idt67_focus=&tbBuscador%3AidFormBuscarProceso%3AcodigoSnip=&tbBuscador%3AidFormBuscarProceso%3ACUI=&tbBuscador%3AidFormBuscarProceso%3AsiglasEntidad=&tbBuscador%3AidFormBuscarProceso%3Aj_idt95_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt95_focus=&tbBuscador%3AidFormBuscarProceso%3Adepartamento_input=&tbBuscador%3AidFormBuscarProceso%3Adepartamento_focus=&tbBuscador%3AidFormBuscarProceso%3Aprovincia_input=&tbBuscador%3AidFormBuscarProceso%3Aprovincia_focus=&tbBuscador%3AidFormBuscarProceso%3Adistrito_input=&tbBuscador%3AidFormBuscarProceso%3Adistrito_focus=&tbBuscador%3AidFormBuscarProceso%3AnumeroConvocatoria=' . $convocatoria . '&tbBuscador%3AidFormBuscarProceso%3Aj_idt122_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt122_focus=&tbBuscador%3AidFormBuscarProceso%3AdfechaInicio_input=&tbBuscador%3AidFormBuscarProceso%3AdfechaFin_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt85_collapsed=true&tbBuscador%3AidFormBuscarProceso%3AcodigoCaptcha=' . $captcha . '&tbBuscador%3AidFormBuscarProceso%3AtxtNombreEntidad=&tbBuscador%3AidFormBuscarProceso%3AtxtRucEntidad=&tbBuscador%3AidFormBuscarProceso%3Atxtsigla=&javax.faces.ViewState=' . $view_state,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/xml, text/xml, */*; q=0.01',
                'Accept-Language: es-CO,es;q=0.9',
                'Connection: keep-alive',
                'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                'Faces-Request: partial/ajax',
                'Origin: http://procesos.seace.gob.pe',
                'Referer: http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml',
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
        //echo $response;
        $client = new Client();
        $client = new Client(HttpClient::create(array(
            'headers' => array(
                'Host' => 'procesos.seace.gob.pe',
                'Referer' => $url,
                'Cookie' => $cookies,
            ),
        )));
        $crawler = $client->request('GET', $url);
        // $view_state = "";
        // $view = $crawler->filterXPath("//input[contains(@id,'javax.faces.ViewState')]")->each(function ($node) use (&$view_state) {
        //     $view_state = $node->attr("value");
        // });
        //2 Detalle
        $model = Concurso::where('convocatoria', $convocatoria)->first();
        $proceso = $model->proceso;
        $convocatoria = $model->convocatoria;
        $post_fields_string = 'tbBuscador%3AidFormBuscarProceso=tbBuscador%3AidFormBuscarProceso&tbBuscador%3AidFormBuscarProceso%3AhddNumeroRuc=&tbBuscador%3AidFormBuscarProceso%3AnombreEntidad=&tbBuscador%3AidFormBuscarProceso%3Aj_idt32_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt32_focus=&tbBuscador%3AidFormBuscarProceso%3Aj_idt41_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt41_focus=&tbBuscador%3AidFormBuscarProceso%3AnumeroSeleccion=&tbBuscador%3AidFormBuscarProceso%3AdescripcionObjeto=&tbBuscador%3AidFormBuscarProceso%3AanioConvocatoria_input=2023&tbBuscador%3AidFormBuscarProceso%3AanioConvocatoria_focus=&tbBuscador%3AidFormBuscarProceso%3Aj_idt67_input=3&tbBuscador%3AidFormBuscarProceso%3Aj_idt67_focus=&tbBuscador%3AidFormBuscarProceso%3AcodigoSnip=&tbBuscador%3AidFormBuscarProceso%3ACUI=&tbBuscador%3AidFormBuscarProceso%3AsiglasEntidad=&tbBuscador%3AidFormBuscarProceso%3Aj_idt95_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt95_focus=&tbBuscador%3AidFormBuscarProceso%3Adepartamento_input=&tbBuscador%3AidFormBuscarProceso%3Adepartamento_focus=&tbBuscador%3AidFormBuscarProceso%3Aprovincia_input=&tbBuscador%3AidFormBuscarProceso%3Aprovincia_focus=&tbBuscador%3AidFormBuscarProceso%3Adistrito_input=&tbBuscador%3AidFormBuscarProceso%3Adistrito_focus=&tbBuscador%3AidFormBuscarProceso%3AnumeroConvocatoria=' . $convocatoria . '&tbBuscador%3AidFormBuscarProceso%3Aj_idt122_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt122_focus=&tbBuscador%3AidFormBuscarProceso%3AdfechaInicio_input=&tbBuscador%3AidFormBuscarProceso%3AdfechaFin_input=&tbBuscador%3AidFormBuscarProceso%3Aj_idt85_collapsed=false&tbBuscador%3AidFormBuscarProceso%3AcodigoCaptcha=&tbBuscador%3AidFormBuscarProceso%3AtxtNombreEntidad=&tbBuscador%3AidFormBuscarProceso%3AtxtRucEntidad=&tbBuscador%3AidFormBuscarProceso%3Atxtsigla=&javax.faces.ViewState=' . urldecode($view_state) . '&nidSistema=3&ptoRetorno=LOCAL&tbBuscador%3AidFormBuscarProceso%3AdtProcesos%3A0%3Aj_idt229=tbBuscador%3AidFormBuscarProceso%3AdtProcesos%3A0%3Aj_idt229&ntipo=1&nidProceso=' . $proceso . '&nidConvocatoria=' . $convocatoria;
        $http_headers_array = array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Language: es-CO,es;q=0.9,es-419;q=0.8,en;q=0.7',
            'Cache-Control: max-age=0',
            'Connection: keep-alive',
            'Content-Type: application/x-www-form-urlencoded',
            'Cookie: ' . $cookies,
            'Origin: http://procesos.seace.gob.pe',
            'Referer: http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml',
            'Sec-Fetch-Dest: document',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: same-origin',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
            'sec-ch-ua: "Chromium";v="104", " Not A;Brand";v="99", "Google Chrome";v="104"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Windows"',
        );

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post_fields_string,
            CURLOPT_HTTPHEADER => $http_headers_array,
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://procesos.seace.gob.pe/seacebus-uiwd-pub/fichaSeleccion/fichaSeleccion.xhtml',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'tbFicha%3AidFormFichaSeleccion=tbFicha%3AidFormFichaSeleccion&tbFicha%3AfrmPopupCalendarizacion=tbFicha%3AfrmPopupCalendarizacion&javax.faces.ViewState=' . urldecode($view_state) . '&tbFicha%3Aj_idt343_collapsed=false&tbFicha%3AtabItemDet_activeIndex=0&tbFicha%3Aj_idt384_collapsed=true&javax.faces.ViewState=' . urldecode($view_state) . '&' . urlencode($tb_ficha) . '=' . urlencode($tb_ficha),
            CURLOPT_HTTPHEADER => array(
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'Accept-Language: es-CO,es;q=0.9,es-419;q=0.8,en;q=0.7',
                'Cache-Control: max-age=0',
                'Connection: keep-alive',
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: ' . $cookies,
                'Origin: http://procesos.seace.gob.pe',
                'Referer: http://procesos.seace.gob.pe/seacebus-uiwd-pub/fichaSeleccion/fichaSeleccion.xhtml?ptoRetorno=LOCAL',
                'Sec-Fetch-Dest: document',
                'Sec-Fetch-Mode: navigate',
                'Sec-Fetch-Site: same-origin',
                'Sec-Fetch-User: ?1',
                'Upgrade-Insecure-Requests: 1',
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
                'sec-ch-ua: "Chromium";v="104", " Not A;Brand";v="99", "Google Chrome";v="104"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Windows"',
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }
}
