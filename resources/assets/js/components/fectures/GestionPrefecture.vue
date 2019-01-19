<template>
    <div class="section-ges">

        <div v-if="loadingsend" class="alert-success-top">{{loadingsend}}</div>
        <div v-if="messageAlert" class="alert-success-top">{{messageAlert}}</div>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Export
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="gestionprefectures/exports/xls">Export xls</a>
                <a class="dropdown-item" href="gestionprefectures/exports/xlsx">Export xlsx</a>
                <a class="dropdown-item" href="gestionprefectures/exports/csv">Export csv</a>
            </div>
        </div>
        <div class="bladsh row ">
            <button class="btn btn-primary " @click="openCreate" > + Ajouter</button>
            <div class="b-search col-md-3 offset-md-9" >
                <i class="fa fa-search"></i>
                <input type="text" v-model="search"/>
            </div>
        </div>

        <addprefectures :openCard="createActive" @closeCard="closeCreate"></addprefectures>
        <deleteprefectures :openCard="deleteActive" @closeCard="closeDelete"></deleteprefectures>

        <div class="row">
            <div class="col-md-4" v-for="prefecture in prefectures">
                <div class="card">
                    <div class="card-body row">
                        <div class="ct-header col-sm-4">
                            <img class="img-user-tab" :src="'images/localisation/prefecture/'+prefecture.image"/>
                        </div>
                        <div class="ct-body col-sm-8">
                            <h5>{{prefecture.libelle}}</h5>
                            <p>{{prefecture.code}}</p>
                            <button class="btn btn-primary" @click="EditPrefecture(prefecture)">Modifier</button>
                            <i class="fas fa-spinner fa-spin " v-if="loading_id == prefecture.id"></i>
                            <button class="btn btn-danger" @click="DeletePrefecture(prefecture)" v-else>Supprimer</button>

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
                <button class="btn btn-prev" @click="getPrefectures(pagination.prev_page_url)"
                        :disabled="!pagination.prev_page_url">
                    Previous
                </button>
            </li>
            <li class="page-item " v-for="item in pagination.last_page" >
                <button class="btn "  :class="{'active':(item === pagination.current_page)}" @click="getPrefectures('http://localhost:8080/gestionprefectures/'+search+'?page='+item)">
                    {{item}}
                </button>
            </li>
            <li class="page-item ">
                <button class="btn btn-next" @click="getPrefectures(pagination.next_page_url)"
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
                prefectures : {},
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
                this.$children[0].prefecture = {
                    id: 0,
                    code: "",
                    libelle: "",
                    image: ""
                };
                this.$children[0].editprefecture = false;
                this.$children[0].avatar = "";
                this.$children[0].errors = "";
                this.createActive = true ;

            },
            closeCreate(){
                this.createActive = false;
            },
            EditPrefecture(prefecture) {
                this.$children[0].prefecture = {
                    id: prefecture.id,
                    code: prefecture.code,
                    libelle: prefecture.libelle,
                    image: prefecture.image
                };
                this.$children[0].editprefecture = true;
                this.$children[0].avatar = "";
                this.$children[0].errors = "";
                this.createActive = true;
            },
            DeletePrefecture(prefecture) {
                if(prefecture.sousprefectures.length == 0){
                   this.$children[1].id_prefecture = prefecture.id;
                }else {
                    this.$children[1].id_prefecture = "";
                }
                this.deleteActive = true;


            },
            closeDelete(){
                this.deleteActive = false;
            },
            getPrefectures(page_url){
                let vm = this;
                page_url = page_url || '/gestionprefectures/'+this.search;

                axios.get(page_url)
                    .then(response => {
                        this.prefectures = response.data.prefecturepagination.data;
                        vm.makePagination(response.data.prefecturepagination);
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
            this.getPrefectures();
        },
        watch: {
            messageAlert(){
                setTimeout(this.hideAlert , 3000);
            },
            search(){
                this.getPrefectures();
            }
        }
    }
</script>
