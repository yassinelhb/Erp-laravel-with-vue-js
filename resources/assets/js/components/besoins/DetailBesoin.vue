<template>
    <div class="row details-container" v-if="openDetail">
        <h3 class="section-title"><img src="img/return.png" @click="closeDetail"/> </h3>
        <div class="card-details col-md-4">
            <h2>{{besoin.libelle}}</h2>
            <p><img src="img/date.png"/> {{besoin.date}}</p>
            <p><img src="img/season.png"/> {{besoin.saison.compagne}}</p>
            <p><img src="img/farm.png"/> {{besoin.av.code}}, {{besoin.av.libelle}}</p>
        </div>
        <div class="section-ges">

            <div v-if="loadingsend" class="alert-success-top">{{loadingsend}}</div>
            <div v-if="messageAlert" class="alert-success-top">{{messageAlert}}</div>
            <div class="bladsh row ">
                <button class="btn btn-primary " @click="openCreate" > + Ajouter</button>
                <div class="b-search col-md-3 offset-md-9" >
                    <i class="fa fa-search"></i>
                    <input type="text" v-model="search"/>
                </div>
            </div>

            <addarticlebesoin :openCard="createActive" :besoin="besoin" @closeCard="closeCreate"></addarticlebesoin>
            <deletearticlebesoin :openCard="deleteActive" @closeCard="closeDelete"></deletearticlebesoin>

            <div class="table-responsive">
             <table class="table table-hover">
                <thead>
                <tr>
                    <th>Id</th>
                    <th >Article</th>
                    <th>Libelle</th>
                    <th>Quantite</th>
                    <th>Date creation</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="articlebesoin in articlebesoins" >
                    <td>{{articlebesoin.id}}</td>
                    <td>{{articlebesoin.article.code}}</td>
                    <td>{{articlebesoin.article.libelle}}</td>
                    <td>{{articlebesoin.quantite}}</td>
                    <td>{{articlebesoin.created_at}}</td>
                    <td>
                        <button class="btn btn-primary" @click="EditArticlebesoin(articlebesoin)">Modifier</button>
                        <i class="fas fa-spinner fa-spin " v-if="loading_id == articlebesoin.id"></i>
                        <button class="btn btn-danger" @click="DeleteArticlebesoin(articlebesoin.id)"  v-else>Supprimer</button>
                    </td>
                </tr>
               <tr v-if="articlebesoins.length == 0" >
                    <td colspan="7"><h5 class="text-center">Il n'y a aucun article</h5></td>
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
                    <button class="btn btn-prev" @click="getArticlebesoins(pagination.prev_page_url)"
                            :disabled="!pagination.prev_page_url">
                        Previous
                    </button>
                </li>
                <li class="page-item " v-for="item in pagination.last_page" >
                    <button class="btn "  :class="{'active':(item === pagination.current_page)}" @click="getArticlebesoins('http://localhost:8080/gestiondetailbesoin/'+search+'?page='+item)">
                        {{item}}
                    </button>
                </li>
                <li class="page-item ">
                    <button class="btn btn-next" @click="getArticlebesoins(pagination.next_page_url)"
                            :disabled="!pagination.next_page_url">Next
                    </button>
                </li>

            </ul>


        </div>
    </div>
</template>

<script>
    export default {
        props: ['openDetail','besoin'],
        data() {
            return {
                articlebesoins : {},
                createActive : false,
                deleteActive : false,
                loading_id : -1,
                pagination: {},
                loadingsend : "",
                messageAlert : "",
                search: "",

            }
        },
        methods: {

            hideAlert(){
                this.messageAlert = "";
            },
            openCreate() {
                this.$children[0].articlebesoin = {
                    besoin: this.besoin.id,
                    article : '',
                    quantite: '',
                    saison : this.besoin.saison.id
                };
                this.$children[0].editarticlebesoin = false;
                this.$children[0].errors = "";
                this.createActive = true ;

            },
            closeCreate(){
                this.createActive = false;
            },
            EditArticlebesoin(articlebesoin) {
                this.$children[0].articlebesoin = {
                        id: articlebesoin.id,
                        besoin: articlebesoin.besoin.id,
                        article: articlebesoin.article.id,
                        quantite: articlebesoin.quantite,
                        saison: this.besoin.saison.id
                };
                this.$children[0].editarticlebesoin = true;
                this.$children[0].errors = "";
                this.createActive = true;
            },
            DeleteArticlebesoin(key) {
                this.$children[1].id_articlebesoin = key;
                this.deleteActive = true;


            },
            closeDelete(){
                this.deleteActive = false;
            },
            getArticlebesoins(page_url){
                let vm = this;
                page_url = page_url || '/gestiondetailbesoin/'+this.besoin.id+'/'+this.search;

                axios.get(page_url)
                    .then(response => {
                        this.articlebesoins = response.data.detailbesoin.data;
                        vm.makePagination(response.data.detailbesoin);
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
                this.$emit('closeDetail');
            }
        },

        watch: {
            messageAlert(){
                setTimeout(this.hideAlert , 3000);
            },
            search(){
            this.getArticlebesoins();
            },
            besoin(){
                this.getArticlebesoins();
            }

        }
    }
</script>
