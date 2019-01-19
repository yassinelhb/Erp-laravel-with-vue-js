<template>
    <div class="bl-ctn row" v-if="openCard" style="padding: 0;margin: 0" >
        <div class="modal fade show bd-example-modal-lg" id="exampleModalCenter" style="display: block ;background: rgba(0,0,0,.4);z-index: 9999"  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="header-title">
                        <h3 class="mod-title" v-if="editsousprefecture">Modifier Sousprefecture</h3>
                        <h3 class="mod-title" v-else>Ajouter sousprefecture</h3>
                        <button type="button" class="close" @click="hideCard">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="bl-ctn-modal">
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="code">Code * </label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text"  :class="{'is-invalid': errors.code}" name="code" id="code" placeholder="Code" class="form-control"  v-model="sousprefecture.code"/>
                                    <small class="invalid-feedback" v-if="errors.code"> {{errors.code[0]}} </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="libelle">Libelle *</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text"  :class="{'is-invalid': errors.libelle}" name="libelle" id="libelle" placeholder="Libelle" class="form-control" v-model="sousprefecture.libelle"/>
                                    <small class="invalid-feedback" v-if="errors.libelle"> {{errors.libelle[0]}} </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="libelle">Prefectures *</label>
                                </div>
                                <div class="col-sm-4">
                                    <select v-model="sousprefecture.prefecture_id" :class="{'is-invalid': errors.prefecture_id}" class="form-control" required>
                                        <option v-for="prefecture in prefectures" :value="prefecture.id">{{prefecture.libelle}}</option>
                                    </select>
                                    <small class="invalid-feedback" v-if="errors.prefecture_id"> {{errors.prefecture_id[0]}} </small>

                                </div>
                            </div>



                            <div class="form-group row">
                                <div class="col-sm-4 ">
                                    <label>Image </label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="bl-img-modal">
                                        <img :src="avatar"  alt="Image" name="image" v-if="avatar">
                                        <img :src="'images/localisation/sousprefecture/'+sousprefecture.image"  alt="Image" name="image" v-else-if="sousprefecture.image">
                                        <img src="images/localisation/sousprefecture/sousprefecture.png" alt="Image" v-else>
                                        <div class="bl-inp-file">
                                            <img src="img/filewhite.png"  class="img-file" />
                                            <span> Select a file</span>
                                            <input type="file" class="inp-file"  name="image" @change="GetImage"/>
                                        </div>
                                        <button class="btn btn-confir-rmv"  @click="deleteimg" v-if="avatar || (sousprefecture.image && sousprefecture.image!='sousprefecture.png')  " >
                                            <i class="fa fa-close text-white"></i> Supprimer
                                        </button>

                                    </div>
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col-sm-8 offset-4 bl-btn-bottom" >
                                    <button type="button" class="btn btn-default" @click="hideCard">Annuler</button>
                                    <button class="btn btn-primary" @click="editSousprefecture" v-if="editsousprefecture"> Enregistrer</button>
                                    <button type="button" class="btn btn-primary" @click="addSousprefecture" v-else>Ajouter</button>
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
                sousprefecture : "",
                prefectures : {},
                errors : {},
                editsousprefecture : false,
                avatar : "",
                messageAlert :""
            }
        },
        methods: {

            getPrefectures(page_url){
                axios.get('/gestionprefectures')
                    .then(response => {
                        this.prefectures = response.data.prefecture;
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
                        this.sousprefecture.image = e.target.result ;
                    }
                }
            },


            addSousprefecture(){
                this.$parent.loadingsend ="loading ...";
                axios.post('/gestionsousprefectures',this.sousprefecture).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getSousprefectures();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Sousprefecture à ete ajouter avec success ...";
                        this.hideCard();
                    }

                })
                    .catch((error) => {
                        this.$parent.loadingsend ="";
                        this.errors = error.response.data.errors;

                    })
            },

            editSousprefecture(){
                this.$parent.loadingsend ="loading ...";
                axios.patch('/gestionsousprefectures/'+this.sousprefecture.id,this.sousprefecture).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getSousprefectures();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Sousprefecture à ete modifier avec success ...";
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
                this.sousprefecture.image = "";
            },
            hideCard() {
                this.$emit('closeCard');
            }
        },
        mounted:function () {
            this.getPrefectures();
        }


    }
</script>
