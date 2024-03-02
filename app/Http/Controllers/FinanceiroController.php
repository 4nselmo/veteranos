<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Jogador;
use App\Models\JogadorGol;
use App\Models\Temporadas;
use App\Models\Equipe;
use App\Models\TipoMovimentacao;
use App\Models\Contribuicao;
use App\Models\FinanceiroLancamento;
use Barryvdh\DomPDF\Facade\Pdf;

class FinanceiroController extends Controller
{

    public function index()
    {
        return view('financeiro');
    }

    public function getDados(Request $request) {
        $obj = (object)[];
        $obj->contribuintes = $this->getContribites($request);
        $obj->tipoMovimentacoes = $this->getTiposMovimentacao($request);
        $obj->contribuicoes = $this->getContribuicoes($request);
        $obj->financeiroLancamentos = $this->getFinanceiroLancamentos($request);
        $obj->valor_total_entradas = $this->getValorTotalEntradas();
        $obj->valor_total_saidas = $this->getValorTotalSaidas();
        return $obj;
    }

    public function getContribites(Request $request)
    {
        $contribuintes = Jogador::orderBy('nome')->get();
        
        return $contribuintes;
    }

    public function getTiposMovimentacao(Request $request)
    {
        $tipoMovimentacoes = TipoMovimentacao::get();
        
        return $tipoMovimentacoes;
    }

    public function getContribuicoes(Request $request)
    {
        $contribuicao = Contribuicao::get();
        
        return $contribuicao;
    }

    public function storeUpdateContribuicao(Request $request)
    {
        if($request->id)
            $contribuicao = Contribuicao::find($request->id);
        else
            $contribuicao = new Contribuicao;
        
        $contribuicao->nome = $request->nome;
        $request->id?$contribuicao->update():$contribuicao->save();
        return $this->getContribuicoes($request);
    }
    public function storeUpdateFinanceiroLanacamento(Request $request)
    {
        if($request->id)
            $lancamento = FinanceiroLancamento::find($request->id);
        else
            $lancamento = new FinanceiroLancamento;

        $lancamento->jogador_id = $request->contribuinte;
        $lancamento->contribuicao_id = $request->contribuicao;
        $lancamento->tipo_movimentacao_id = $request->tipoMovimentacao;
        $lancamento->valor = $request->valor;
        $lancamento->data_movimentacao = $request->data;
        $lancamento->descricao = $request->descricao;
        $request->id?$lancamento->update():$lancamento->save();
        return $this->getFinanceiroLancamentos($request);
    }

    public function getFinanceiroLancamentos()
    {
        $lancamentos = FinanceiroLancamento::get();
        foreach ($lancamentos as $lancamento) {
            $lancamento->contribuinte = $this->getContribuinte($lancamento->jogador_id);
            $lancamento->contribuicao = $this->getContribuicao($lancamento->contribuicao_id);
            $lancamento->tipoMovimentacao = $this->getTipoMovimentacao($lancamento->tipo_movimentacao_id);
            $lancamento->valor_formatado = number_format($lancamento->valor, 2, ',', '.');
            $lancamento->data_movimentacao_formatada = date('d/m/Y',strtotime($lancamento->data_movimentacao));
        }
        return $lancamentos;
    }

    public function getContribuinte($contribuinte_id)
    {
        $contribuinte = Jogador::find($contribuinte_id);
        return $contribuinte->nome;
    }

    public function getContribuicao($contribuicao_id)
    {
        $contribuicao = Contribuicao::find($contribuicao_id);
        return $contribuicao->nome;
    }

    public function getTipoMovimentacao($tipo_movimentacao_id)
    {
        $tipoMovimentacao = TipoMovimentacao::find($tipo_movimentacao_id);
        return $tipoMovimentacao->nome;
    }

    public function gerarPdf()
    {
        $lancamentos = $this->getFinanceiroLancamentos();
        $entradas = $this->getValorTotalEntradas();
        $saidas = $this->getValorTotalSaidas();
        $saldo = $this->getSaldo();

        // $pdf = PDF::loadView('financeiroPdf', compact('lancamentos'));
        $html = view('financeiroPdf',
		[
			'lancamentos' => $lancamentos,
            'entradas' => number_format($entradas, 2, ',', '.'),
            'saidas' => number_format($saidas, 2, ',', '.'),
            'saldo' => number_format($saldo, 2, ',', '.'),
		])->render();
        return PDF::loadHTML($html)->setPaper('a4','landscape')->stream('Financeiro');
        // return $pdf->setPaper('a4')->stream('Financeiro');
    }

    public function getValorTotalEntradas()
    {
        $valor_total_entradas = FinanceiroLancamento::where('tipo_movimentacao_id', 1)->sum('valor');
        return $valor_total_entradas;
    }

    public function getValorTotalSaidas()
    {
        $valor_total_saidas = FinanceiroLancamento::where('tipo_movimentacao_id', 2)->sum('valor');
        return $valor_total_saidas;
    }
    public function getSaldo()
    {
        $saldo = $this->getValorTotalEntradas()-$this->getValorTotalSaidas();
        return $saldo;
    }
}