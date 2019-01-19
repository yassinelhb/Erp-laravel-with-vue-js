<template>
    <div class="section-ges">

        <div v-if="loadingsend" class="alert-success-top">{{loadingsend}}</div>
        <div v-if="messageAlert" class="alert-success-top">{{messageAlert}}</div>

        <div class="select-top" v-if="user.role == 'Administrateur'">

            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Export
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="articles/exports/xls">Export xls</a>
                    <a class="dropdown-item" href="articles/exports/xlsx">Export xlsx</a>
                    <a class="dropdown-item" href="articles/exports/csv">Export csv</a>
                </div>
            </div>

            <select v-model="saison">
                <option v-for="saison in saisons" :value="saison.id">{{saison.compagne}}</option>
            </select>

        </div>
        <div class="bladsh row ">
         <button class="btn btn-primary " @click="openCreate" v-if="user.role == 'Administrateur'"> + Ajouter</button>
         <div class="b-search col-md-3 offset-md-9" >
           <i class="fa fa-search"></i>
           <input type="text" v-model="search"/>
         </div>
        </div>

        <addarticle :openCard="createActive" @closeCard="close"></addarticle>
        <deletearticle :openCard="deleteActive" @closeCard="close"></deletearticle>
        <div class="row">
            <div class="col-md-4" v-for="article in articles">
              <div class="card">
                <div class="card-body row">
                    <div class="ct-header col-sm-4">
                        <img class="img-user-tab" :src="'images/product/'+article.image"/>
                    </div>
                    <div class="ct-body col-sm-8 ">
                        <h5>{{article.libelle}}</h5>
                        <p>{{article.code}}</p>
                        <p>Quantité Disponible : {{article.qtedispo}}</p>
                        <div v-if="article.saisons.length == 0 || article.saisons[0].pivot.prixvente == 0 ">
                            <p class="text-dark" v-if="user.role == 'Administrateur'">Saisir le Prix de vente de cette saison.</p>
                            <div v-else>
                             <p>Prix Vente : 0 </p>
                             <p>Tva : 0 %</p>
                            </div>
                        </div>
                        <div v-else>
                            <p>Prix Vente : {{article.saisons[0].pivot.prixvente}}  </p>
                            <p>Tva : {{article.saisons[0].pivot.tva}} %</p>
                        </div>
                        <div v-if="user.role == 'Administrateur'">
                            <button class="btn btn-primary" @click="EditArticle(article)" >Modifier</button>
                            <i class="fas fa-spinner fa-spin " v-if="loading_id == article.id"></i>
                            <button class="btn btn-danger" @click="DeleteArticle(article.id)" v-else>Supprimer</button>
                        </div>
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
                <button class="btn btn-prev" @click="getArticles(pagination.prev_page_url)"
                        :disabled="!pagination.prev_page_url">
                    Previous
                </button>
            </li>
            <li class="page-item " v-for="item in pagination.last_page" >
                <button class="btn "  :class="{'active':(item === pagination.current_page)}" @click="getArticles('http://localhost:8080/gestionarticles/'+saison+'/'+search+'?page='+item)">
                    {{item}}
                </button>
            </li>
            <li class="page-item ">
                <button class="btn btn-next" @click="getArticles(pagination.next_page_url)"
                        :disabled="!pagination.next_page_url">Next
                </button>
            </li>

        </ul>

    </div>
</template>

<script>
    export default {
        props: ['user'],
        data() {
            return {
                articles : {},
                saisons : {},
                createActive : false,
                deleteActive : false,
                prixActive : false,
                loading_id : -1,
                pagination: {},
                loadingsend : "",
                messageAlert : "",
                search: "",
                saison: "",
            }
        },
        methods: {

            hideAlert(){
                this.messageAlert = "";
            },
            openCreate() {
                this.$children[0].article = {
                  id: 0,
                  code: "",
                  libelle: "",
                  quantite: "",
                  unitevente: "Kg",
                  image: "",
                };
                this.$children[0].editarticle = false;
                this.$children[0].avatar = "";
                this.$children[0].errors = "";
                this.createActive = true ;

            },

            EditArticle(article) {
                if(article.saisons.length> 0 ){
                    var prixvente = article.saisons[0].pivot.prixvente;
                    var tva = article.saisons[0].pivot.tva;
                    var articlesaisonid = article.saisons[0].pivot.id;
                }else {
                    var prixvente = 0;
                    var tva = 0;
                    var articlesaisonid = "";
                }
                this.$children[0].article = {
                    id: article.id,
                    code: article.code,
                    libelle: article.libelle,
                    quantite: article.qtedispo,
                    unitevente: article.unitevente,
                    articlesaisonid : articlesaisonid,
                    prixvente: prixvente,
                    tva: tva,
                    image: article.image,
                    saison: this.saison
                };

                this.$children[0].editarticle = true;
                this.$children[0].avatar = "";
                this.$children[0].errors = "";
                this.createActive = true;
            },
            DeleteArticle(key) {
                this.$children[1].id_article = key;
                this.deleteActive = true;


            },
            close(){
                this.createActive = false;
                this.deleteActive = false;
                this.prixActive = false
            },
            getSaisons(){
                axios.get('/gestionsaisons')
                    .then(response => {
                        if(this.user.role=='Administrateur'){
                            this.saisons = response.data.saison;
                            this.saison = this.saisons[0].id;
                        }else{
                            this.saison = response.data.thisaison.id
                        }

                    }).catch(error => {
                    console.log(error);
                })
            },
            getArticles(page_url){
                let vm = this;
                page_url = page_url || '/gestionarticles/'+this.saison+'/'+this.search;
                axios.get(page_url)
                    .then(response => {
                        this.articles = response.data.articlespagination.data;
                        vm.makePagination(response.data.articlespagination);
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
            this.getSaisons();
        },
        watch: {
            messageAlert(){
                setTimeout(this.hideAlert , 3000);
            },
            saison(){
                this.getArticles();
            },
            search(){
                this.getArticles();
            }
        }
    }
</script>
