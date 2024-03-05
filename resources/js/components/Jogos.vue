<script>
    export default {
      
      data() {
          return {
          estatisticasJogos:[],
          temporadas:[],
          temporada:'',
          detalhes:[],
          detalhesDoJogo:''

          }
      },


      methods: {
        getEstatisticasJogos()
        {
          const data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            temporada_id: $('#temporada').val()
          }
          jQuery.get('getEstatisticasJogos', data, res => {
            this.estatisticasJogos = res;
          });
        },


        getDados()
        {
          const data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            temporada_id: $('#temporada').val()
          }
          jQuery.get('getDadosGols', data, res => {
              this.estatisticasJogos = res.estatisticasJogos;
              this.temporadas = res.temporadas;
          })
        },

        openModalDetalhes(detalhesDoJogo = '')
        {
            const myModal = new bootstrap.Modal(document.getElementById('detalhesModal'));
            myModal.show();
            this.detalhesDoJogo = detalhesDoJogo;
            this.getDetalhes(detalhesDoJogo.data);
            $('#detalhesModal').on('shown.bs.modal', function (e) {
            });

            $('#detalhesModal').on('hidden.bs.modal', function(e){
              this.detalhesDoJogo = '';
            });
        },

        getDetalhes(data_jogo)
        {
          const data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            data: data_jogo
          }
          jQuery.get('getDetalhes', data, res => {
              this.detalhes = res;
          })
        }
      },

      watch:
      {
        temporada()
        {
          this.getEstatisticasJogos();
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
      </div>
  </div>
  <br>
  <div class="row">
    <div class="col col-lg-3">
        <label class="col-form-label" style="padding: 2%;">Temporadas</label>
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
              <th width="15%">Times</th>
              <th style="text-align: center;">Azul</th>
              <th style="text-align: center;">Preto</th>
              <th>Data</th>
              <th width="2%"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="estatistica in estatisticasJogos">
              <td>Time Azul <b>{{estatistica.golsTimeAzul}}</b> x <b>{{estatistica.golsTimePreto}}</b> Time Preto</td>
              <td style="text-align: center; font-weight: bold;">
                <span v-if="estatistica.golsTimeAzul > estatistica.golsTimePreto">V</span>
                <span v-if="estatistica.golsTimeAzul < estatistica.golsTimePreto">D</span>
                <span v-if="estatistica.golsTimeAzul == estatistica.golsTimePreto">E</span>
              </td>
              <td style="text-align: center; font-weight: bold;">
                <span v-if="estatistica.golsTimeAzul > estatistica.golsTimePreto">D</span>
                <span v-if="estatistica.golsTimeAzul < estatistica.golsTimePreto">V</span>
                <span v-if="estatistica.golsTimeAzul == estatistica.golsTimePreto">E</span>
              </td>
              <td>{{estatistica.data_formatada}}</td>
              <td><a style="cursor: pointer;" v-on:click="openModalDetalhes(estatistica)"><i class="bi bi-journal-text" title="Detalhes"></i></a></td>
            </tr>
          </tbody>
        </table>
        </div>
    </div>
    <div class="modal fade" id="detalhesModal" tabindex="-1" aria-labelledby="detalhesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" >
      <div class="modal-content detalhesModal">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="detalhesModalLabel">Detalhes</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <br>
          <div class="row">
            <div class="col col-lg-12">
              Time Azul <b>{{detalhesDoJogo.golsTimeAzul}}</b> x <b>{{detalhesDoJogo.golsTimePreto}}</b> Time Preto
            </div>
          </div>
          <br>
          <div class="row">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th width="35%">Nome</th>
                  <th>Gols</th>
                  <th>Gols Sofridos</th>
                  <th>Gols Contra</th>
                  <th>Time</th>
                  <th>Data</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="detalhe in detalhes">
                  <td>{{detalhe.nome}}</td>
                  <td v-if="detalhe.gols">{{detalhe.gols}}</td>
                  <td v-else>-</td>
                  <td v-if="detalhe.gols_sofridos">{{detalhe.gols_sofridos}}</td>
                  <td v-else>-</td>
                  <td v-if="detalhe.gol_contra">{{detalhe.gol_contra}}</td>
                  <td v-else>-</td>
                  <td>{{detalhe.equipe}}</td>
                  <td>{{detalhe.data_formatada}}</td>

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
</template>
<style scoped>
    .bi-journal-text{
      padding: 1.7;
      color: blue;
    }
</style>