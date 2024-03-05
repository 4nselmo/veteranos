<p class="titulo">Lista de Gols</p>
<div class="row"> 
    <div class="col col-lg-12">
        <table class="tabela" width="100%">
            <tr class="cabecalho-header">
                <td width="1%">#</td>
                <td>Nome</td>
                <td>Gols Marcados</td>
                <td>Gols Sofridos</td>
                <td>Gols Contra</td>
            </tr>
                
            <?php $i = 0; ?>
            @foreach($listaGols as $key => $lista)
                <tr class="lancamentos-body {{ ($i % 2 == 0) ? 'quase-branco' : 'menos-branco' }}">
                    <td>{{$key+1}}</td>
                    <td>{{$lista->nome}}</td>
                    @if($lista->gols)
                        <td>{{$lista->gols}}</td>
                    @else
                        <td>-</td>
                    @endif
                    @if($lista->gols_sofridos)
                        <td>{{$lista->gols_sofridos}}</td>
                    @else
                        <td>-</td>
                    @endif
                    @if($lista->gol_contra)
                        <td>{{$lista->gol_contra}}</td>
                    @else
                        <td>-</td>
                    @endif
                </tr>
                <?php $i++; ?>
            @endforeach

        </table>
        </div>
    </div>
    <br>
    <p class="titulo">Estatísticas</p>
    <div class="row">
    <table class="tabela" width="100%">
        <tr class="cabecalho-header">
            <td>Times</td>
            <td>Vitórias</td>
            <td>Derrotas</td>
            <td>Empates</td>
            <td>Gols Pro</td>
        </tr>
        <tr class="quase-branco equipes">
            <td colspan="3"></td>
            <td style="text-align: right">Total:</td>
            <td>{{$estatisticas->golsTotal}}</td>
        </tr>
        <tr class="menos-branco">
            <td class="equipes">Time Preto</td>
            <td>{{$estatisticas->vitoriasTimePreto}}</td>
            <td>{{$estatisticas->derrotasTimePreto}}</td>
            <td>{{$estatisticas->empates}}</td>
            <td>{{$estatisticas->golsTimePreto}}</td>
        </tr>
        <tr class="quase-branco">
            <td class="equipes">Time Azul</td>
            <td>{{$estatisticas->vitoriasTimeAzul}}</td>
            <td>{{$estatisticas->derrotasTimeAzul}}</td>
            <td>{{$estatisticas->empates}}</td>
            <td>{{$estatisticas->golsTimeAzul}}</td>
        </tr>
    </table>
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
    .equipes{
        font-weight: bold;
    }

</style>