<template>
    <div class="section-ges">

        <div v-if="loadingsend" class="alert-success-top">{{loadingsend}}</div>
        <div v-if="messageAlert" class="alert-success-top">{{messageAlert}}</div>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Export
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="gestionavs/exports/xls">Export xls</a>
                <a class="dropdown-item" href="gestionavs/exports/xlsx">Export xlsx</a>
                <a class="dropdown-item" href="gestionavs/exports/csv">Export csv</a>
            </div>
        </div>
        <div class="bladsh row ">
            <button class="btn btn-primary " @click="openCreate"> + Ajouter</button>
            <div class="b-search col-md-3 offset-md-9" >
                <i class="fa fa-search"></i>
                <input type="text" v-model="search"/>
            </div>
        </div>

        <addav :openCard="createActive" @closeCard="closeCreate"></addav>
        <deleteav :openCard="deleteActive" @closeCard="closeDelete"></deleteav>
        <div class="row">
            <div class="col-md-4" v-for="av in avs">
              <div class="card">
                <div class="card-body row">
                    <div class="ct-header col-sm-4">
                        <img class="img-user-tab" :src="'images/localisation/av/'+av.image"/>
                    </div>
                    <div class="ct-body col-sm-8">
                        <h5>{{av.libelle}}</h5>
                        <p>{{av.code}}</p>
                        <p>{{av.representant}}</p>
                        <p class="text-dark">{{av.cgi.libelle}}</p>
                        <button class="btn btn-primary" @click="EditAv(av)">Modifier</button>
                        <i class="fas fa-spinner fa-spin " v-if="loading_id == av.id"></i>
                        <button class="btn btn-danger" @click="DeleteAv(av.id)" v-else>Supprimer</button>

                    </div>
                </div>
              </div>
            </div>
        </div>
        <span>Afficher
                <span v-if="pagination.from">{{pagination.from}} </span>
                <span v-else> 0</span> à
                <span v-if="pagination.to">{{pagination.to}} </span>
                <span v-else> 0</span>
                de {{pagination.total}}</span>
        <ul class="pagination float-right">
            <li class="page-item ">
                <button class="btn btn-prev" @click="getAvs(pagination.prev_page_url)"
                        :disabled="!pagination.prev_page_url">
                    Previous
                </button>
            </li>
            <li class="page-item " v-for="item in pagination.last_page" >
                <button class="btn "  :class="{'active':(item === pagination.current_page)}" @click="getAvs('http://localhost:8080/gestionavs/'+search+'?page='+item)">
                    {{item}}
                </button>
            </li>
            <li class="page-item ">
                <button class="btn btn-next" @click="getAvs(pagination.next_page_url)"
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
                avs : {},
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
                this.$children[0].av = {
                    code: "",
                    cgi_id: "",
                    libelle: "",
                    representant : "",
                    adresse : "",
                    email : "",
                    numfix : "",
                    mobile : "",
                    fax : "",
                    image: ""
                };
                this.$children[0].editav = false;
                this.$children[0].avatar = "";
                this.$children[0].errors = "";
                this.createActive = true;

            },
            closeCreate(){
                this.createActive = false;
            },
            EditAv(av) {
                this.$children[0].av = {
                    id : av.id,
                    code: av.code,
                    cgi_id: av.cgi.id,
                    libelle: av.libelle,
                    representant : av.representant,
                    adresse : av.adresse,
                    email : av.email,
                    numfix : av.numfix,
                    mobile : av.mobile,
                    fax : av.fax,
                    image: av.image,
                };
                this.$children[0].editav = true;
                this.$children[0].avatar = "";
                this.$children[0].errors = "";
                this.createActive = true;
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 190;
            },
            DeleteAv(key) {
                this.$children[1].id_av = key;
                this.deleteActive = true;
            },
            closeDelete(){
                this.deleteActive = false;
            },
            getAvs(page_url){
                let vm = this;
                page_url = page_url || '/gestionavs/'+this.search;

                axios.get(page_url)
                    .then(response => {
                        this.avs = response.data.avspagination.data;
                        vm.makePagination(response.data.avspagination);
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
            this.getAvs();
        },
        watch: {
            messageAlert(){
                setTimeout(this.hideAlert , 3000);
            },
            search(){
                this.getAvs();
            }
         }
    }
</script>
