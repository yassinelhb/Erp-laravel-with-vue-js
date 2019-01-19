<template>
   <div class="bl-ctn" v-if="openCard">

    <div class="bl-header-user row">
        <div class="col-sm-2 bl-img-user">

            <img :src="avatar"  alt="Image" name="image" v-if="avatar">
            <img :src="'images/'+user.image"  alt="Image" name="image" v-else-if="user.image">
            <img src="images/logo-user.jpg" alt="Image" v-else>
            <div class="bl-inp-file">
               <img src="img/filewhite.png"  class="img-file" />
               <span> Select a file</span>
              <input type="file" class="inp-file"  name="image" @change="GetImage" />
            </div>
            <button class="btn btn-confir-rmv" @click="deleteimg" v-if="avatar || (user.image && user.image!='logo-user.jpg')  ">
                <i class="fa fa-close text-white"></i> Supprimer
            </button>

        </div>
        <div class="col-sm-4">
           <div class="form-group">
               <label for="nom">Nom D'utilisateur *</label>
               <input type="text" :class="{'is-invalid': errors.nom}" class="form-control inp-uname" id="nom" name="nom"  v-model="user.nom" />
               <small class="invalid-feedback" v-if="errors.nom"> {{errors.nom[0]}} </small>

           </div>
            <div class="form-group">
                <select class="form-control"  :class="{'is-invalid': errors.role}" name="role" v-model="user.role" >
                    <option value="">Role</option>
                    <option>Cozoc</option>
                    <option>Responsable CGI</option>
                    <option>Responsable Financier</option>
                    <option>Gestionnaire de pont Bascule</option>
                </select>
                <small class="invalid-feedback" v-if="errors.role"> {{errors.role[0]}} </small>
            </div>
        </div>
    </div>
    <div class="bl-content-user row ">

       <div class="col-sm-6">
          <h5 class="ttile">Generale</h5>
           <div class="form-group row">
               <div class="col-sm-4">
                   <label for="matricule">Matricule *</label>
               </div>
               <div class="col-sm-7">
                   <input type="text"  :class="{'is-invalid': errors.matricule}" name="matricule" id="matricule" placeholder="Matricule" class="form-control" v-model="user.matricule" />
                   <small class="invalid-feedback" v-if="errors.matricule"> {{errors.matricule[0]}} </small>

               </div>
           </div>
           <div class="form-group row">
              <div class="col-sm-4">
                 <label for="adr">Adresse Email *</label>
              </div>
              <div class="col-sm-7">
                  <input type="email"  :class="{'is-invalid': errors.email}" name="email" id="adr" placeholder="Adresse email" class="form-control" v-model="user.email" />
                  <small class="invalid-feedback" v-if="errors.email"> {{errors.email[0]}} </small>
              </div>
           </div>
           <div class="form-group row">
               <div class="col-sm-4">
                   <label for="sexe">Sexe *</label>
               </div>
               <div class="col-sm-3">
                   <select class="form-control inp-select " :class="{'is-invalid': errors.sexe}" name="sexe" id="sexe" v-model="user.sexe" >
                       <option>Homme</option>
                       <option>Femme</option>
                   </select>
                   <small class="invalid-feedback" v-if="errors.sexe"> {{errors.sexe[0]}} </small>

               </div>
           </div>

           <div class="form-group row">
               <div class="col-sm-4">
                   <label for="tel">Telephone *</label>
               </div>
               <div class="col-sm-7 form-tel">
                   <select name="codetel" v-model="user.codetel">
                       <option value="+216" >TUNISIE(+216)</option>
                       <option value="+235">TCHAD(+235)</option>
                       <option value="+33">FRANCE(+33)</option>
                       <option value="+213">ALGERIE(+213)</option>

                   </select>
                   <input type="number" :class="{'is-invalid': errors.telephone} " name="telephone" id="tel" placeholder="00 000 000" class="form-control" v-model="user.telephone" />
                   <small class="invalid-feedback" v-if="errors.telephone"> {{errors.telephone[0]}} </small>

               </div>
           </div>



       </div>
       <div class="col-sm-5">
           <h5 class="ttile">Adresse</h5>
           <div class="form-group row">
               <div class="col-sm-4">
                   <label for="rue">Rue *</label>
               </div>
               <div class="col-sm-8">
                   <input type="text" :class="{'is-invalid': errors.rue}" class="form-control" id="rue" name="rue" placeholder="Rue" v-model="user.rue" />
                   <small class="invalid-feedback" v-if="errors.rue"> {{errors.rue[0]}} </small>

               </div>
           </div>
           <div class="form-group row">
               <div class="col-sm-4">
                   <label for="ville">Ville *</label>
               </div>
               <div class="col-sm-8">
                   <input type="text"  :class="{'is-invalid': errors.ville}" class="form-control" id="ville" name="ville" placeholder="Ville" v-model="user.ville" />
                   <small class="invalid-feedback" v-if="errors.ville"> {{errors.ville[0]}} </small>

               </div>
           </div>
           <div class="form-group row">
               <div class="col-sm-4">
                   <label for="etat" >Etat *</label>
               </div>
               <div class="col-sm-8">
                   <input type="text"  :class="{'is-invalid': errors.etat}" id="etat" name="etat" placeholder="Etat" class="form-control" v-model="user.etat" />
                   <small class="invalid-feedback" v-if="errors.etat"> {{errors.etat[0]}} </small>

               </div>
           </div>


       </div>

       <div class="col-sm-8 offset-2 bl-btn-bottom" >
           <button class="btn btn-default" @click="hideCard"> Fermer</button>
           <button class="btn btn-primary" @click="editUser" v-if="edituser"> Enregistrer</button>
           <button class="btn btn-primary" @click="addUser" v-else> Ajouter</button>
       </div>


    </div>



   </div>
</template>

<script>
    export default {
        props: ['openCard'],
        data(){
           return {
               user : "",
               errors : {},
               edituser : false,
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
                        this.user.image = e.target.result ;
                    }
                }
            },


            addUser(){
                this.$parent.loadingsend ="loading ...";
                axios.post('/gestionusers',this.user).then((response)=>{
                        if(response.data.etat){
                            this.$parent.loadingsend ="";
                            this.$parent.getUsers();
                            this.$parent.messageAlert ="l'utilisateur à ete ajouter avec success ...";
                            this.hideCard();
                        }

                })
                    .catch((error) => {
                        this.$parent.loadingsend ="";
                        this.errors = error.response.data.errors;

                    })
            },

            editUser(){
                this.$parent.loadingsend ="loading ...";
                axios.patch('/gestionusers/'+this.user.id,this.user).then((response)=>{
                    if(response.data.etat){
                        this.$parent.loadingsend ="";
                        this.$parent.getUsers();
                        this.$parent.messageAlert ="l'utilisateur à ete modifier avec success ...";
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
                this.user.image = "";
            },
            hideCard() {
                this.$emit('closeCard');
            }
        },


    }
</script>
