<?php

namespace App\Models;

use Goutte\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpClient\HttpClient;

class Concurso extends Model
{
    use HasFactory;

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
