<template>
    <div class="section-ges">
        <div v-if="loadingsend" class="alert-success-top">{{loadingsend}}</div>
        <div v-if="messageAlert" class="alert-success-top">{{messageAlert}}</div>

        <div class="bladsh row">
               <div class="b-search col-md-3 offset-md-9" >
                   <i class="fa fa-search"></i>
                   <input type="text" v-model="search"/>
               </div>
            </div>
            <div class="table-responsive">
              <table class="table">
                <thead>
                <tr>
                    <th>Facture</th>
                    <th>Bordereau</th>
                    <th>Modalité Paiement</th>
                    <th>Date Livraison</th>
                    <th>Date Paiement</th>
                    <th>Total</th>
                    <th>File</th>
                    <th v-if="roleuser == 'Responsable Financier'">Global</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="facture in factures" v-if="factures.length >0">
                    <td>{{facture.nfacture}}</td>
                    <td>{{facture.bordereaulivraison.nbordereau}}</td>
                    <td>{{facture.bordereaulivraison.modaliteP}}</td>
                    <td>{{facture.bordereaulivraison.besoin.date}}</td>
                    <td>{{facture.bordereaulivraison.datePaiement}}</td>
                    <td>{{facture.bordereaulivraison.total}}</td>
                    <td>
                        <a :href="facture.file"><img src="img/pdf.png" width="100"/></a>
                    </td>
                    <td v-if="roleuser == 'Responsable Financier'">
                        <i class="fa fa-toggle-on" v-if="facture.factureglobal"></i>
                        <i class="fa fa-toggle-off" v-else @click="createFactureGlobal(facture)"></i>
                    </td>

                </tr>
                <tr v-if="factures.length == 0">
                    <td colspan="7"><h5 class="text-center">Il n'y a aucun facture</h5></td>
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
                    <button class="btn btn-prev" @click="getFactures(pagination.prev_page_url)"
                            :disabled="!pagination.prev_page_url">
                        Previous
                    </button>
                </li>
                <li class="page-item " v-for="item in pagination.last_page" >
                <li class="page-item " v-for="item in pagination.last_page" >
                    <button class="btn "  :class="{'active':(item === pagination.current_page)}" @click="getFactures('http://localhost:8080/gestionfacturelivraisons/'+search+'?page='+item)">
                        {{item}}
                    </button>
                </li>
                <li class="page-item ">
                    <button class="btn btn-next" @click="getFactures(pagination.next_page_url)"
                            :disabled="!pagination.next_page_url">Next
                    </button>
                </li>

            </ul>


     </div>
</template>

<script>
    export default {
        props: ['roleuser'],
        data() {
            return {
                factures : {},
                pagination: {},
                search: "",
                messageAlert : "",
                loadingsend : ""
            }
        },
        methods: {
            hideAlert(){
                this.messageAlert = "";
            },
            createFactureGlobal(facture){
                this.loadingsend ="loading ...";
                axios.post('/gestionfactureglobal',facture).then((response)=>{
                    if(response.data.etat){
                        this.getFactures();
                        this.loadingsend ="";
                        this.messageAlert ="Facture global à été ajouter avec success ...";
                    }
                }).catch((error) => {
                        this.loadingsend ="";
                        this.errors = error.response.data.errors;

                    })
            },
            getFactures(page_url){
                let vm = this;
                page_url = page_url || '/gestionfacturelivraisons/'+this.search;

                axios.get(page_url)
                    .then(response => {
                        this.factures = response.data.facturespagination.data;
                        vm.makePagination(response.data.facturespagination);
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
            this.getFactures();

        },
        watch: {
            messageAlert(){
                setTimeout(this.hideAlert , 3000);
            },
            search(){
                this.getFactures();
            }
        }
    }
</script>
