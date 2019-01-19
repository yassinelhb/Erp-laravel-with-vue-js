<template>
    <div class="bl-ctn row" v-if="openCard" style="padding: 0;margin: 0" >
        <div class="modal fade show bd-example-modal-lg" id="exampleModalCenter" style="display: block ;background: rgba(0,0,0,.4);z-index: 9999"  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="header-title">
                        <h3 class="mod-title" v-if="editsaison">Modifier Saison</h3>
                        <h3 class="mod-title" v-else>Ajouter Saison</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"  @click="hideCard">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="bl-ctn-modal">
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="compagne">Compagne *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text"  :class="{'is-invalid': errors.compagne}" name="compagne" id="compagne" placeholder="Compagne" class="form-control" v-model="saison.compagne"/>
                                    <small class="invalid-feedback" v-if="errors.compagne"> {{errors.compagne[0]}} </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="datedebut">Date début *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="date"  :class="{'is-invalid': errors.datedebut}" name="datedebut" id="datedebut" placeholder="Date début" class="form-control" v-model="saison.datedebut"/>
                                    <small class="invalid-feedback" v-if="errors.datedebut"> {{errors.datedebut[0]}} </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="datefin">Date fin *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="date"  :class="{'is-invalid': errors.datefin}" name="datefin" id="datefin" placeholder="Date fin" class="form-control" v-model="saison.datefin"/>
                                    <small class="invalid-feedback" v-if="errors.datefin"> {{errors.datefin[0]}} </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="commentaire">Commentaire </label>
                                </div>
                                <div class="col-sm-7">
                                    <textarea :class="{'is-invalid': errors.commentaire}" class="form-control" name="commentaire" id="commentaire" placeholder="Commentaire"  v-model="saison.commentaire">{{saison.commentaire}}</textarea>
                                    <small class="invalid-feedback" v-if="errors.commentaire"> {{errors.commentaire[0]}} </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="prixcoton">Prix coton *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="number"  :class="{'is-invalid': errors.prixcoton}" name="prixcoton" id="prixcoton" placeholder="Prix coton" class="form-control" v-model="saison.prixcoton"/>
                                    <small class="invalid-feedback" v-if="errors.prixcoton"> {{errors.prixcoton[0]}} </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="tvacoton">Tva coton (%) *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="number"  :class="{'is-invalid': errors.tvacoton}" name="tvacoton" id="tvacoton" placeholder="Tva coton" class="form-control" v-model="saison.tvacoton"/>
                                    <small class="invalid-feedback" v-if="errors.tvacoton"> {{errors.tvacoton[0]}} </small>
                                </div>
                            </div>


                            <div class="form-group row">
                                <div class="col-sm-8 offset-4 bl-btn-bottom" >
                                    <button type="button" class="btn btn-default" @click="hideCard">Annuler</button>
                                    <button class="btn btn-primary" @click="editSaison" v-if="editsaison"> Enregistrer</button>
                                    <button type="button" class="btn btn-primary" @click="addSaison" v-else>Ajouter</button>
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
                saison : "",
                errors : {},
                editsaison : false,
                avatar : "",
                messageAlert :""
            }
        },
        methods: {

            addSaison(){
                this.$parent.loadingsend ="loading ...";
                axios.post('/gestionsaisons',this.saison).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getSaisons();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Saison à été ajouter avec success ...";
                        this.hideCard();
                    }

                })
                    .catch((error) => {
                        this.$parent.loadingsend ="";
                        this.errors = error.response.data.errors;

                    })
            },

            editSaison(){
                this.$parent.loadingsend ="loading ...";
                axios.patch('/gestionsaisons/'+this.saison.id,this.saison).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getSaisons();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Saison à été modifier avec success ...";
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
            }
        },


    }
</script>
