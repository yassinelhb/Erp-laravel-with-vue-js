<template>
    <div class="bl-ctn" v-if="openDetail" >
        <div class="col-sm-8 offset-2" style="margin-top: 32px;margin-bottom: 25px">
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="besoin">Besoin </label>
                </div>
                <div class="col-sm-7">
                    <select :class="{'is-invalid': errors.besoin}" name="besoin" id="besoin" class="form-control" v-model="besoin" >
                        <option value="">Selection Besoin</option>
                        <option v-for="besoin in besoins" v-if="besoin.articles.length"  :value="besoin">{{ besoin.libelle}}</option>
                    </select>
                    <small class="invalid-feedback" v-if="errors.besoin"> {{errors.besoin[0]}} </small>

                </div>
            </div>
        </div>

        <div class="bl-ctn-modal row" v-if="besoin">
            <div class="col-sm-6">
                <div class="form-group row" >
                    <div class="col-sm-4">
                        <label >Prefecture</label>
                    </div>
                    <div class="col-sm-7">
                        {{ besoin.av.cgi.canton.sousprefecture.prefecture.libelle}}
                    </div>
                </div>
                <div class="form-group row" >
                    <div class="col-sm-4">
                        <label >SousPrefecture</label>
                    </div>
                    <div class="col-sm-7">
                        {{ besoin.av.cgi.canton.sousprefecture.libelle}}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="datePaiement">Date Paiement *</label>
                    </div>
                    <div class="col-sm-7">
                        <input type="date"  :class="{'is-invalid': errors.datePaiement}" name="datePaiement" id="datePaiement"  class="form-control" v-model="bordereau.datePaiement"/>
                        <small class="invalid-feedback" v-if="errors.datePaiement"> {{errors.datePaiement[0]}} </small>

                    </div>
                </div>


            </div>
            <div class="col-sm-6">

                <div class="form-group row">
                    <div class="col-sm-4">
                        <label >Association Villegoise</label>
                    </div>
                    <div class="col-sm-7">
                        {{ besoin.av.cgi.canton.libelle}}
                        {{ besoin.av.cgi.libelle}}
                        {{ besoin.av.libelle}}
                    </div>
                </div>

                <div class="form-group row" >
                    <div class="col-sm-4">
                        <label >Date Livraison </label>
                    </div>
                    <div class="col-sm-7">
                        {{ besoin.date }}
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="modaliteP">Modalité Paiement </label>
                    </div>
                    <div class="col-sm-7">
                        <select name="modaliteP" id="modaliteP" class="form-control" v-model="bordereau.modaliteP">
                            <option>Chèque</option>
                            <option>Espèce</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group col-sm-7 offset-2 row bl-textarea">
                <div class="col-sm-3">
                    <label for="observation">Observation</label>
                </div>
                <div class="col-sm-9">
                        <textarea  name="observation" id="observation"  class="form-control"  placeholder="Observation" v-model="bordereau.observation">
                        </textarea>
                </div>
            </div>
            <div class="table-responsive col-sm-10 offset-1" v-if="details.length">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th>Article</th>
                        <th>Designation</th>
                        <th>Unité</th>
                        <th>Prix Vente</th>
                        <th>Quantite</th>
                        <th>Tva (%)</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="detail in details" >
                        <td>{{detail.article.code}}</td>
                        <td>{{detail.article.libelle}}</td>
                        <td>{{detail.article.unitevente}}</td>
                        <td>{{detail.article.saisons[0].pivot.prixvente}}</td>
                        <td>{{detail.quantite}}</td>
                        <td>{{detail.article.saisons[0].pivot.tva}}</td>
                            <td>{{(detail.article.saisons[0].pivot.tva * detail.quantite * detail.article.saisons[0].pivot.prixvente )/100 +  detail.quantite * detail.article.saisons[0].pivot.prixvente}}</td>
                    </tr>



                    </tbody>
                </table>
                <table class="table table-total table-bordered">
                    <tr>
                        <td>Total Besoin</td>
                        <td>{{ Total() }}</td>
                    </tr>
                </table>
            </div>
            <div class="form-group col-sm-6 row">
                <div class="col-sm-12 offset-4 bl-btn-bottom" >
                    <button type="button" class="btn btn-default" @click="close">Annuler</button>
                    <button class="btn btn-primary" v-if="editbord" @click="editBordereau"> Enregistrer</button>
                    <button type="button" class="btn btn-primary" v-if="!editbord" @click="addBordereau">Ajouter</button>

                </div>
            </div>
        </div>


    </div>
</template>

<script>
    export default {
        props: ['openDetail'],
        data() {
            return {
                besoins : {},
                besoin : "",
                details : {},
                bordereau : "",
                editbord : false,
                errors : {},
                total : 0,


            }
        },
        methods: {

            getBesoins(){
                axios.get('/gestionbesoins')
                    .then(response => {
                        this.besoins = response.data.besoins;
                    }).catch(error => {
                    console.log(error);
                })
            },
            getDetails(){
                axios.get('/gestionbordereau/'+this.besoin.saison_id+'/'+this.besoin.id)
                    .then(response => {
                        this.details = response.data.details;
                    }).catch(error => {
                    console.log(error);
                })
            },
            close(){
                this.$emit('close');
                this.besoin =""
            },
            Total(){
                return this.total =  this.details.reduce(function(total, detail){
                    return total + ((detail.article.saisons[0].pivot.tva * detail.quantite * detail.article.saisons[0].pivot.prixvente )/100 +
                     detail.quantite * detail.article.saisons[0].pivot.prixvente);
                },0);
            },
            addBordereau(){
                this.$parent.loadingsend ="loading ...";
                this.bordereau.detailbesoin = this.details;
                this.bordereau.total = this.total ;
                axios.post('/gestionbordereau',this.bordereau).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getBordereaux();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Bordereau à ete ajouter avec success ...";
                        this.close();
                    }

                })
                    .catch((error) => {
                        this.$parent.loadingsend ="";
                        this.errors = error.response.data.errors;

                    })
            },

            editBordereau(){
                this.$parent.loadingsend ="loading ...";
                this.bordereau.detailbesoin = this.details;
                this.bordereau.total = this.total ;
                axios.patch('/gestionbordereau/'+this.bordereau.id,this.bordereau).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getBordereaux();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Bordereau à ete modifier avec success ...";
                        this.close();
                    }

                })
                    .catch((error) => {
                        this.$parent.loadingsend ="";
                        this.errors = error.response.data.errors;

                    })
            },

        },
        mounted() {
           this.getBesoins();

        },
        watch: {
            besoin(){
                this.getDetails();
                this.bordereau.besoin = this.besoin;
            },
        }
    }
</script>
