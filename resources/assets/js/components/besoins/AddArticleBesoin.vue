<template>
    <div class="bl-ctn row" v-if="openCard" style="padding: 0;margin: 0" >
        <div class="modal fade show bd-example-modal-lg" id="exampleModalCenter" style="display: block ;background: rgba(0,0,0,.4);z-index: 9999"  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="header-title">
                        <h3 class="mod-title" v-if="editarticlebesoin">Modifier Article</h3>
                        <h3 class="mod-title" v-else>Ajouter Article</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"  @click="hideCard">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="bl-ctn-modal">
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label>Besoin *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text"  class="form-control" :value="besoin.libelle" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="article">Article *</label>
                                </div>
                                <div class="col-sm-7">
                                    <select :class="{'is-invalid': errors.article}" name="article" id="article"  class="form-control" v-model="articlebesoin.article">
                                     <option value="">Selection Article</option>
                                     <option v-for="article in articles" :value="article.id" v-if="article.saisons.length> 0 && article.saisons[0].pivot.prixvente > 0">{{article.code}}, {{article.libelle}}</option>
                                    </select>
                                    <small class="invalid-feedback" v-if="errors.article"> {{errors.article[0]}} </small>




                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="qte">Quantite *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="number"  :class="{'is-invalid': errors.quantite}" name="quantite" id="qte"  class="form-control" v-model="articlebesoin.quantite"/>
                                    <small class="invalid-feedback" v-if="errors.quantite"> {{errors.quantite[0]}} </small>
                                </div>
                            </div>


                            <div class="form-group row">
                                <div class="col-sm-8 offset-4 bl-btn-bottom" >
                                    <button type="button" class="btn btn-default" @click="hideCard">Annuler</button>
                                    <button class="btn btn-primary" @click="editArticlebesoin" v-if="editarticlebesoin"> Enregistrer</button>
                                    <button type="button" class="btn btn-primary" @click="addArticlebesoin" v-else>Ajouter</button>
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
        props: ['openCard','besoin'],
        data(){
            return {
                articlebesoin : "",
                articles : {},
                errors : {},
                editarticlebesoin : false,
                messageAlert :"",
            }
        },
        methods: {

            addArticlebesoin(){
                this.$parent.loadingsend ="loading ...";
                axios.post('/gestiondetailbesoin',this.articlebesoin).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getArticlebesoins();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Article à ete ajouter avec success ...";
                        this.hideCard();
                    }
                })
                    .catch((error) => {
                        this.$parent.loadingsend ="";
                        this.errors = error.response.data.errors;

                    })
            },

            editArticlebesoin(){
                this.$parent.loadingsend ="loading ...";
                axios.patch('/gestiondetailbesoin/'+this.articlebesoin.id,this.articlebesoin).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getArticlebesoins();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Article à ete modifier avec success ...";
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
            getArticles(){
                axios.get('/gestionarticles/'+this.articlebesoin.saison)
                    .then(response => {
                        this.articles = response.data.articles;
                        console.log(this.articles)
                    }).catch(error => {
                    console.log(error);
                })
            },

        },

        watch: {
            articlebesoin(){
                this.getArticles()
            }
        },



    }
</script>
