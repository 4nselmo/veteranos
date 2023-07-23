<script>
    export default {
      
      data() {
          return {
          jogadores: [],
          temporada:'',
          jogador:'',
          jogador_nome:'',
          jogadorId:'',
          equipes:[],
          jogadorGols:[],
          estatisticas:[],
          temporada_estatistica_id:'',
          mensagemAlerta: '',
          mensagemAlertaSucesso:'',
          jogadorGolId:'',
          disable_store:false

          }
      },


      methods: {
        openModalJogador(jogador = '')
        {
          const myModal = new bootstrap.Modal(document.getElementById('jogadorModal'));
          myModal.show();

          // if(jogador)
          // {
          //   document.getElementById('nome').value = jogador.nome;
          // }

          $('#jogadorModal').on('hidden.bs.modal', function(e){
            this.jogador = '';
            document.getElementById('nome').value = '';
          });

          this.jogador = jogador;
          if(this.jogador)
          {
            document.getElementById('nome').value = this.jogador.nome;
          }

        },

        openModalEstatisticas()
        {
            const myModal = new bootstrap.Modal(document.getElementById('estatisticas'));
            myModal.show();

            this.getEstatisticas();
            $('#estatisticas').on('shown.bs.modal', function (e) {
              $('#temporada_estatistica_id').val('');
            });

            $('#estatisticas').on('hidden.bs.modal', function(e){
              $('#temporada_estatistica_id').val('');
            });
        },

        getEstatisticas()
        {
          const data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            temporada_id: $('#temporada_estatistica_id').val()
          }
          jQuery.get('getEstatisticas', data, res => {
            this.estatisticas = res;
          });
        },

        getJogadores()
        {
          const data = {
              _token: $('meta[name="csrf-token"]').attr('content'),
              temporada_id: this.temporada
          }

          jQuery.get('getJogadores', data, res => {
              this.jogadores = res;
          })

        },

        getDados()
        {

          jQuery.get('getDados', res => {
              this.jogadores = res.jogadores;
              this.temporadas = res.temporadas;
              this.equipes = res.equipes;
          })
        },

        salvarJogador(){

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
            const data={
              _token: $('meta[name="csrf-token"]').attr('content'),
              nome: document.getElementById('nome').value,
              temporada_id: this.temporada
            }

            jQuery.post('salvarJogador',  data, res  => {
              this.jogadores = res;
              this.mensagemAlertaSucesso = 'Salvo com sucesso!';
                $('#toastAlertSuccess').show();
                setTimeout(function(){
                  $('#toastAlertSuccess').hide();
                  this.mensagemAlertaSucesso = '';
                }, 5000); 
            });
          }
        },

        atualizarJogador()
        {          
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
            const data = {
              _token: $('meta[name="csrf-token"]').attr('content'),
              id:this.jogador.id,
              nome: document.getElementById('nome').value,
              temporada_id: this.temporada
            }
            
            jQuery.post('atualizarJogador', data, res  => {
              this.jogadores = res;
              jQuery('#jogadorModal').modal('hide');
              this.mensagemAlertaSucesso = 'Atualizado com sucesso!';
                $('#toastAlertSuccess').show();
                setTimeout(function(){
                  $('#toastAlertSuccess').hide();
                  this.mensagemAlertaSucesso = '';
              }, 1000); 
            });
          }
        },

        openModalGols(jogadorId)
        {
          this.jogadorId = jogadorId;
          this.getGolsJogador(jogadorId);
          const myModal = new bootstrap.Modal(document.getElementById('golsModal'));
          myModal.show();
          $('#golsModal').on('shown.bs.modal', function (e) {
            $('#temporada_gols_id').val('');
          });

          $('#golsModal').on('hidden.bs.modal', function(e){
            this.jogadorId = '';
            document.getElementById('gols').value = '';
            document.getElementById('equipe').value = '';
            $('#temporada_gols_id').val('');
            document.getElementById('data').value = '';
          });
        },

        getGolsJogador(jogadorId)
        {
          const data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            jogadorId: jogadorId,
            temporada_id: this.temporada
          };
          jQuery.get('getGolsJogador', data, res => {
            this.jogadorGols = res;
          });
        },

        editGols(jogadorGols)
        {
          this.jogadorGolId = jogadorGols.id;
          document.getElementById('gols').value = jogadorGols.gols;
          document.getElementById('golsSofridos').value = jogadorGols.gols_sofridos;
          document.getElementById('equipe').value = jogadorGols.equipe_id;
          document.getElementById('data').value = jogadorGols.data;
          document.getElementById('temporada_gols_id').value = jogadorGols.temporada_id;
        },

        deleteGols(){
          const data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            jogadorGolId: this.jogadorGolId,
            jogadorId: this.jogadorId,
            temporada_id: this.temporada
          }
          jQuery.post('deleteGols', data, res =>{
            this.jogadores = res.jogadores;
            this.jogadorGols = res.jogador_gols;
            this.mensagemAlertaSucesso = 'Deletado com sucesso!';
            $('#toastAlertSuccess').show();
            setTimeout(function(){
              $('#toastAlertSuccess').hide();
              this.mensagemAlertaSucesso = '';
            }, 5000);
            $('#modalConfirmDeleteGols').modal('hide');
          })
        },

        storeGol()
        {
          this.disable_store = true;
          var data = this.validator();
          if (data == false)
            return false;

          jQuery.post('storeGol', data, res => {
            this.jogadores = res.jogadores;
            this.jogadorGols = res.jogador_gols;
            document.getElementById('gols').value = '';
            document.getElementById('equipe').value = '';
            document.getElementById('data').value = '';
            document.getElementById('golsSofridos').value = '';
            document.getElementById('temporada_gols_id').value = '';
            this.disable_store = false;
            this.mensagemAlertaSucesso = 'Salvo com sucesso!';
            $('#toastAlertSuccess').show();
            setTimeout(function(){
              $('#toastAlertSuccess').hide();
              this.mensagemAlertaSucesso = '';
            }, 5000); 
          });
        },

        updateGol()
        {
          this.disable_store = true;
          var data = this.validator();
          if (data == false)
            return false;

          jQuery.post('updateGol', data, res => {
            this.jogadores = res.jogadores;
            this.jogadorGols = res.jogador_gols;
            document.getElementById('gols').value = '';
            document.getElementById('equipe').value = '';
            document.getElementById('data').value = '';
            document.getElementById('golsSofridos').value = '';
            document.getElementById('temporada_gols_id').value = '';
            this.jogadorGolId = '';
            this.disable_store = false;
            this.mensagemAlertaSucesso = 'Atualizado com sucesso!';
            $('#toastAlertSuccess').show();
            setTimeout(function(){
              $('#toastAlertSuccess').hide();
              this.mensagemAlertaSucesso = '';
            }, 5000);  
          });
        },

        validator()
        {
          if(!$('#temporada_gols_id').val())
          {
            this.mensagemAlerta = 'O campo Temporada deve ser selecionado!';
            $('#toastAlert').show();
            setTimeout(function(){
              $('#toastAlert').hide();
              this.mensagemAlerta = '';
            }, 5000);  
            this.disable_store = false;
            return false;
          }

          if(!document.getElementById('gols').value && !document.getElementById('golsSofridos').value)
          {
            this.mensagemAlerta = 'O campo Gols Marcados ou Gols Sofridos deve ser preenchido!';
            $('#toastAlert').show();
            setTimeout(function(){
              $('#toastAlert').hide();
              this.mensagemAlerta = '';
            }, 5000);  
            this.disable_store = false;
            return false;
          }

          if(!document.getElementById('equipe').value)
          {
            this.mensagemAlerta = 'O campo Time deve ser selecionado!';
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
            jogadorId: this.jogadorId,
            gols: document.getElementById('gols').value,
            data: document.getElementById('data').value,
            id: this.jogadorGolId,
            equipe_id: document.getElementById('equipe').value,
            gols_sofridos: document.getElementById('golsSofridos').value,
            temporada_id: document.getElementById('temporada_gols_id').value
          };

          return data;
        },

        deleteJogador()
        {
          const data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            jogadorId: this.jogadorId,
            temporada_id: this.temporada
          }
          jQuery.post('deleteJogador', data, res =>{
            this.jogadores = res;
            this.mensagemAlertaSucesso = 'Deletado com sucesso!';
            $('#toastAlertSuccess').show();
            setTimeout(function(){
              $('#toastAlertSuccess').hide();
              this.mensagemAlertaSucesso = '';
            }, 5000);
            this.jogadorId = '';
            $('#modalConfirm').modal('hide');
          })
        },

        deleteConfirmacao(jogadorId)
        {
          this.jogadorId = jogadorId
          $('#modalConfirm').modal('show');
          
          $('#modalConfirm').on('hidden.bs.modal', function(e){
            this.jogadorId = '';
          });
        },

        modalConfirmDeleteGols(jogadorGolId)
        {
          this.jogadorGolId = jogadorGolId
          $('#modalConfirmDeleteGols').modal('show');
          
          $('#modalConfirmDeleteGols').on('hidden.bs.modal', function(e){
            this.jogadorGolId = '';
          });
        },

      },

      watch:
      {
        temporada()
        {
          this.getJogadores();
        },

        temporada_estatistica_id()
        {
          this.getEstatisticas();
        }
      },

      mounted() {
        this.getDados();
      }
  }
