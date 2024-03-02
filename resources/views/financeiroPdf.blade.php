<p class="titulo">Veteranos Financeiro</p>
<div class="row">
    <table class="tabela" width="30%">
        <tr class="total-tr">
            <td>Entradas</td>
            <td>Saídas</td>
            <td>Saldo</td>
        </tr>
        <tr class="total-valor">
            <td>R$ {{$entradas}}</td>
            <td>R$ {{$saidas}}</td>
            @if($saldo >= 0)
                <td class="saldo_positivo">R$ {{$saldo}}</td>
            @else
                <td class="saldo_negativo">-R$ {{$saldo}}</td>
            @endif

        </tr>
    </table>
</div>
<br>
<div class="row"> 
    <div class="col col-lg-12">
        <table class="tabela" width="100%">
            <tr class="cabecalho-header">
                <td>Id</td>
                <td>Nome</td>
                <td>Movimentação</td>
                <td>Tipo</td>
                <td>Valor</td>
                <td>Data</td>
                <!-- <td width="20%">Descrição</td> -->
            </tr>
                
            <?php $i = 0; ?>
            @foreach($lancamentos as $lancamento)
                <tr class="lancamentos-body {{ ($i % 2 == 0) ? 'quase-branco' : 'menos-branco' }}">
                    <td>{{$lancamento->id}}</td>
                    <td>{{$lancamento->contribuinte}}</td>
                    <td>{{$lancamento->contribuicao}}</td>
                    <td>{{$lancamento->tipoMovimentacao}}</td>
                    @if($lancamento->tipo_movimentacao_id == 1)
                        <td class="entrada">R$ {{$lancamento->valor_formatado}}</td>
                    @else
                        <td class="saida">-R$ {{$lancamento->valor_formatado}}</td>
                    @endif
                    <td>{{$lancamento->data_movimentacao_formatada}}</td>
                    <!-- <td>{{$lancamento->descricao}}</td> -->
                </tr>
                <?php $i++; ?>
            @endforeach

        </table>
        </div>
    </div>

<style scoped>
    .tabela{
        font-size: 11px;
    }
    .quase-branco {
	background: #fefefe !important;
    }
    .menos-branco {
        background: #ddd !important;
    }

    .cabecalho-header{
        font-size: 12px;
        font-weight: bold;
        background: #636363;
        color: #fff;
    }

    .lancamentos-body {
        font-size: 12px;
        background: #bbb;
    }

    .entrada{
      color: green;
    }

    .saida{
      color: red;
    }

    .saldo_positivo{
        color: green;
        font-weight: bold;
    }
    .saldo_negativo{
        color: red;
        font-weight: bold;
    }

    .total-tr{
        font-weight: bold;
        background: rgb(107, 107, 107);
        color: #fff;
        font-size: 15px;
    }

    .total-valor{
        font-weight: bold;
        background: rgb(235, 235, 235);
        color: #333;
        font-size: 15px;
    }

    .titulo{
        text-align: center;
        font-size:20px;
        font-weight: bold;
    }

</style>