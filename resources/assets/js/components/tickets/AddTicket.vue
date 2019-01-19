<template>
    <div class="bl-ctn" v-if="openCard"  >
                <div class="modal-content modal-larg">

                        <div class="bl-ctn-modal row" >
                          <div class="col-sm-6">

                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="caisse">Caisse *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="number"  :class="{'is-invalid': errors.caisse}" name="caisse" id="caisse"  placeholder="Caisse" class="form-control"  v-model="ticket.caisse"/>
                                    <small class="invalid-feedback" v-if="errors.caisse"> {{errors.caisse[0]}} </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="dateP1">Date P1 *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="datetime-local"  :class="{'is-invalid': errors.dateP1}" name="dateP1" id="dateP1"  class="form-control"  v-model="ticket.dateP1"/>
                                    <small class="invalid-feedback" v-if="errors.dateP1"> {{errors.dateP1[0]}} </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="dateP2">Date P2 *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="datetime-local"  :class="{'is-invalid': errors.dateP2}" name="dateP2" id="dateP2" class="form-control" v-model="ticket.dateP2"/>
                                    <small class="invalid-feedback" v-if="errors.dateP2"> {{errors.dateP2[0]}} </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="poidsP1">Poids P1 *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="number"  :class="{'is-invalid': errors.poidsP1}" name="poidsP1" id="poidsP1" placeholder="Poids P1" class="form-control" v-model="ticket.poidsP1"/>
                                    <small class="invalid-feedback" v-if="errors.poidsP1"> {{errors.poidsP1[0]}} </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="poidsP2">Poids P2 *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="number"  :class="{'is-invalid': errors.poidsP2}" name="poidsP2" id="poidsP2" placeholder="Poids P2" class="form-control" v-model="ticket.poidsP2"/>
                                    <small class="invalid-feedback" v-if="errors.poidsP2"> {{errors.poidsP2[0]}} </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="peseur">Peseur *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text"  :class="{'is-invalid': errors.peseur}" name="peseur" id="peseur"  placeholder="Peseur" class="form-control"  v-model="ticket.peseur"/>
                                    <small class="invalid-feedback" v-if="errors.peseur"> {{errors.peseur[0]}} </small>
                                </div>
                            </div>
                          </div>
                          <div class="col-sm-6">
                              <div class="form-group row">
                                  <div class="col-sm-4">
                                      <label for="vehicule">Véhicule *</label>
                                  </div>
                                  <div class="col-sm-7">
                                      <input type="text"  :class="{'is-invalid': errors.vehicule}" name="vehicule" id="vehicule" placeholder="Véhicule" class="form-control" v-model="ticket.vehicule"/>
                                      <small class="invalid-feedback" v-if="errors.vehicule"> {{errors.vehicule[0]}} </small>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <div class="col-sm-4">
                                      <label for="av">Association Villegoise*</label>
                                  </div>
                                  <div class="col-sm-7 frm-select">
                                      <select class="form-control" id="av" :class="{'is-invalid': errors.av}" v-model="prefecture" v-if="!ticket.av || prefecture" >
                                          <option value="">Selection Prefectrue</option>
                                          <option v-for="prefecture in prefectures" :value="prefecture.id" >
                                              {{ prefecture.code }}, {{ prefecture.libelle }}
                                          </option>
                                      </select>
                                      <select class="form-control" :class="{'is-invalid': errors.av}"   v-model="sousprefecture" v-if="prefecture">
                                          <option value="">Selection Sousprefecture</option>
                                          <option v-for="sousprefecture in sousprefectures" :value="sousprefecture.id">
                                              {{ sousprefecture.code }}, {{ sousprefecture.libelle }}
                                          </option>
                                      </select>
                                      <select class="form-control" :class="{'is-invalid': errors.av}"  v-model="canton" v-if="sousprefecture">
                                          <option value="">Selection Canton</option>
                                          <option v-for="canton in cantons" :value="canton.id">
                                              {{ canton.code }}, {{ canton.libelle }}
                                          </option>
                                      </select>
                                      <select class="form-control"  :class="{'is-invalid': errors.av}"  v-model="cgi" v-if="canton">
                                          <option value="">Selection Centre de Gestion des Intrants</option>
                                          <option v-for="cgi in cgis" :value="cgi.id">
                                              {{ cgi.code }}, {{ cgi.libelle }}
                                          </option>
                                      </select>
                                      <select class="form-control" :class="{'is-invalid': errors.av}"  v-model="ticket.av" v-if="ticket.av || cgi">
                                          <option value="">Association Villegoise</option>
                                          <option v-for="av in avs" :value="av.id">
                                              {{ av.code }}, {{ av.libelle }}
                                          </option>
                                      </select>
                                      <i class="fa fa-edit" v-if="ticket.av && !prefecture" @click="EditAv" ></i>
                                      <small class="invalid-feedback" style="margin-top: -10px" v-if="errors.av"> {{errors.av[0]}} </small>
                                  </div>
                              </div>

                          </div>
                          <div class="form-group col-sm-7 offset-2 row bl-textarea">
                              <div class="col-sm-3">
                                  <label for="observation">Observation</label>
                              </div>
                              <div class="col-sm-9">
                                 <textarea :class="{'is-invalid': errors.observation}" name="observation" id="observation"  class="form-control"  placeholder="Observation" v-model="ticket.observation">
                                    {{ticket.observation}}
                                 </textarea>
                                  <small class="invalid-feedback" v-if="errors.observation"> {{errors.observation[0]}} </small>
                              </div>
                          </div>
                          <div class="form-group col-sm-12 row">
                                <div class="col-sm-6 offset-5 bl-btn-bottom" >
                                    <button type="button" class="btn btn-default" @click="hideCard">Annuler</button>
                                    <button class="btn btn-primary" @click="editTicket" v-if="editticket"> Enregistrer</button>
                                    <button type="button" class="btn btn-primary" @click="addTicket" v-else>Ajouter</button>
                                </div>
                          </div>
                        </div>


                </div>
    </div>

