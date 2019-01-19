<template>
    <div class="bl-ctn row" v-if="openCard" style="padding: 0;margin: 0" >
        <div class="modal fade show bd-example-modal-lg" id="exampleModalCenter" style="display: block ;background: rgba(0,0,0,.4);z-index: 9999"  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="header-title">
                        <h3 class="mod-title" v-if="editprefecture">Modifier Prefecture</h3>
                        <h3 class="mod-title" v-else>Ajouter Prefecture</h3>
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
                                    <input type="text"  :class="{'is-invalid': errors.code}" name="code" id="code" placeholder="Code" class="form-control" v-model="prefecture.code"/>
                                    <small class="invalid-feedback" v-if="errors.code"> {{errors.code[0]}} </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="libelle">Libelle *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text"  :class="{'is-invalid': errors.libelle}" name="libelle" id="libelle" placeholder="Libelle" class="form-control" v-model="prefecture.libelle"/>
                                    <small class="invalid-feedback" v-if="errors.libelle"> {{errors.libelle[0]}} </small>
                                </div>
                            </div>



                            <div class="form-group row">
                                <div class="col-sm-4 ">
                                    <label>Image </label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="bl-img-modal">
                                        <img :src="avatar"  alt="Image" name="image" v-if="avatar">
                                        <img :src="'images/localisation/prefecture/'+prefecture.image"  alt="Image" name="image" v-else-if="prefecture.image">
                                        <img src="images/localisation/prefecture/prefecture.png" alt="Image" v-else>
                                        <div class="bl-inp-file">
                                            <img src="img/filewhite.png"  class="img-file" />
                                            <span> Select a file</span>
                                            <input type="file" class="inp-file"  name="image"  @change="GetImage"/>
                                        </div>
                                        <button class="btn btn-confir-rmv"  @click="deleteimg" v-if="avatar || (prefecture.image && prefecture.image!='prefecture.png')  " >
                                            <i class="fa fa-close text-white"></i> Supprimer
                                        </button>

                                    </div>
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col-sm-8 offset-4 bl-btn-bottom" >
                                  <button type="button" class="btn btn-default" @click="hideCard">Annuler</button>
                                  <button class="btn btn-primary" @click="editPrefecture" v-if="editprefecture"> Enregistrer</button>
                                  <button type="button" class="btn btn-primary" @click="addPrefecture" v-else>Ajouter</button>
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
               prefecture : "",
               errors : {},
               editprefecture : false,
               avatar : "",
               messageAlert :""
           }
        },
        methods: {

            GetImage(e) {
                let type =e.target.files[0].type;
                if(type == 'image/jpeg' || type == 'image/png' || type == 'image/jpg'){
                    let image = e.target.files[0]
                    let reader = new FileReader();
                    reader.readAsDataURL(image);
                    reader.onload = e => {
                        this.avatar = e.target.result ;
                        this.prefecture.image = e.target.result ;
                    }
                }
            },

            addPrefecture(){
                this.$parent.loadingsend ="loading ...";
                axios.post('/gestionprefectures',this.prefecture).then((response)=>{
                        if(response.data.etat){
                            this.$parent.getPrefectures();
                            this.$parent.loadingsend ="";
                            this.$parent.messageAlert ="Prefecture à ete ajouter avec success ...";
                            this.hideCard();
                        }

                })
                    .catch((error) => {
                        this.$parent.loadingsend ="";
                        this.errors = error.response.data.errors;

                    })
            },

            editPrefecture(){
                this.$parent.loadingsend ="loading ...";
                axios.patch('/gestionprefectures/'+this.prefecture.id,this.prefecture).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getPrefectures();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Prefecture à ete modifier avec success ...";
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
                this.prefecture.image = "";
            },
            hideCard() {
                this.$emit('closeCard');
            }
        },


    }
</script>
