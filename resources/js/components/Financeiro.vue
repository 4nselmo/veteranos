<script>
    export default {
      
      data() {
          return {
          contribuintes:[],
          contribuinte:'',
          tipoMovimentacoes:[],
          tipoMovimentacao:'',
          mensagemAlerta: '',
          mensagemAlertaSucesso:'',
          contribuicoes:[],
          contribuicao:null,
          financeiroLancamentos:[],
          financeiroLancamento:'',
          disable_store:false

          }
      },


      methods: {

        openModalFinanceiroLancamento(lancamento = '')
        {
          const myModal = new bootstrap.Modal(document.getElementById('novoLancamento'));
          myModal.show();
          $('#contribuinte').val('');
          $('#contribuicao').val('');
          $('#tipoMovimentacao').val('');
          document.getElementById('valor').value = '';
          document.getElementById('data').value = '';
          document.getElementById('descricao').value = '';
          $('#valor').maskMoney();
          if(lancamento)
          {
            this.financeiroLancamento = lancamento;
            document.getElementById('contribuinte').value = lancamento.jogador_id;
            document.getElementById('contribuicao').value = lancamento.contribuicao_id;
            document.getElementById('tipoMovimentacao').value = lancamento.tipo_movimentacao_id;
            document.getElementById('valor').value = lancamento.valor_formatado;
            document.getElementById('data').value = lancamento.data_movimentacao;
            document.getElementById('descricao').value = lancamento.descricao;
          }

          $('#novoLancamento').on('hidden.bs.modal', function(e){
            this.financeiroLancamento = '';
            $('#contribuinte').val('');
            $('#contribuicao').val('');
            $('#tipoMovimentacao').val('');
            document.getElementById('valor').value = '';
            document.getElementById('data').value = '';
            document.getElementById('descricao').value = '';
          });

        },

        openModalContribuicao()
        {
          const myModal = new bootstrap.Modal(document.getElementById('modalContribuicao'));
          myModal.show();

          this.getContribuicoes();

          $('#modalContribuicao').on('hidden.bs.modal', function(e){
            this.nome = '';
            document.getElementById('nome').value = '';
          });

        },

        getDados()
        {
          jQuery.get('getDadosFinanceiro', res => {
              this.contribuintes = res.contribuintes;
              this.tipoMovimentacoes = res.tipoMovimentacoes;
              this.contribuicoes = res.contribuicoes;
              this.financeiroLancamentos = res.financeiroLancamentos;
          })
        },

        storeUpdateContribuicao(){

          if(!document.getElementById('nome').value)
          {
            this.mensagemAlerta = 'O campo Nome deve ser preenchido!';
            $('#toastAlert').show();
            setTimeout(function(){
              $('#toastAlert').hide();
              this.mensagemAlerta = '';
            }, 5000); 
          }
          else
          {
            this.disable_store = true;
            const data={
              _token: $('meta[name="csrf-token"]').attr('content'),
              nome: document.getElementById('nome').value,
              id: this.contribuicao?this.contribuicao.id:''
            }

            jQuery.post('storeUpdateContribuicao',  data, res  => {
              this.contribuicoes = res;
              this.mensagemAlertaSucesso = 'Salvo com sucesso!';
                $('#toastAlertSuccess').show();
                document.getElementById('nome').value = '';
                this.disable_store = false;
                setTimeout(function(){
                  $('#toastAlertSuccess').hide();
                  this.mensagemAlertaSucesso = '';
                  this.contribuicao = null;
                }, 5000); 
            });
          }
        },

        storeUpdateFinanceiroLanacamento(){

            this.disable_store = true;
            var data = this.validator();
            if (data == false)
              return false;

            jQuery.post('storeUpdateFinanceiroLanacamento',  data, res  => {
              this.financeiroLancamentos = res;
              this.mensagemAlertaSucesso = 'Salvo com sucesso!';
                $('#toastAlertSuccess').show();

                $('#contribuinte').val('');
                $('#contribuicao').val('');
                $('#tipoMovimentacao').val('');
                document.getElementById('valor').value = '';
                document.getElementById('data').value = '';
                document.getElementById('descricao').value = '';

                this.disable_store = false;
                $('#novoLancamento').modal('hide');
                setTimeout(function(){
                  $('#toastAlertSuccess').hide();
                  this.mensagemAlertaSucesso = '';
                  this.contribuicao = null;
                }, 5000); 
            });
          // }
        },

        getContribuicoes()
        {
          const data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            // temporada_id: $('#temporada_estatistica_id').val()
          }
          jQuery.get('getContribuicoes', data, res => {
            this.contribuicoes = res;
          });
        },

        editContribuicao(contribuicao)
        {
          this.contribuicao = contribuicao;
          document.getElementById('nome').value = contribuicao.nome;
        },
        
        validator()
        {
          if(!$('#contribuinte').val())
          {
            this.mensagemAlerta = 'O campo Contribuinte deve ser selecionado!';
            $('#toastAlert').show();
            setTimeout(function(){
              $('#toastAlert').hide();
              this.mensagemAlerta = '';
            }, 5000);  
            this.disable_store = false;
            return false;
          }

          if(!$('#contribuicao').val())
          {
            this.mensagemAlerta = 'O campo Contribuição deve ser selecionado!';
            $('#toastAlert').show();
            setTimeout(function(){
              $('#toastAlert').hide();
              this.mensagemAlerta = '';
            }, 5000);  
            this.disable_store = false;
            return false;
          }

          if(!$('#tipoMovimentacao').val())
          {
            this.mensagemAlerta = 'O campo Movimentação deve ser selecionado!';
            $('#toastAlert').show();
            setTimeout(function(){
              $('#toastAlert').hide();
              this.mensagemAlerta = '';
            }, 5000);  
            this.disable_store = false;
            return false;
          }

          if(!document.getElementById('valor').value)
          {
            this.mensagemAlerta = 'O campo Valor deve ser preenchido!';
            $('#toastAlert').show();
            setTimeout(function(){
              $('#toastAlert').hide();
              this.mensagemAlerta = '';
            }, 5000);  
            this.disable_store = false;
            return false;
          }

          if(!document.getElementById('data').value)
          {
            this.mensagemAlerta = 'O campo Data deve ser preenchido!';
            $('#toastAlert').show();
            setTimeout(function(){
              $('#toastAlert').hide();
              this.mensagemAlerta = '';
            }, 5000);  
            this.disable_store = false;
            return false;
          }

          const data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: this.financeiroLancamento?this.financeiroLancamento.id:'',
            contribuinte: document.getElementById('contribuinte').value,
            contribuicao: document.getElementById('contribuicao').value,
            tipoMovimentacao: document.getElementById('tipoMovimentacao').value,
            valor: jQuery("#valor").maskMoney('unmasked')[0],
            data: document.getElementById('data').value,
            descricao: document.getElementById('descricao').value,
          };

          return data;
        },

        getFinanceiroLancamentos()
        {
          const data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
          }
          jQuery.get('getFinanceiroLancamentos', data, res => {
            this.financeiroLancamentos = res;
          });
        },

        formartValores(){
            this.$nextTick(()=>{
                jQuery('.formatValor').maskMoney({
                    allowNegative: false,
                    thousands: '.',
                    decimal: ',',
                    affixesStay: false
                });        
                      
            });
            
        },

        maskMoney: function (value, hasPrefix, casas = 2) {
          if (isNaN(value))
              value = 0;

          /*if (!value)
              value = 0;*/

          value = value.toLocaleString('de-DE', {
              style: 'currency',
              currency: 'BRT',
              minimumFractionDigits: casas
          });
          value = value.replace('BRT', '');

          if (hasPrefix)
              value = "R$ " + value;

          return value
       },

       financeiroPdf(){
            window.open('financeiroPdf'); 
        },

      },

      watch:
      {

      },

      filters:{

      },

      mounted() {
        this.getDados();
      }
  }
