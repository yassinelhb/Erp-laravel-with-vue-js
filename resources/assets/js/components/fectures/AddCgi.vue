<template>
    <div class="bl-ctn row" v-if="openCard" style="padding: 0;margin: 0" >
        <div class="modal fade show bd-example-modal-lg" id="exampleModalCenter" style="display: block ;background: rgba(0,0,0,.4);z-index: 9999"  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="header-title">
                        <h3 class="mod-title" v-if="editcgi">Modifier cgi</h3>
                        <h3 class="mod-title" v-else>Ajouter cgi</h3>
                        <button type="button" class="close" @click="hideCard">
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
                                    <input type="text"  :class="{'is-invalid': errors.code}" name="code" id="code" placeholder="Code" class="form-control"  v-model="cgi.code"/>
                                    <small class="invalid-feedback" v-if="errors.code"> {{errors.code[0]}} </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="libelle">Libelle *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text"  :class="{'is-invalid': errors.libelle}" name="libelle" id="libelle" placeholder="Libelle" class="form-control" v-model="cgi.libelle"/>
                                    <small class="invalid-feedback" v-if="errors.libelle"> {{errors.libelle[0]}} </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="libelle">Canton *</label>
                                </div>
                                <div class="col-sm-4">
                                    <select v-model="cgi.canton_id" :class="{'is-invalid': errors.canton_id}" class="form-control" required>
                                        <option v-for="canton in cantons" :value="canton.id">{{canton.libelle}}</option>
                                    </select>
                                    <small class="invalid-feedback" v-if="errors.canton_id"> {{errors.canton_id[0]}} </small>

                                </div>
                            </div>


                           <div class="form-group row">
                                <div class="col-sm-4 ">
                                    <label>Image </label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="bl-img-modal">
                                        <img :src="avatar"  alt="Image" name="image" v-if="avatar">
                                        <img :src="'images/localisation/cgi/'+cgi.image"  alt="Image" name="image" v-else-if="cgi.image">
                                        <img src="images/localisation/cgi/cgi.png" alt="Image" v-else>
                                        <div class="bl-inp-file">
                                            <img src="img/filewhite.png"  class="img-file" />
                                            <span> Select a file</span>
                                            <input type="file" class="inp-file"  name="image" @change="GetImage"/>
                                        </div>
                                        <button class="btn btn-confir-rmv"  @click="deleteimg" v-if="avatar || (cgi.image && cgi.image!='canton.png')  " >
                                            <i class="fa fa-close text-white"></i> Supprimer
                                        </button>

                                    </div>
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col-sm-8 offset-4 bl-btn-bottom" >
                                    <button type="button" class="btn btn-default" @click="hideCard">Annuler</button>
                                    <button class="btn btn-primary" @click="editCgi" v-if="editcgi"> Enregistrer</button>
                                    <button type="button" class="btn btn-primary" @click="addCgi" v-else>Ajouter</button>
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
                cgi : "",
                cantons : {},
                errors : {},
                editcgi : false,
                avatar : "",
                messageAlert :""
            }
        },
        methods: {

            getCantons(page_url){
                axios.get('/gestioncantons')
                    .then(response => {
                        this.cantons = response.data.cantons;
                    }).catch(error => {
                    console.log(error);
                })
            },
            GetImage(e) {
                let type =e.target.files[0].type;
                if(type == 'image/jpeg' || type == 'image/png' || type == 'image/jpg'){
                    let image = e.target.files[0]
                    let reader = new FileReader();
                    reader.readAsDataURL(image);
                    reader.onload = e => {
                        this.avatar = e.target.result ;
                        this.cgi.image = e.target.result ;
                    }
                }
            },


            addCgi(){
                this.$parent.loadingsend ="loading ...";
                axios.post('/gestioncgis',this.cgi).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getCgis();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Cgi à ete ajouter avec success ...";
                        this.hideCard();
                    }

                })
                    .catch((error) => {
                        this.$parent.loadingsend ="";
                        this.errors = error.response.data.errors;

                    })
            },

            editCgi(){
                this.$parent.loadingsend ="loading ...";
                axios.patch('/gestioncgis/'+this.cgi.id,this.cgi).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getCgis();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Cgi à ete modifier avec success ...";
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
                this.cgi.image = "";
            },
            hideCard() {
                this.$emit('closeCard');
            }
        },
        mounted:function () {
            this.getCantons();
        }


    }
</script>
