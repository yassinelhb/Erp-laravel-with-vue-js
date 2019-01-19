<template>
    <div class="bl-ctn" v-if="openCard"  >
        <div class="bl-ctn-modal row" >
          <div class="col-sm-6">
            <h5 class="ttile">Generale</h5>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="code">Code *</label>
                </div>
                <div class="col-sm-7">
                    <input type="text"  :class="{'is-invalid': errors.code}" name="code" id="code" placeholder="Code" class="form-control"  v-model="av.code"/>
                    <small class="invalid-feedback" v-if="errors.code"> {{errors.code[0]}} </small>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="libelle">Libelle *</label>
                </div>
                <div class="col-sm-7">
                    <input type="text"  :class="{'is-invalid': errors.libelle}" name="libelle" id="libelle" placeholder="Libelle" class="form-control" v-model="av.libelle"/>
                    <small class="invalid-feedback" v-if="errors.libelle"> {{errors.libelle[0]}} </small>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="representant">Representant *</label>
                </div>
                <div class="col-sm-7">
                    <input type="text"  :class="{'is-invalid': errors.representant}" name="representant" id="representant" placeholder="representant" class="form-control" v-model="av.representant"/>
                    <small class="invalid-feedback" v-if="errors.representant"> {{errors.representant[0]}} </small>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="adresse">Adresse *</label>
                </div>
                <div class="col-sm-7">
                    <input type="text"  :class="{'is-invalid': errors.adresse}" name="adresse" id="adresse" placeholder="adresse" class="form-control" v-model="av.adresse"/>
                    <small class="invalid-feedback" v-if="errors.adresse"> {{errors.adresse[0]}} </small>
                </div>
            </div>
            <div class="form-group row">
                  <div class="col-sm-4">
                      <label for="libelle">Cgi *</label>
                  </div>
                  <div class="col-sm-4">
                      <select v-model="av.cgi_id" :class="{'is-invalid': errors.cgi_id}" class="form-control" required>
                          <option v-for="cgi in cgis" :value="cgi.id">{{cgi.libelle}}</option>
                      </select>
                      <small class="invalid-feedback" v-if="errors.cgi_id"> {{errors.cgi_id[0]}} </small>

                  </div>
              </div>
            <div class="form-group row">
                  <div class="col-sm-4 ">
                      <label>Image </label>
                  </div>
                  <div class="col-sm-8">
                      <div class="bl-img-modal">
                          <img :src="avatar"  alt="Image" name="image" v-if="avatar">
                          <img :src="'images/localisation/av/'+av.image"  alt="Image" name="image" v-else-if="av.image">
                          <img src="images/localisation/av/av.jpg" alt="Image" v-else>
                          <div class="bl-inp-file">
                              <img src="img/filewhite.png"  class="img-file" />
                              <span> Select a file</span>
                              <input type="file" class="inp-file"  name="image" @change="GetImage"/>
                          </div>
                          <button class="btn btn-confir-rmv"  @click="deleteimg" v-if="avatar || (av.image && av.image!='canton.png')  " >
                              <i class="fa fa-close text-white"></i> Supprimer
                          </button>

                      </div>
                  </div>

              </div>
          </div>
          <div class="col-sm-6">
            <h5 class="ttile">Contactez-les</h5>
            <div class="form-group row">
                  <div class="col-sm-4">
                      <label for="email">Email *</label>
                  </div>
                  <div class="col-sm-7">
                      <input type="email"  :class="{'is-invalid': errors.email}" name="email" id="email" placeholder="email" class="form-control" v-model="av.email"/>
                      <small class="invalid-feedback" v-if="errors.email"> {{errors.email[0]}} </small>
                  </div>
              </div>
            <div class="form-group row">
                  <div class="col-sm-4">
                      <label for="numfix">Num Fix *</label>
                  </div>
                  <div class="col-sm-7">
                      <input type="text"  :class="{'is-invalid': errors.numfix}" name="numfix" id="numfix" placeholder="numfix" class="form-control" v-model="av.numfix"/>
                      <small class="invalid-feedback" v-if="errors.numfix"> {{errors.numfix[0]}} </small>
                  </div>
              </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="mobile">Mobile *</label>
                </div>
                <div class="col-sm-7">
                    <input type="text"  :class="{'is-invalid': errors.mobile}" name="mobile" id="mobile" placeholder="mobile" class="form-control" v-model="av.mobile"/>
                    <small class="invalid-feedback" v-if="errors.mobile"> {{errors.mobile[0]}} </small>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="fax">Fax</label>
                </div>
                <div class="col-sm-7">
                    <input type="text"  :class="{'is-invalid': errors.fax}" name="fax" id="fax" placeholder="fax" class="form-control" v-model="av.fax"/>
                    <small class="invalid-feedback" v-if="errors.fax"> {{errors.fax[0]}} </small>
                </div>
            </div>

          </div>
          <div class="form-group col-sm-6 row">
                <div class="col-sm-8 offset-4 bl-btn-bottom" >
                    <button type="button" class="btn btn-default" @click="hideCard">Annuler</button>
                    <button class="btn btn-primary" @click="editAv" v-if="editav"> Enregistrer</button>
                    <button type="button" class="btn btn-primary" @click="addAv" v-else>Ajouter</button>
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
                av : "",
                cgis : {},
                errors : {},
                editav : false,
                avatar : "",
                messageAlert :""
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
            GetImage(e) {
                let type =e.target.files[0].type;
                if(type == 'image/jpeg' || type == 'image/png' || type == 'image/jpg'){
                    let image = e.target.files[0]
                    let reader = new FileReader();
                    reader.readAsDataURL(image);
                    reader.onload = e => {
                        this.avatar = e.target.result ;
                        this.av.image = e.target.result ;
                    }
                }
            },


            addAv(){
                this.$parent.loadingsend ="loading ...";
                axios.post('/gestionavs',this.av).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getAvs();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Av à ete ajouter avec success ...";
                        this.hideCard();
                    }

                })
                    .catch((error) => {
                        this.$parent.loadingsend ="";
                        this.errors = error.response.data.errors;

                    })
            },

            editAv(){
                this.$parent.loadingsend ="loading ...";
                axios.patch('/gestionavs/'+this.av.id,this.av).then((response)=>{
                    if(response.data.etat){
                        this.$parent.getAvs();
                        this.$parent.loadingsend ="";
                        this.$parent.messageAlert ="Av à ete modifier avec success ...";
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
                this.av.image = "";
            },
            hideCard() {
                this.$emit('closeCard');
            }
        },
        mounted:function () {
            this.getCgis();
        }


    }
</script>