</script>
<template class="financeiro">
  <div class="row" style="padding-top: 10px">
      <div class="col-lg-3">
          <h3>Veteranos Financeiro</h3>
      </div>
      <div class="col-lg-9" style="text-align: right">
          <span class="botoes">
            <button  type = "button" class = "btn btn-primary" id="myInput" v-on:click="openModalFinanceiroLancamento()">
            NOVO LANÇAMENTO
            </button>
          </span>
          <span class="botoes">
            <button  type = "button" class = "btn btn-primary" id="myInput" v-on:click="openModalContribuicao()">
            NOVA CONTRIBUIÇÃO
            </button>
          </span>
          <span class="botoes">
            <i class="bi bi-filetype-pdf" title="Exportar PDF" v-on:click="financeiroPdf()"></i>
          </span>
      </div>
  </div>
  <br>

  <div class="row"> 
    <div class="col col-lg-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="10%">Id</th>
                    <th width="20%">Nome</th>
                    <th width="25%">Descrição</th>
                    <th>Movimentação</th>
                    <th width="10%">Valor</th>
                    <th>Data</th>
                    <!-- <th>Descrição</th> -->
                    <th width="6%">Ações</th>
                </tr>
            </thead>
            <tbody>
                
                <tr v-for="(lancamento, index) in financeiroLancamentos" :key="lancamento.id">
                    <td>{{lancamento.id}}</td>
                    <td>{{lancamento.contribuinte}}</td>
                    <td>{{lancamento.contribuicao}}</td>
                    <td>{{lancamento.tipoMovimentacao}}</td>
                    <td v-if="lancamento.tipo_movimentacao_id == 1" class="entrada">R$ {{lancamento.valor_formatado}}</td>
                    <td v-else class="saida">-R$ {{lancamento.valor_formatado}}</td>
                    <td>{{lancamento.data_movimentacao_formatada}}</td>
                    <!-- <td>{{lancamento.descricao}}</td> -->
                    <td>
                      <a style="cursor: pointer;" v-on:click="openModalFinanceiroLancamento(lancamento)"><i class="bi bi-pencil-fill" title="Editar"></i></a>
                      <a>
                        <i v-if="lancamento.participacoes" class="bi bi-trash3-fill jogador_excluir_not" title="Excluir"></i>
                        <i v-else v-on:click="deleteConfirmacao(lancamento.id)" class="bi bi-trash3-fill jogador_excluir"  title="Excluir"></i>
                      </a>
                    </td>
                </tr>
                
            </tbody>
        </table>
        </div>
    </div>

    <div class="modal fade" id="novoLancamento" tabindex="-1" aria-labelledby="novoLancamentoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-xl" >
        <div class="modal-content novoLancamento" style="height: 60%;">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="novoLancamentoModalLabel">Novo Lançamento</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col col-lg-4">
                <label class="col-form-label">Contribuinte</label>
                <select class="form-select" id="contribuinte" >
                  <option value="" selected>Selecione o contribuinte</option>
                  <option v-for="contribuinte in contribuintes" :value="contribuinte.id" :key="contribuinte.id">{{contribuinte.nome}}</option>
                </select>
              </div>

              <div class="col col-lg-4">
                <label class="col-form-label">Contribuição</label>
                <select class="form-select" id="contribuicao">
                  <option value="" selected>Selecione a contribuição</option>
                  <option v-for="contribuicao in contribuicoes" :value="contribuicao.id" :key="contribuicao.id">{{contribuicao.nome}}</option>
                </select>
              </div>

              <div class="col col-lg-4">
                <label class="col-form-label">Movimentação</label>
                <select class="form-select" id="tipoMovimentacao">
                  <option value="" selected>Selecione a movimentação</option>
                  <option v-for="tipoMovimentacao in tipoMovimentacoes" :value="tipoMovimentacao.id" :key="tipoMovimentacao.id">{{tipoMovimentacao.nome}}</option>
                </select>
              </div>
            </div>
            
            <br>

            <div class="row">

              <div class="col col-lg-4">
              <label for="valor" class="col-form-label">Valor</label>
              <input type="text" name="valor" class="form-control formatValor" id="valor"/>
              </div>

              <div class="col col-lg-4">
                <label for="data" class="col-form-label">Data</label>
                <input type="date" name="data" class="form-control" id="data"/>
              </div>

              <div class="col col-lg-4">
                <label for="descricao" class="col-form-label">Descrição</label>
                <input type="text" name="descricao" class="form-control" id="descricao"/>
              </div>

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-primary" :disabled="disable_store" v-on:click="storeUpdateFinanceiroLanacamento()">Salvar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalContribuicao" tabindex="-1" aria-labelledby="modalContribuicaoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-xl" >
        <div class="modal-content modalContribuicao" style="height: 80%;">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="modalContribuicaoModalLabel">Nova Contribuição</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">

              <div class="col col-lg-11">
                <label for="nome" class="col-form-label">Nome</label>
                <input type="text" name="nome" class="form-control" id="nome"/>
              </div>
              <div class="col col-lg-1 bt-save">
                <i v-if="!disable_store" class="bi bi-save save_enabled" v-on:click="storeUpdateContribuicao()"></i>
                <i v-else class="bi bi-save save_disabled"></i>
              </div>

            </div>
            <br>
            <div class="row">
              <div class="col col-lg-12">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th width="5%">ID</th>
                    <th width="35%">Nome</th>
                    <th width="5%">Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="contribuicao in contribuicoes">
                    <td>{{contribuicao.id}}</td>
                    <td>{{contribuicao.nome}}</td>
                    <th>
                      <a style="cursor: pointer;" v-on:click="editContribuicao(contribuicao)"><i class="bi bi-pencil-fill" title="Editar"></i></a>
                      <a style="cursor: pointer;" v-on:click="modalConfirmDeleteGols(gols.id)"><i class="bi bi-trash3-fill" title="Excluir"></i></a>
                    </th>
                  </tr>
                </tbody>
              </table>
              </div>

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
      <div class="toast align-items-center text-bg-primary border-0" id="toastAlert" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex" style="background-color: #e57373;">
          <div class="toast-body">
            {{mensagemAlerta}}
          </div>
        </div>
      </div>
      <div class="toast align-items-center text-bg-primary border-0" id="toastAlertSuccess" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body">
            {{mensagemAlertaSucesso}}
          </div>
        </div>
      </div>
  </div>
</template>
<style scoped>
    .botoes{
        padding: 3px;
    }

    .bi-pencil-fill{
      color: #0d6efd;
      padding: 1.7px;
    }

    .bi-trash3-fill{
      padding: 1.7px;
      color: red;
    }

    .bi-person-fill-add{
      padding: 1.7;
      color: green;
    }

    .golsModal{
      height: 1000px;
    }
    .jogador_excluir_not{
      color: #ccc;
    }
    .jogador_excluir{
      cursor: pointer;
    }

    .bt-save{
      font-size: 20px;
      margin-top: 45px;

    }

    .save_enabled
    {
      color: #0d6efd;
      cursor: pointer;
    }
    .save_disabled
    {
      color: #ccc;
      cursor: default;
    }


    .entrada{
      color: green;
    }

    .saida{
      color: red;
    }

    .bi-filetype-pdf{
      color: red;
      font-size: 25px;
      /* margin-top: 200px; */
      cursor: pointer;
    }
</style>