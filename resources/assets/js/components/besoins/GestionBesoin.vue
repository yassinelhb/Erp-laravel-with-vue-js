<template>
    <div class="container-fluid">
        <detailbesoin :openDetail="actDetail" :besoin="besoin" @closeDetail="closeDetail"></detailbesoin>

        <div class="row" v-if="actBesoin">
        <h3 class="section-title">Consulter Les Besoins</h3>
           <div class="section-ges">
            <div v-if="loadingsend" class="alert-success-top">{{loadingsend}}</div>
            <div v-if="messageAlert" class="alert-success-top">{{messageAlert}}</div>
               <div class="bladsh row ">
                   <button class="btn btn-primary " @click="openCreate" v-if="user.role == 'Cozoc'"> + Ajouter</button>
                   <div class="b-search col-md-3 offset-md-9" >
                       <i class="fa fa-search"></i>
                       <input type="text" v-model="search"/>
                   </div>
               </div>

            <addbesoin :openCard="createActive" @closeCard="closeCreate"></addbesoin>
            <deletebesoin :openCard="deleteActive" @closeCard="closeDelete"></deletebesoin>

            <div class="table-responsive">
             <table class="table">
                <thead>
                <tr>
                    <th>Besoin</th>
                    <th>Libelle</th>
                    <th>Date</th>
                    <th>Saison</th>
                    <th>Association Villegoise</th>
                    <th v-if="user.role == 'Cozoc'">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="besoin in besoins" v-if="besoins.length >0" :class="{'active':(besoin.bordereaux.length == 0 && user.role == 'Responsable CGI')}">
                    <td>{{besoin.code}}</td>
                    <td>{{besoin.libelle}}</td>
                    <td>{{besoin.date}}</td>
                    <td>{{besoin.saison.compagne}}</td>
                    <td>{{besoin.av.code}},{{besoin.av.libelle}}</td>
                    <td v-if="user.role == 'Cozoc'">
                        <button class="btn btn-default" @click="Detail(besoin)">Detaille</button>
                        <button class="btn btn-primary" @click="EditBesoin(besoin)">Modifier</button>
                        <i class="fas fa-spinner fa-spin " v-if="loading_id == besoin.id"></i>
                        <button class="btn btn-danger" @click="DeleteBesoin(besoin.id)"  v-else>Supprimer</button>
                    </td>
                </tr>
                <tr v-if="besoins.length == 0">
                    <td colspan="7"><h5 class="text-center">Il n'y a aucun besoin</h5></td>
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
                    <button class="btn btn-prev" @click="getBesoins(pagination.prev_page_url)"
                            :disabled="!pagination.prev_page_url">
                        Previous
                    </button>
                </li>
                <li class="page-item " v-for="item in pagination.last_page" >
                    <button class="btn "  :class="{'active':(item === pagination.current_page)}" @click="getBesoins('http://localhost:8080/gestionbesoins/'+search+'?page='+item)">
                        {{item}}
                    </button>
                </li>
                <li class="page-item ">
                    <button class="btn btn-next" @click="getBesoins(pagination.next_page_url)"
                            :disabled="!pagination.next_page_url">Next
                    </button>
                </li>

            </ul>


       </div>
      </div>
   </div>
</template>

<script>
    export default {
        props: ['user'],
        data() {
            return {
                besoins : {},
                createActive : false,
                deleteActive : false,
                loading_id : -1,
                pagination: {},
                loadingsend : "",
                messageAlert : "",
                search: "",
                actBesoin : true,
                actDetail : false,
                besoin : "",
                saison : "",
                count : 0
            }
        },
        methods: {

            hideAlert(){
                this.messageAlert = "";
            },
            openCreate() {
                this.$children[1].besoin = {
                    code: '',
                    libelle: '',
                    date: '',
                    saison: this.saison.id,
                    av: '',
                };
                this.$children[1].editbesoin = false;
                this.$children[1].errors = "";
                this.createActive = true ;


            },
            closeCreate(){
                this.createActive = false;
            },
            EditBesoin(besoin) {
                this.$children[1].besoin = {
                        id: besoin.id,
                        code: besoin.code,
                        libelle: besoin.libelle,
                        date: besoin.date,
                        saison: besoin.saison.id,
                        av: besoin.av.id,
                };
                this.$children[1].editbesoin = true;
                this.$children[1].errors = "";
                this.createActive = true;
            },
            Detail(besoin){
                this.actBesoin = false;
                this.actDetail = true;
                this.besoin = besoin;
            },
            DeleteBesoin(key) {
                this.$children[2].id_besoin = key;
                this.deleteActive = true;
            },
            closeDelete(){
                this.deleteActive = false;
            },
            getSaison(){
                axios.get('/gestionsaisons')
                    .then(response => {
                        this.saison = response.data.thisaison;
                    }).catch(error => {
                    console.log(error);
                })
            },
            getBesoins(page_url){
                let vm = this;
                page_url = page_url || '/gestionbesoins/'+this.search;

                axios.get(page_url)
                    .then(response => {
                        this.besoins = response.data.besoinspagination.data;
                        vm.makePagination(response.data.besoinspagination);
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
            },
            closeDetail(){
                this.actBesoin= true
                this.actDetail= false
            },
            Besoin(id){
                axios.get('/gestionbesoins/'+id)
                    .then(response => {
                        this.besoins.unshift(response.data.besoin);
                    }).catch(error => {
                    console.log(error);
                })
            },
        },
        mounted() {
            this.getBesoins();
            this.getSaison();
            Echo.private('Besoin')
                .listen('BesoinEvent', (e) => {
                    if(this.user.role == 'Responsable CGI'){
                        this.Besoin(e.besoin.id);
                    }
                })
        },
        watch: {
            messageAlert(){
                setTimeout(this.hideAlert , 3000);
            },
            search(){
                this.getBesoins();
            },

        }
    }
</script>
