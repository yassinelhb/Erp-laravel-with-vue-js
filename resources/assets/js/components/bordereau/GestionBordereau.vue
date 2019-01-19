<template>
    <div class="container-fluid">
        <div v-if="loadingsend" class="alert-success-top">{{loadingsend}}</div>
        <div v-if="messageAlert" class="alert-success-top">{{messageAlert}}</div>
        <addbordereau :openDetail="createActive" @close="close"></addbordereau>

        <div class="row" v-if="actBordereau">
        <h3 class="section-title">Consulter Les Bordereaux</h3>
           <div class="section-ges">

               <div class="bladsh row">
                   <button class="btn btn-primary " @click="openCreate"> + Ajouter</button>
                   <div class="b-search col-md-3 offset-md-9" >
                       <i class="fa fa-search"></i>
                       <input type="text" v-model="search"/>
                   </div>
               </div>

            <div class="table-responsive">
             <table class="table table-hover">
                <thead>
                <tr>
                    <th>Bordereau</th>
                    <th>Modalité Paiement</th>
                    <th>Date Livraison</th>
                    <th>Date Paiement</th>
                    <th>Besoin</th>
                    <th>Total</th>
                    <th>File</th>
                    <th>Facture</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="bordereau in bordereaux" v-if="bordereaux.length >0">
                    <td  @click="Detail(bordereau)">{{bordereau.nbordereau}}</td>
                    <td  @click="Detail(bordereau)">{{bordereau.modaliteP}}</td>
                    <td  @click="Detail(bordereau)">{{bordereau.besoin.date}}</td>
                    <td  @click="Detail(bordereau)">{{bordereau.datePaiement}}</td>
                    <td  @click="Detail(bordereau)">{{bordereau.besoin.libelle}}</td>
                    <td  @click="Detail(bordereau)">{{bordereau.total}}</td>
                    <td>
                        <a :href="bordereau.file"><img src="img/pdf.png" width="100"/></a>
                    </td>
                    <td>
                        <i class="fa fa-toggle-on" v-if="bordereau.factures.length"></i>
                        <i class="fa fa-toggle-off" v-else @click="addFacture(bordereau)"></i>
                    </td>
                </tr>
                <tr v-if="bordereaux.length == 0">
                    <td colspan="7"><h5 class="text-center">Il n'y a aucun bordereau</h5></td>
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
                    <button class="btn btn-prev" @click="getBordereaux(pagination.prev_page_url)"
                            :disabled="!pagination.prev_page_url">
                        Previous
                    </button>
                </li>
                <li class="page-item " v-for="item in pagination.last_page" >
                    <button class="btn "  :class="{'active':(item === pagination.current_page)}" @click="getBordereaux('http://localhost:8080/gestionbordereau/'+search+'?page='+item)">
                        {{item}}
                    </button>
                </li>
                <li class="page-item ">
                    <button class="btn btn-next" @click="getBordereaux(pagination.next_page_url)"
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
        data() {
            return {
                bordereaux : {},
                createActive : false,
                actBordereau : true,
                pagination: {},
                loadingsend : "",
                messageAlert : "",
                search: "",
                createFact : false,
            }
        },
        methods: {

            hideAlert(){
                this.messageAlert = "";
            },
            openCreate() {
                this.actBordereau = false;
                this.createActive = true;
                this.$children[0].bordereau = {
                    datePaiement : "",
                    modaliteP : "Chèque",
                    observation : "",
                    besoin : "",
                    detailbesoin : "",
                    total : 0
                }
                this.$children[0].editbord = false;
                this.$children[0].errors = ""


            },
            close(){
                this.actBordereau = true;
                this.createActive = false;
            },
            Detail(bordereau){
               if (!bordereau.factures.length){
                 this.actBordereau = false;
                 this.createActive = true;
                 this.$children[0].bordereau = {
                    id : bordereau.id,
                    datePaiement : bordereau.datePaiement,
                    modaliteP : bordereau.modaliteP,
                    observation : bordereau.observation,
                    besoin : bordereau.besoin,
                    detailbesoin : "",
                    total : 0
                 };
                 this.$children[0].getBesoins();
                 this.$children[0].besoin = bordereau.besoin;
                 this.$children[0].editbord = true;
                 this.$children[0].errors = "";
               }


            },
            addFacture(bordereau){
                this.loadingsend = "loading ...";
                axios.post('/gestionfacturelivraisons',bordereau).then((response)=>{
                if(response.data.etat){
                    this.getBordereaux();
                    this.loadingsend =  "";
                    this.messageAlert ="Facture à ete ajouter avec success ...";
                  }
                })
                    .catch((error) => {
                        this.$parent.loadingsend ="";
                        this.errors = error.response.data.errors;

                    })
            },

            getBordereaux(page_url){
                let vm = this;
                page_url = page_url || '/gestionbordereau/'+this.search;

                axios.get(page_url)
                    .then(response => {
                        this.bordereaux = response.data.bordereaux.data;
                        vm.makePagination(response.data.bordereaux);
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
            this.getBordereaux();

        },
        watch: {
            messageAlert(){
                setTimeout(this.hideAlert , 3000);
            },
            search(){
                this.getBordereaux();
            }
        }
    }
</script>
