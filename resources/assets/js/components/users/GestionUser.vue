<template>
    <div class="section-ges">

        <div v-if="loadingsend" class="alert-success-top">{{loadingsend}}</div>
        <div v-if="messageAlert" class="alert-success-top">{{messageAlert}}</div>

        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Export
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="gestionusers/exports/xls">Export xls</a>
                <a class="dropdown-item" href="gestionusers/exports/xlsx">Export xlsx</a>
                <a class="dropdown-item" href="/gestionusers/exports/csv">Export csv</a>
            </div>
        </div>
        <div class="bladsh row">

            <button class="btn btn-primary" @click="openCreate" > + Ajouter</button>
            <div class="b-search col-md-3 offset-md-9" >
                <i class="fa fa-search"></i>
                <input type="text" v-model="search"/>
            </div>
        </div>

        <adduser :openCard="createActive" @closeCard="closeCreate"></adduser>
        <deleteuser :openCard="deleteActive" @closeCard="closeDelete"></deleteuser>

        <div class="table-responsive">
         <table class="table">
            <thead>
            <tr>
                <th >Nom D'Utilisateur</th>
                <th>Matricule</th>
                <th>Email</th>
                <th>Telephone</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users" v-if="users.length >0">
                <td>

                    <div class="bl-img-tab">
                        <img class="img-user-tab" :src="'images/'+user.image"/>
                    </div>
                    <span class="ft-uname">{{user.nom}} </span>
                </td>
                <td>{{user.matricule}}</td>
                <td>{{user.email}}</td>
                <td>{{user.codetel}}{{user.telephone}}</td>
                <td>{{user.role}}</td>
                <td>
                    <button class="btn btn-primary" @click="EditUser(user)">Modifier</button>
                    <i class="fas fa-spinner fa-spin " v-if="loading_id == user.id"></i>
                    <button class="btn btn-danger" @click="DeleteUser(user.id)"  v-else>Supprimer</button>
                </td>
            </tr>
            <tr v-if="users.length == 0">
                <td colspan="7"><h5 class="text-center">Il n'y a aucun user</h5></td>
            </tr>
            </tbody>
        </table>

        </div>

        <span>Afficher
                <span v-if="pagination.from">{{pagination.from}} </span>
                <span v-else> 0</span> à
                <span v-if="pagination.to">{{pagination.to}} </span>
                <span v-else> 0</span>
                de {{pagination.total}}</span>
        <ul class="pagination float-right">
            <li class="page-item ">
                <button class="btn btn-prev" @click="getUsers(pagination.prev_page_url)"
                        :disabled="!pagination.prev_page_url">
                    Previous
                </button>
            </li>
            <li class="page-item " v-for="item in pagination.last_page" >
                <button class="btn "  :class="{'active':(item === pagination.current_page)}" @click="getUsers('http://localhost:8080/gestionusers/'+search+'?page='+item)">
                    {{item}}
                </button>
            </li>
            <li class="page-item ">
                <button class="btn btn-next" @click="getUsers(pagination.next_page_url)"
                        :disabled="!pagination.next_page_url">Next
                </button>
            </li>

        </ul>


    </div>
</template>

<script>
    export default {
        data() {
            return {
                users : {},
                createActive : false,
                deleteActive : false,
                loading_id : -1,
                pagination: {},
                loadingsend : "",
                messageAlert : "",
                search: ""
            }
        },
        methods: {

            hideAlert(){
                this.messageAlert = "";
            },
            openCreate() {
                this.$children[0].user = {
                    id: 0,
                    matricule: "",
                    nom: "",
                    password: "",
                    role: "",
                    sexe: "",
                    rue: "",
                    ville: "",
                    etat: "",
                    codetel: "+216",
                    telephone: "",
                    image: "",
                    password_confirmation: "",
                };
                this.$children[0].edituser = false;
                this.$children[0].avatar = "";
                this.$children[0].errors = "";
                this.createActive = true ;

            },
            closeCreate(){
                this.createActive = false;
            },
            EditUser(user) {
                this.$children[0].user = {
                        id: user.id,
                        matricule: user.matricule,
                        nom: user.nom,
                        email: user.email,
                        password: user.password,
                        role: user.role,
                        sexe: user.sexe,
                        rue: user.rue,
                        ville: user.ville,
                        etat: user.etat,
                        codetel: user.codetel,
                        telephone: user.telephone,
                        image: user.image,
                        password_confirmation: user.password_confirmation,
                };
                this.$children[0].edituser = true;
                this.$children[0].avatar = "";
                this.$children[0].errors = "";
                this.createActive = true;
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 190;
            },
            DeleteUser(key) {
                this.$children[1].id_user = key;
                this.deleteActive = true;


            },
            closeDelete(){
                this.deleteActive = false;
            },
            getUsers(page_url){
                let vm = this;
                page_url = page_url || '/gestionusers/'+this.search;

                axios.get(page_url)
                    .then(response => {
                        this.users = response.data.userspagination.data;
                        vm.makePagination(response.data.userspagination);
                    }).catch(error => {
                    console.log(error);
                })
            },
            makePagination: function(data) {
                let pagination = {
                    current_page: data.current_page,
                    last_page: data.last_page,
                    next_page_url: data.next_page_url,
                    prev_page_url: data.prev_page_url,
                    total :  data.total,
                    to : data.to,
                    from : data.from,

                };
                this.pagination = pagination;
            }
        },
        mounted() {
            this.getUsers();
        },
        watch: {
            messageAlert(){
                setTimeout(this.hideAlert , 3000);
            },
            search(){
                this.getUsers();
            }
        }
    }
</script>
