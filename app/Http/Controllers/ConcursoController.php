<?php

namespace App\Http\Controllers;

use App\Models\Concurso;
use App\Models\DetalleConcurso;
use Illuminate\Http\Request;

class ConcursoController extends Controller
{
    public function index()
    {
        $concursos = Concurso::latest()->get();
        return view('concursos.index', compact('concursos'));
    }

    public function data()
    {
        $url = 'http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml';
        $model = new Concurso;
        $data = $model->getCookies($url);
        $cookie = $data['cookies'];
        $view_state = $data['view_state'];

        // dd($view_state);
        // dd(urlencode($view_state));

        $image_captcha = $model->getCaptchaCode($cookie)[0];
        return view('concursos.scrapping', compact('image_captcha', 'cookie', 'view_state'));
    }

    public function concurso()
    {
        return redirect()->route('concurso');
    }

    public function saveData(Request $request)
    {
        $url = 'http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml';
        $model = new Concurso;
        $model->saveData($url, $request->cookie, $request->view_state, $request->captcha, $request->fecha_inicio_publicacion, $request->fecha_final_publicacion);
    }

    public function show($convocatoria)
    {
        $detalle_concurso = DetalleConcurso::where('convocatoria', $convocatoria)
            ->whereIn('tipo_id', [1, 2, 3, 4, 5])
            ->get();
        $documentos = DetalleConcurso::where('convocatoria', $convocatoria)
            ->whereIn('tipo_id', [6])
            ->get();
        $botoneria_imagenes = DetalleConcurso::where('convocatoria', $convocatoria)
            ->whereIn('tipo_id', [7])
            ->where('key', '')
            ->get();
        $botoneria_enlaces = DetalleConcurso::where('convocatoria', $convocatoria)
            ->whereIn('tipo_id', [7])
            ->where('key', '<>', '')
            ->get();

        foreach ($documentos as $key => $documento) {
            $documentos[$key]->value = json_decode($documentos[$key]->value);
        }
        return view('concursos.show', compact('detalle_concurso', 'documentos', 'convocatoria', 'botoneria_imagenes', 'botoneria_enlaces'));
    }

    public function botoneria($convocatoria, $btn_url)
    {
        $find_tb_ficha = "tbFicha:j_idt";
        $pos_tb_ficha = strpos($btn_url, $find_tb_ficha) + strlen($find_tb_ficha);
        $tb_ficha = $find_tb_ficha . substr($btn_url, $pos_tb_ficha, 4);
        $url = 'http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml';
        $model = new Concurso;
        $cookie = $model->getCookies($url);
        $image_captcha = $model->getCaptchaCode($cookie)[0];
        return view('concursos.botoneria', compact('image_captcha', 'cookie', 'convocatoria', 'tb_ficha'));
    }

    public function printPant(Request $request)
    {
        $url = 'http://procesos.seace.gob.pe/seacebus-uiwd-pub/buscadorPublico/buscadorPublico.xhtml';
        $model = new Concurso;
        $model->AllRequest($url, $request->cookie, $request->captcha, $request->convocatoria, $request->tb_ficha);
    }
}
