<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Jogador;
use App\Models\JogadorGol;
use App\Models\Temporadas;
use App\Models\Equipe;
use Barryvdh\DomPDF\Facade\Pdf;

class JogosController extends Controller
{
    public function index()
    {
        return view('jogos');
    }

    public function getDados(Request $request) {
        $obj = (object)[];
        $obj->temporadas = $this->getTemporadas();
        $obj->estatisticasJogos = $this->getEstatisticasJogos($request);
        return $obj;
    }

    public function getTemporadas() {
        $temporadas = Temporadas::select('id', 'nome')->get();
        return $temporadas;
    }

    public function getEstatisticasJogos(Request $request)
    {
        $datas_jogos = JogadorGol::select('data');
        if($request->temporada_id)
            $datas_jogos = $datas_jogos->where('temporada_id', $request->temporada_id);

        $datas_jogos = $datas_jogos->groupBy('data')->orderBy('data', 'desc')->get();

        foreach ($datas_jogos as $data) {
            $data->golsTimePreto = (int)$this->getGolsTimePorDia($data->data, 4);
            $data->golsTimeAzul = (int)$this->getGolsTimePorDia($data->data, 3);
            $data->data_formatada = date('d/m/Y',strtotime($data->data));
        }
        return $datas_jogos;
    }

    public function getGolsTimePorDia($data, $equipe_id)
    {
        $gols = JogadorGol::select('gols')->where('data', $data)->where('equipe_id', $equipe_id)->groupBy('data')->sum('gols');
        $quauntidadeGolsContraMarcados = $this->getGolsContra($data, $equipe_id);
        if($quauntidadeGolsContraMarcados)
            return $gols+$quauntidadeGolsContraMarcados;
        
        return $gols;
    }

    public function getDetalhes(Request $request)
    {
        $detalhes = JogadorGol::select('j.nome', 'jogador_gols.gols', 'jogador_gols.gols_sofridos', 'jogador_gols.gol_contra', 'e.nome  as equipe', 'jogador_gols.data')
        ->join('jogadores As j', 'j.id', '=', 'jogador_gols.jogador_id')
        ->join('equipes As e', 'e.id', '=', 'jogador_gols.equipe_id')
        ->where('jogador_gols.data', $request->data)
        ->get();
        foreach ($detalhes as $detalhe) {
            $detalhe->data_formatada = date('d/m/Y',strtotime($detalhe->data));
        }
        return $detalhes;

    }


    public function getGolsContra($data, $equipe_id)
    {
        $quauntidadeGolsContraMarcados = JogadorGol::select('gol_contra')
        ->where('data', $data)
        ->where('equipe_id', $equipe_id)
        ->sum('gol_contra');
        return $quauntidadeGolsContraMarcados;
    }
}