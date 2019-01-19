<template>
    <div class="bl-ctn row" v-if="openCard" style="padding: 0;margin: 0" >
        <div class="modal fade show bd-example-modal-lg" id="exampleModalCenter" style="display: block ;background: rgba(0,0,0,.4);z-index: 9999"  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="header-title">
                        <h3 class="mod-title" v-if="editarticle">Modifier Article</h3>
                        <h3 class="mod-title" v-else>Ajouter Article</h3>
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
                                    <input type="text"  :class="{'is-invalid': errors.code}" name="code" id="code" placeholder="Code" class="form-control" v-model="article.code"/>
                                    <small class="invalid-feedback" v-if="errors.code"> {{errors.code[0]}} </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="libelle">Libelle *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text"  :class="{'is-invalid': errors.libelle}" name="libelle" id="libelle" placeholder="Libelle" class="form-control" v-model="article.libelle"/>
                                    <small class="invalid-feedback" v-if="errors.libelle"> {{errors.libelle[0]}} </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="qte">Quantité disponible *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="number" :class="{'is-invalid': errors.quantite} " name="quantite" id="qte" placeholder="Quantité disponible" class="form-control" v-model="article.quantite" />
                                    <small class="invalid-feedback" v-if="errors.quantite"> {{errors.quantite[0]}} </small>

                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="uvente">Unité vente *</label>
                                </div>
                                <div class="col-sm-7">
                                    <select :class="{'is-invalid': errors.unitevente} " name="unitevente" id="uvente" class="form-control" v-model="article.unitevente" >
                                       <option>Kg</option>
                                    </select>
                                    <small class="invalid-feedback" v-if="errors.unitevente"> {{errors.unitevente[0]}} </small>

                                </div>
                            </div>

                            <div class="form-group row" v-if="editarticle">
                                <div class="col-sm-4">
                                    <label for="prixvente">Prix Vente *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text"  :class="{'is-invalid': errors.prixvente}" name="prixvente" id="prixvente" placeholder="Prix vente" class="form-control" v-model="article.prixvente"/>
                                    <small class="invalid-feedback" v-if="errors.prixvente"> {{errors.prixvente[0]}} </small>
                                    <div class="icon-warning" v-if="article.prixvente == 0"> <i class="fa fa-warning"></i> <span>Saisir prix vente</span></div>

                                </div>
                            </div>

                            <div class="form-group row" v-if="editarticle">
                                <div class="col-sm-4">
                                    <label for="tva">Tva (%) *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text"  :class="{'is-invalid': errors.tva}" name="tva" id="tva" placeholder="Tva" class="form-control" v-model="article.tva"/>
                                    <small class="invalid-feedback" v-if="errors.tva"> {{errors.tva[0]}} </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4 ">
                                    <label for="uvente">Image </label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="bl-img-modal">
                                        <img :src="avatar"  alt="Image" name="image" v-if="avatar">
                                        <img :src="'images/product/'+article.image"  alt="Image" name="image" v-else-if="article.image">
                                        <img src="images/product/product.png" alt="Image" v-else>
                                        <div class="bl-inp-file">
                                            <img src="img/filewhite.png"  class="img-file" />
                                            <span> Select a file</span>
                                            <input type="file" class="inp-file"  name="image" @change="GetImage"/>
                                        </div>
                                        <button class="btn btn-confir-rmv"  @click="deleteimg" v-if="avatar || (article.image && article.image!='product.png')  " >
                                            <i class="fa fa-close text-white"></i> Supprimer
                                        </button>

                                    </div>
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col-sm-8 offset-4 bl-btn-bottom" >
                                  <button type="button" class="btn btn-default" @click="hideCard">Annuler</button>
                                  <button class="btn btn-primary" @click="editArticle" v-if="editarticle"> Enregistrer</button>
                                  <button type="button" class="btn btn-primary" @click="addArticle" v-else>Ajouter</button>
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
               article : "",
               errors : {},
               editarticle : false,
               avatar : "",
           }
        },
        methods: {

            GetImage(e) {
                let image = e.target.files[0]
                let reader = new FileReader();
                reader.readAsDataURL(image);
                reader.onload = e => {
                     this.avatar = e.target.result ;
                     this.article.image = e.target.result ;
                }

            },

            addArticle(){
                this.$parent.loadingsend ="loading ...";
                axios.post('/gestionarticles',this.article).then((response)=>{
                        if(response.data.etat){
                            this.$parent.getArticles();
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

            editArticle(){
                this.$parent.loadingsend ="loading ...";
                axios.patch('/gestionarticles/'+this.article.id,this.article).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getArticles();
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
            deleteimg(){
                this.avatar = "";
                this.article.image = "";
            },
            hideCard() {
                this.$emit('closeCard');
            }
        },


    }
</script>
