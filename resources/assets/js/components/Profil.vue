<template>


        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 profil-general ">
                    <div class="profil-img">
                        <div class="circle-img">
                            <img :src="avatar"  alt="Image" name="image"  class="rounded-circle" v-if="avatar">
                            <img :src="'images/'+user.image" name="image" class="rounded-circle" v-else>
                            <div class="bl-file">
                                <img src="img/filewhite.png"  class="img-file" />
                                <span> Select a file</span>
                                <input type="file" class="inp-file"  name="image" @change="GetImage"/>

                            </div>
                        </div>
                        <h2 class="title-profil">{{user.nom}}</h2>
                        <span class="role-profil">{{user.role}}</span>
                    </div>
                    <span class="h-seperate"></span>
                    <div class="profil-content">
                        <small>Matricule</small>
                        <h6>{{user.matricule}}</h6>
                        <small class="p-t-30">Adresse email</small>
                        <h6>{{user.email}}</h6>

                        <small class="p-t-30">Telephone</small>
                        <h6>{{user.codetel}}{{user.telephone}}</h6>
                        <small class="p-t-30">Adresse</small>
                        <h6>{{user.rue}} {{user.ville}}, {{user.etat}}</h6>


                    </div>
                </div>
                <div class="col-md-7 profil-setting">

                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#gen" class="active">Generale</a></li>
                        <li><a data-toggle="tab" href="#psd" >Mot de passe</a></li>

                    </ul>

                    <div class="tab-content">

                        <div id="gen" class="tab-pane fade in active">
                          <form v-on:submit.prevent="editProfil()">
                            <div class="form-group">
                                <label for="nom">Nom</label>
                                <input type="text"  :class="{'is-invalid': errors.nom}" class="form-control" id="nom" name="nom" v-model="user.nom"  />
                                <small class="invalid-feedback" v-if="errors.nom"> {{errors.nom[0]}} </small>

                            </div>

                            <div class="form-group">
                                <label for="telephone">Telephone</label>

                                <input type="number":class="{'is-invalid': errors.telephone}" class="form-control inp-tel" id="telephone" name="telephone"   v-model="user.telephone"/>
                                <select class="codtel form-control" v-model="user.codetel">
                                    <option value="+216" >TUNISIE(+216)</option>
                                    <option value="+235">TCHAD(+235)</option>
                                    <option value="+33">FRANCE(+33)</option>
                                    <option value="+213">ALGERIE(+213)</option>
                                </select>
                                <small class="invalid-feedback" v-if="errors.telephone"> {{errors.telephone[0]}} </small>


                            </div>

                            <div class="form-group">
                                <label for="rue">Rue</label>
                                <input type="text"  :class="{'is-invalid': errors.rue}" class="form-control" id="rue" name="rue"   v-model="user.rue" />
                                <small class="invalid-feedback" v-if="errors.rue"> {{errors.rue[0]}} </small>

                            </div>

                            <div class="form-group">
                                <label for="ville">Ville</label>
                                <input type="text"  :class="{'is-invalid': errors.ville}" class="form-control" id="ville" name="ville"  v-model="user.ville"  />
                                <small class="invalid-feedback" v-if="errors.ville"> {{errors.ville[0]}} </small>

                            </div>

                            <div class="form-group">
                                <label for="etat">Etat</label>
                                <input type="etat"  :class="{'is-invalid': errors.etat}" class="form-control" id="etat" name="e"  v-model="user.etat"  />
                                <small class="invalid-feedback" v-if="errors.etat"> {{errors.etat[0]}} </small>

                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary" @click="editProfil">Enregistrer</button>
                            </div>
                          </form>

                        </div>
                        <div id="psd" class="tab-pane fade">
                          <form v-on:submit.prevent="editPassword()">

                            <div class="form-group">
                                <label for="actpwd">Mot De Passe actuel</label>
                                <input type="password" :class="{'is-invalid': errors.current_password}" id="actpwd" name="current_password"  class="form-control"  v-model="user.current_password" />
                                <small class="invalid-feedback" v-if="errors.current_password"> {{errors.current_password[0]}} </small>

                            </div>

                            <div class="form-group">
                                <label for="pwd">Nouveau mot de passe</label>
                                <input type="password" :class="{'is-invalid': errors.password}" id="pwd" name="password"  class="form-control"  v-model="user.password" />
                                <small class="invalid-feedback" v-if="errors.password"> {{errors.password[0]}} </small>

                            </div>

                            <div class="form-group">
                                <label for="confr">Confirmer Mot De Passe</label>
                                <input type="password"  id="confr" name="password_confirmation"  class="form-control"  v-model="user.password_confirmation" />

                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary" @click="editPassword">Enregistrer</button>
                            </div>
                          </form>

                        </div>

                    </div>

                </div>

            </div>
        </div>
</template>
<script>
    export default {
        data(){
           return {
               user : "",
               avatar : null,
               errors : {},
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

            getProfil(){
                axios.get('/profile')
                    .then(response => {
                        this.user = {
                            id : response.data.id,
                            nom : response.data.nom,
                            role : response.data.role,
                            email : response.data.email,
                            matricule : response.data.matricule,
                            telephone : response.data.telephone,
                            codetel : response.data.codetel,
                            image : response.data.image,
                            rue : response.data.rue,
                            ville : response.data.ville,
                            etat : response.data.etat,
                            current_password : "",
                            password : "",
                            password_confirmation : ""
                        }
                    }).catch(error => {
                    console.log(error);
                })
            },
            editProfil(){
                axios.patch('/profile/'+this.user.id,this.user).then((response)=>{
                    if(response.data.etat){
                        window.location.href = "/profil"

                    }

                })
                    .catch((error) =>{
                        this.errors = error.response.data.errors;
                    })
            },

            editPassword(){
                axios.patch('/profile/editpassword/'+this.user.id,this.user).then((response)=>{

                    if(response.data.etat){
                        window.location.href = "/profil"
                    }


                })
                    .catch((error) =>{
                        this.errors = error.response.data.errors;
                    })
            },

        },
        mounted() {
            this.getProfil();
        }
    }
</script>