</template>

<script>
    export default {
        props: ['openCard'],
        data(){
            return {
                ticket : "",
                errors : {},
                editticket : false,
                messageAlert :"",
                prefectures : {},
                sousprefectures : {},
                cantons : {},
                cgis: {},
                avs: {},
                errors : {},
                prefecture : "",
                sousprefecture : "",
                canton : "",
                cgi : "",
            }
        },
        methods: {

            getCgis(){
                axios.get('/gestioncgis')
                    .then(response => {
                        this.cgis = response.data.cgis;
                    }).catch(error => {
                    console.log(error);
                })
            },

            addTicket(){
                this.$parent.loadingsend ="loading ...";
                axios.post('/gestiontickets',this.ticket).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getTickets();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Ticket à ete ajouter avec success ...";
                        this.hideCard();
                    }

                })
                    .catch((error) => {
                        this.$parent.loadingsend ="";
                        this.errors = error.response.data.errors;

                    })
            },

            editTicket(){
                this.$parent.loadingsend ="loading ...";
                axios.patch('/gestiontickets/'+this.ticket.id,this.ticket).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getTickets();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Ticket à ete modifier avec success ...";
                        this.hideCard();

                    }

                })
                    .catch((error) =>{
                        this.$parent.loadingsend ="";
                        this.errors = error.response.data.errors;
                    })
            },

            hideCard() {
                this.$emit('closeCard');
            },

            getPrefectures(){
                axios.get('/gestionprefectures')
                    .then(response => {
                        this.prefectures = response.data.prefecture;
                    }).catch(error => {
                    console.log(error);
                })
            },
            getSousprefectures(){
                axios.get('/gestionsousprefectures/'+this.prefecture)
                    .then(response => {
                        this.sousprefectures = response.data.sousprefecture;
                    }).catch(error => {
                    console.log(error);
                })
            },

            getCantons(){
                axios.get('/gestioncantons/'+this.sousprefecture)
                    .then(response => {
                        this.cantons = response.data.cantons;
                    }).catch(error => {
                    console.log(error);
                })
            },
            getCgis(){
                axios.get('/gestioncgis/'+this.canton)
                    .then(response => {
                        this.cgis = response.data.cgis;
                    }).catch(error => {
                    console.log(error);
                })
            },
            getAvs(){
                axios.get('/gestionavs/'+this.cgi)
                    .then(response => {
                        this.avs = response.data.avs;
                    }).catch(error => {
                    console.log(error);
                })
            },
            EditAv(){
                this.ticket.av = ""
            }
        },
        mounted:function () {
            this.getPrefectures();
        },
        watch: {
            ticket(){
                this.prefecture = '';
                this.getSousprefectures();
                this.sousprefecture = '';
                this.getCantons();
                this.canton = '';
                this.getCgis();
                this.getAvs()

            },
            prefecture(){
                this.getSousprefectures();
                this.errors.av = ""
            },
            sousprefecture(){
                this.getCantons();
                this.errors.av = ""
            },
            canton(){
                this.getCgis();
                this.errors.av = ""
            },
            cgi(){
                this.getAvs();
                this.errors.av = ""
            },


        }


    }
</script>
