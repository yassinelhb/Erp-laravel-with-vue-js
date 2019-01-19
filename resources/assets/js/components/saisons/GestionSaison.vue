<template>
    <div class="section-ges">

        <div v-if="loadingsend" class="alert-success-top">{{loadingsend}}</div>
        <div v-if="messageAlert" class="alert-success-top">{{messageAlert}}</div>

        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Export
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="gestionsaisons/exports/xls">Export xls</a>
                <a class="dropdown-item" href="gestionsaisons/exports/xlsx">Export xlsx</a>
                <a class="dropdown-item" href="/gestionsaisons/exports/csv">Export csv</a>
            </div>
        </div>

        <div class="bladsh row ">
            <button class="btn btn-primary " @click="openCreate"> + Ajouter</button>
            <div class="b-search col-md-3 offset-md-9" >
                <i class="fa fa-search"></i>
                <input type="text" v-model="search"/>
            </div>
        </div>

        <addsaison :openCard="createActive" @closeCard="closeCreate"></addsaison>
        <deletesaison :openCard="deleteActive" @closeCard="closeDelete"></deletesaison>

        <div class="table-responsive">
         <table class="table">
            <thead>
            <tr>
                <th>Active</th>
                <th >Compagne</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Commentaire</th>
                <th>Prix Coton</th>
                <th>Tva Coton (%)</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="saison in saisons" >

                <td>
                    <i class="fa fa-toggle-on" v-if="saison.active"></i>
                    <i class="fa fa-toggle-off"  @click="checkSaison(saison.id)" v-else></i>
                </td>
                <td>
                    <span class="ft-uname">{{saison.compagne}} </span>
                </td>
                <td>{{saison.datedebut}}</td>
                <td>{{saison.datefin}}</td>
                <td v-if="saison.commentaire" class="commentaire">{{saison.commentaire}}</td>
                <td v-else>--------</td>
                <td>{{saison.prixcoton}}</td>
                <td>{{saison.tvacoton}}</td>
                <td>
                    <button class="btn btn-primary" @click="EditSaison(saison)">Modifier</button>
                    <i class="fas fa-spinner fa-spin " v-if="loading_id == saison.id"></i>
                    <button class="btn btn-danger" @click="DeleteSaison(saison.id)"  v-else>Supprimer</button>
                </td>
            </tr>
            <tr v-if="saisons.length == 0">
                <td colspan="7"><h5 class="text-center">Il n'y a aucun saison</h5></td>
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
                <button class="btn btn-prev" @click="getSaisons(pagination.prev_page_url)"
                        :disabled="!pagination.prev_page_url">
                    Previous
                </button>
            </li>
            <li class="page-item " v-for="item in pagination.last_page" >
                <button class="btn "  :class="{'active':(item === pagination.current_page)}" @click="getSaisons('http://localhost:8080/gestionsaisons/'+search+'?page='+item)">
                    {{item}}
                </button>
            </li>
            <li class="page-item ">
                <button class="btn btn-next" @click="getSaisons(pagination.next_page_url)"
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
                saisons : {},
                createActive : false,
                deleteActive : false,
                loading_id : -1,
                pagination : {},
                loadingsend : "",
                messageAlert : "",
                search : "",
            }
        },
        methods: {

            hideAlert(){
                this.messageAlert = "";
            },
            openCreate() {
                this.$children[0].saison = {
                    id: 0,
                    compagne: '',
                    datedebut: '',
                    datefin: '',
                    commentaire: '',
                    prixcoton: '',
                    tvacoton: '',
                };
                this.$children[0].editsaison = false;
                this.$children[0].errors = "";
                this.createActive = true ;

            },
            closeCreate(){
                this.createActive = false;
            },
            EditSaison(saison) {
                this.$children[0].saison = {
                        id: saison.id,
                        compagne: saison.compagne,
                        datedebut: saison.datedebut,
                        datefin: saison.datefin,
                        commentaire: saison.commentaire,
                        prixcoton: saison.prixcoton,
                        tvacoton: saison.tvacoton,
                };
                this.$children[0].editsaison = true;
                this.$children[0].errors = "";
                this.createActive = true;
            },
            checkSaison(id){
                this.loadingsend ="loading ...";
                axios.patch('/gestionactivesaisons/'+id).then((response)=>{
                    if(response.data.etat){
                        this.getSaisons();
                        this.loadingsend ="";
                        this.messageAlert ="Saison à été active ...";
                    }

                })
                    .catch((error) =>{
                        console.log(error)
                    })
            },
            DeleteSaison(key) {
                this.$children[1].id_saison = key;
                this.deleteActive = true;


            },
            closeDelete(){
                this.deleteActive = false;
            },
            getSaisons(page_url){
                let vm = this;
                var years =[];
                page_url = page_url || '/gestionsaisons/'+this.search;

                axios.get(page_url)
                    .then(response => {
                        this.saisons = response.data.saisonspagination.data;
                        for(var i=0 ; i <this.saisons.length;i++){
                            years.push(this.saisons[i].id)
                        }
                        console.log(years)
                        vm.makePagination(response.data.saisonspagination);
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

        },
        mounted() {
            this.getSaisons();
        },
        watch: {
            messageAlert(){
                setTimeout(this.hideAlert , 3000);
            },
            search(){
                this.getSaisons();
            },

        }
    }
</script>