</script>
<template>
  <div class="row" style="padding-top: 10px">
      <div class="col-lg-2">
          <h3>Veteranos</h3>
      </div>
      <div class="col-lg-10" style="text-align: right">
          <span class="botoes">
          <button  type = "button" class = "btn btn-primary" id="myInput" v-on:click="openModalJogador()">
          NOVO JOGADOR
          </button>
      </span>
      <span class="botoes">
          <button  type = "button" class="btn btn-primary" id="myInput" v-on:click="openModalEstatisticas()">
          ESTATISTICAS
          </button>
      </span>
      </div>
  </div>
  <br>
  <div class="row">
    <div class="col col-lg-3">
        <label for="temporadas" class="col-form-label" style="padding: 2%;">Temporadas</label>
        <select class="form-select" id="temporada" aria-label="Default select example" v-model="temporada">
          <option value="" selected>Selecione a temporada</option>
          <option v-for="temporada in temporadas" :value="temporada.id" :key="temporada.id">{{temporada.nome}}</option>
        </select>
    </div>
  </div>
  <br>

  <div class="row"> 
    <div class="col col-lg-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="10%">Id</th>
                    <th width="35%">Nome</th>
                    <th>Gols Marcados</th>
                    <th>Gols Sofridos</th>
                    <th>Gols Contra</th>
                    <th width="6%">Ações</th>
                </tr>
            </thead>
            <tbody>
                
                <tr v-for="(jogador, index) in jogadores" :key="jogador.id">
                    <td>{{index+1}}</td>
                    <td>{{jogador.nome}}</td>
                    <td>{{jogador.gols?jogador.gols:'-'}}</td>
                    <td>{{jogador.gols_sofridos?jogador.gols_sofridos:'-'}}</td>
                    <td>{{jogador.gol_contra?jogador.gol_contra:'-'}}</td>
                    <td>
                      <a style="cursor: pointer;" v-on:click="openModalJogador(jogador)"><i class="bi bi-pencil-fill" title="Editar"></i></a>
                      <a>
                        <i v-if="jogador.participacoes" class="bi bi-trash3-fill jogador_excluir_not" title="Excluir"></i>
                        <i v-else v-on:click="deleteConfirmacao(jogador.id)" class="bi bi-trash3-fill jogador_excluir"  title="Excluir"></i>
                      </a>
                      <a style="cursor: pointer;" v-on:click="openModalGols(jogador.id)"><i class="bi bi-person-fill-add" title="Adicionar Gols"></i></a>
                    </td>
                </tr>
                
            </tbody>
        </table>
        </div>
    </div>

    <div class="modal fade" id="jogadorModal" tabindex="-1" aria-labelledby="jogadorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="jogadorModalLabel">Jogador</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Nome</label>
                        <input type="text" name="nome" v-model="jogador_nome" class="form-control" id="nome"/>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" v-on:click="jogador?atualizarJogador():salvarJogador()">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="estatisticas" tabindex="-1" aria-labelledby="estatisticasModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content estatisticasModalLabel" style="height: 60%;">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="estatisticasModalLabel">Estatísticas</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col col-lg-3">
                    <label for="temporadas" class="col-form-label">Temporadas</label>
                    <select class="form-select" id="temporada_estatistica_id" v-model="temporada_estatistica_id">
                      <option value="" selected>Selecione a temporada</option>
                      <option v-for="temporada in temporadas" :value="temporada.id" :key="temporada.id">{{temporada.nome}}</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th width="35%">Times</th>
                        <th>Vitórias</th>
                        <th>Derrotas</th>
                        <th>Empates</th>
                        <th>Gols Pro</th>
                      </tr>
                      <tr>
                        <th colspan="3"></th>
                        <th style="text-align: right">Total:</th>
                        <th>{{estatisticas.golsTotal}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Time Preto</td>
                        <td>{{estatisticas.vitoriasTimePreto}}</td>
                        <td>{{estatisticas.derrotasTimePreto}}</td>
                        <td>{{estatisticas.empates}}</td>
                        <td>{{estatisticas.golsTimePreto}}</td>
                      </tr>
                      <tr>
                        <td>Time Azul</td>
                        <td>{{estatisticas.vitoriasTimeAzul}}</td>
                        <td>{{estatisticas.derrotasTimeAzul}}</td>
                        <td>{{estatisticas.empates}}</td>
                        <td>{{estatisticas.golsTimeAzul}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
              </div>
            </div>
          </div>
        </div>

  <div class="modal fade" id="golsModal" tabindex="-1" aria-labelledby="golsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" >
      <div class="modal-content golsModal">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="golsModalLabel">Adicionar Quantidade de Gols</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col col-lg-3">
              <label for="temporadas" class="col-form-label">Temporadas</label>
              <select class="form-select" id="temporada_gols_id" >
                <option value="" selected>Selecione a temporada</option>
                <option v-for="temporada in temporadas" :value="temporada.id" :key="temporada.id">{{temporada.nome}}</option>
              </select>
            </div>
            <div class="col col-lg-2">
              <label for="gols" class="col-form-label">Quantidade de Gols</label>
              <input type="number" name="gols" class="form-control" id="gols"/>
            </div>
            <div class="col col-lg-2">
              <label for="golsSofridos" class="col-form-label">Gols Sofridos</label>
              <input type="number" name="golsSofridos" class="form-control" id="golsSofridos"/>
            </div>
            <div class="col col-lg-3">
              <label for="gols" class="col-form-label">Time</label>
              <select class="form-select" id="equipe">
                <option value="" selected>Selecione o time</option>
                <option v-for="equipe in equipes" :value="equipe.id" :key="equipe.id">{{equipe.nome}}</option>
              </select>
            </div>
            <div class="col col-lg-2">
              <label for="data" class="col-form-label">Data</label>
              <input type="date" name="data" class="form-control" id="data"/>
            </div>
          </div><br>
          <div class="row">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th width="35%">Nome</th>
                  <th>Gols</th>
                  <th>Gols Sofridos</th>
                  <th>Time</th>
                  <th>Data</th>
                  <th width="5%">Ações</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="gols in jogadorGols" :key="gols.id">
                  <td>{{gols.nome}}</td>
                  <td>{{gols.gols}}</td>
                  <td>{{gols.gols_sofridos}}</td>
                  <td>{{gols.equipe}}</td>
                  <td>{{gols.data}}</td>
                  <th>
                    <a style="cursor: pointer;" v-on:click="editGols(gols)"><i class="bi bi-pencil-fill" title="Editar"></i></a>
                    <a style="cursor: pointer;" v-on:click="modalConfirmDeleteGols(gols.id)"><i class="bi bi-trash3-fill" title="Excluir"></i></a>
                  </th>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="button" class="btn btn-primary" :disabled="disable_store" v-on:click="jogadorGolId?updateGol():storeGol()">Salvar</button>
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

  <div class="modal fade" id="modalConfirm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
              </div>
              <div class="modal-body" style="text-align: center;">
                Deseja realmente deletar esse jogador?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" v-on:click="limpar()">Não</button>
                <button type="button" class="btn btn-primary" v-on:click="deleteJogador">Sim</button>
              </div>
            </div>
          </div>
  </div>

  <div class="modal fade" id="modalConfirmDeleteGols" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
              </div>
              <div class="modal-body" style="text-align: center;">
                Deseja realmente deletar esses gols?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" v-on:click="limpar()">Não</button>
                <button type="button" class="btn btn-primary" v-on:click="deleteGols()">Sim</button>
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
</style>