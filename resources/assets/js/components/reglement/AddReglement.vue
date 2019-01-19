<template>
    <div class="bl-ctn" v-if="openDetail" >
        <div class="bl-ctn-modal" >
            <div class="col-sm-8 offset-2" style="margin-top: 32px ; margin-bottom: 25px" v-if="!editbord">
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="facture">Facture </label>
                    </div>
                    <div class="col-sm-7">
                        <select  name="facture" id="facture" class="form-control" v-model="facture" >
                            <option value="">Selection facture</option>
                            <option v-for="facture in factures"  v-if="!facture.factureglobal" :value="facture">{{ facture.nfacture}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 row"  v-if="facture" :class="{'mg-top-45': editbord}">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label>Montant TTC </label>
                        </div>
                        <div class="col-sm-7">
                            <input type="text" class="form-control"  :value="facture.bordereaulivraison.total" disabled/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label>Montant à régle </label>
                        </div>
                        <div class="col-sm-7">
                            <input type="text" class="form-control"   v-model="bordereau.montantreglement" disabled/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="montantP">Montant payer *</label>
                        </div>
                        <div class="col-sm-7">
                            <input type="number"   name="montantP" id="montantP" :class="{'is-invalid': errors.montantP}" class="form-control"  v-model="bordereau.montantP"/>
                            <small class="invalid-feedback" v-if="errors.montantP"> {{errors.montantP[0]}} </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="modaliteP">Reste</label>
                        </div>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" :value="reste" disabled>
                        </div>
                    </div>

                </div>

                <div class="col-sm-6">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="date">Date réglement *</label>
                        </div>
                        <div class="col-sm-7">
                            <input type="date" :class="{'is-invalid': errors.datereglement}" class="form-control" name="datereglement" id="date" v-model="bordereau.datereglement"/>
                            <small class="invalid-feedback" v-if="errors.datereglement"> {{errors.datereglement[0]}} </small>

                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="modaliteP">Modalité Paiement</label>
                        </div>
                        <div class="col-sm-7">
                            <select name="modaliteP" id="modaliteP" class="form-control" v-model="bordereau.modaliteP">
                                <option>Chèque</option>
                                <option>Espèce</option>
                            </select>
                        </div>
                    </div>


                </div>
            </div>

            <div class="form-group col-sm-6 row" v-if="facture">
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
                bordereau : "",
                errors : {},
                factures : {},
                openSelect : false,
                facture : "",
                reste : "",
                editbord : false


            }
        },
        methods: {


            getFactures(){
                axios.get('/gestionfacturelivraisons')
                    .then(response => {
                        this.factures = response.data.factures;
                    }).catch(error => {
                    console.log(error);
                })
            },

            getBordereau(facture){
                axios.get('/gestionreglement/'+facture.id)
                    .then(response => {
                        if(response.data.bordereau){
                            this.bordereau.montantreglement = response.data.bordereau.montantreglement -
                                response.data.bordereau.montantP;
                        }else {
                            this.bordereau.montantreglement = facture.bordereaulivraison.total;
                        }
                        this.reste =  this.bordereau.montantreglement;
                        this.bordereau.montantP = 0;
                    }).catch(error => {
                    console.log(error);
                })
            },

            close(){
                this.$emit('close');
                this.facture =""
            },

            addBordereau(){
                this.$parent.loadingsend ="loading ...";
                axios.post('/gestionreglement',this.bordereau).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getBordereaux();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Bordereau à été ajouter avec success ...";
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
                axios.patch('/gestionreglement/'+this.bordereau.id,this.bordereau).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getBordereaux();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Bordereau à été modifier avec success ...";
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
           this.getFactures();

        },
        watch: {
            'bordereau.montantP'(){
                this.reste = this.bordereau.montantreglement - this.bordereau.montantP;
            },
            facture(){
                this.errors = {};
                if(!this.editbord){
                    this.bordereau.facture = this.facture;
                    this.getBordereau(this.facture);
                }

            }
        }
    }
</script>
