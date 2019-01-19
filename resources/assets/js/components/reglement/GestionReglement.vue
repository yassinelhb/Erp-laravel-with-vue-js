<template>
    <div class="container-fluid">
        <div v-if="loadingsend" class="alert-success-top">{{loadingsend}}</div>
        <div v-if="messageAlert" class="alert-success-top">{{messageAlert}}</div>
        <addreglement :openDetail="createActive" @close="close"></addreglement>
        <deletereglement :openCard="deleteActive" @closeCard="closeDelete"></deletereglement>


        <div class="row" v-if="actBordereau">
        <h3 class="section-title">Consulter Les Bordereaux Réglement</h3>
           <div class="section-ges">

               <div class="bladsh row">
                   <button class="btn btn-primary " @click="openCreate"> + Ajouter</button>
                   <div class="b-search col-md-3 offset-md-9" >
                       <i class="fa fa-search"></i>
                       <input type="text" v-model="search"/>
                   </div>
               </div>

            <div class="table-responsive">
             <table class="table">
                <thead>
                <tr>
                    <th>Bordereau Réglement</th>
                    <th>Facture</th>
                    <th>Modalité Paiement</th>
                    <th>Date Réglement</th>
                    <th>Montant Payer</th>
                    <th>Reste</th>
                    <th>File</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="bordereau in bordereaux" v-if="bordereaux.length >0">
                    <td>{{bordereau.reglement}}</td>
                    <td>{{bordereau.facturelivraison.nfacture}}</td>
                    <td>{{bordereau.modaliteP}}</td>
                    <td>{{bordereau.datereglement}}</td>
                    <td>{{bordereau.montantP}}</td>
                    <td>{{ bordereau.montantreglement - bordereau.montantP}}</td>
                    <td>
                        <a :href="bordereau.file"><img src="img/pdf.png" width="100"/></a>
                    </td>
                    <td>
                        <button class="btn btn-primary" @click="EditBordereau(bordereau)"  >Modifier</button>
                        <i class="fas fa-spinner fa-spin " v-if="loading_id == bordereau.id"></i>
                        <button class="btn btn-danger" @click="DeleteBordereau(bordereau)" v-else>Supprimer</button>
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
                    <button class="btn "  :class="{'active':(item === pagination.current_page)}" @click="getBordereaux('http://localhost:8080/gestionreglement/'+search+'?page='+item)">
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
        <div class="modal fade  show" id="exampleModalCenter" style="display : block;background: rgba(0,0,0,.3)" v-if="alertEdit">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Modifier</h5>
                        <button type="button" class="close" @click="closeEdit" >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p> Vous pouvez pas modifier cet Réglement </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="closeEdit">OK</button>
                    </div>
                </div>
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
                deleteActive : false,
                pagination: {},
                loadingsend : "",
                messageAlert : "",
                search: "",
                loading_id : -1,
                alertEdit : false
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
                    facture : "",
                    montantreglement : "",
                    montantP : 0,
                    modaliteP : "Chèque",
                    datereglement : "",
                }
                this.$children[0].editbord = false;
                this.$children[0].errors = ""


            },
            close(){
                this.actBordereau = true;
                this.createActive = false;
            },
            closeEdit(){
                this.alertEdit = false;
            },
            EditBordereau(bordereau){
                axios.get('/gestionreglement/'+bordereau.facturelivraison_id)
                    .then(response => {
                        if(response.data.bordereau.id == bordereau.id) {
                            this.actBordereau = false;
                            this.createActive = true;
                            this.$children[0].bordereau = {
                                id: bordereau.id,
                                montantreglement: bordereau.montantreglement,
                                montantP: bordereau.montantP,
                                modaliteP: bordereau.modaliteP,
                                datereglement: bordereau.datereglement,
                                facture: bordereau.facturelivraison,
                            };
                            this.$children[0].getFactures();
                            this.$children[0].facture = bordereau.facturelivraison;
                            this.$children[0].editbord = true;
                            this.$children[0].errors = "";
                        }else{
                            this.alertEdit = true;
                            }
                        }).catch(error => {
                    console.log(error);
                })
            },
            DeleteBordereau(bordereau) {
                axios.get('/gestionreglement/'+bordereau.facturelivraison_id)
                    .then(response => {
                        if(response.data.bordereau.id == bordereau.id){
                            console.log(response.data.bordereau)
                            this.$children[1].id_bord = bordereau.id;
                            this.deleteActive = true;
                        }else {
                            this.$children[1].id_bord = '';
                            this.deleteActive = true;
                        }
                    }).catch(error => {
                    console.log(error);
                })
            },
            closeDelete(){
                this.deleteActive = false;
            },

            getBordereaux(page_url){
                let vm = this;
                page_url = page_url || '/gestionreglement/'+this.search;

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
