<template>
    <div class="bl-ctn row" v-if="openCard" style="padding: 0;margin: 0" >
        <div class="modal fade show bd-example-modal-lg" id="exampleModalCenter" style="display: block ;background: rgba(0,0,0,.4);z-index: 9999"  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="header-title">
                        <h3 class="mod-title" v-if="editbesoin">Modifier Besoin</h3>
                        <h3 class="mod-title" v-else>Ajouter Besoin</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"  @click="hideCard">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="bl-ctn-modal">
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="code">Code *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text"  :class="{'is-invalid': errors.code}" name="code" id="code" placeholder="Code" class="form-control" v-model="besoin.code"/>
                                    <small class="invalid-feedback" v-if="errors.code"> {{errors.code[0]}} </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="libelle">Libelle *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text"  :class="{'is-invalid': errors.libelle}" name="libelle" id="libelle" placeholder="Libelle" class="form-control" v-model="besoin.libelle"/>
                                    <small class="invalid-feedback" v-if="errors.libelle"> {{errors.libelle[0]}} </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="date">Date *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="date"  :class="{'is-invalid': errors.date}" name="date" id="date" placeholder="Date" class="form-control" v-model="besoin.date"/>
                                    <small class="invalid-feedback" v-if="errors.date"> {{errors.date[0]}} </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="av">Association Villegoise *</label>
                                </div>
                                <div class="col-sm-7 frm-select">
                                    <select class="form-control" id="av" :class="{'is-invalid': errors.av}" v-model="prefecture" v-if="!besoin.av || prefecture" >
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
                                    <select class="form-control" :class="{'is-invalid': errors.av}"  v-model="besoin.av" v-if="besoin.av || cgi">
                                        <option value="">Association Villegoise</option>
                                        <option v-for="av in avs" :value="av.id">
                                            {{ av.code }}, {{ av.libelle }}
                                        </option>
                                    </select>
                                    <i class="fa fa-edit" v-if="besoin.av && !prefecture" @click="EditAv" ></i>
                                    <small class="invalid-feedback" style="margin-top: -10px" v-if="errors.av"> {{errors.av[0]}} </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-8 offset-4 bl-btn-bottom" >
                                    <button type="button" class="btn btn-default" @click="hideCard">Annuler</button>
                                    <button class="btn btn-primary" @click="editBesoin" v-if="editbesoin"> Enregistrer</button>
                                    <button type="button" class="btn btn-primary" @click="addBesoin" v-else>Ajouter</button>
                                </div>
                            </div>
                        </div>
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
                besoin : "",
                prefectures : {},
                sousprefectures : {},
                cantons : {},
                cgis: {},
                avs: {},
                errors : {},
                editbesoin : false,
                messageAlert :"",
                prefecture : "",
                sousprefecture : "",
                canton : "",
                cgi : "",


            }
        },
        methods: {

            addBesoin(){
                this.$parent.loadingsend ="loading ...";
                axios.post('/gestionbesoins',this.besoin).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getBesoins();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Besoin à ete ajouter avec success ...";
                        this.hideCard();
                    }

                })
                    .catch((error) => {
                        this.$parent.loadingsend ="";
                        this.errors = error.response.data.errors;

                    })
            },

            editBesoin(){

                this.$parent.loadingsend ="loading ...";
                axios.patch('/gestionbesoins/'+this.besoin.id,this.besoin).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getBesoins();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Besoin à ete modifier avec success ...";
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
                this.besoin= "";
                this.prefecture = "";
                this.sousprefecture = "";
                this.canton = "";
                this.cgi = "";
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
                this.besoin.av = ""
            }

        },

        mounted:function () {
            this.getPrefectures();
        },
        watch: {
            besoin(){
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
