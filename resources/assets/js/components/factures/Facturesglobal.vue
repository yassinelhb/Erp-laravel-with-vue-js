<template>
    <div class="section-ges">

          <deletefacturesglobal :openCard="deleteActive" @closeCard="closeDelete"></deletefacturesglobal>

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
                    <th>Facture Livraison</th>
                    <th>Date Creation</th>
                    <th>Crédit</th>
                    <th>Total</th>
                    <th>File</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="facture in factures" v-if="factures.length >0">
                    <td>{{facture.nglobal}}</td>
                    <td>{{facture.facturelivraison.nfacture}}</td>
                    <td>{{facture.created_at}}</td>
                    <td v-if="facture.facturelivraison.reglements.length">
                         {{facture.facturelivraison.reglements[facture.facturelivraison.reglements.length-1].montantreglement-
                         facture.facturelivraison.reglements[facture.facturelivraison.reglements.length-1].montantP}}</td>
                    <td v-else>0</td>
                    <td>{{facture.facturelivraison.bordereaulivraison.total}}</td>
                    <td>
                        <a :href="facture.file"><img src="img/pdf.png" width="100"/></a>
                    </td>
                    <td>
                        <i class="fas fa-spinner fa-spin " v-if="loading_id == facture.id"></i>
                        <button class="btn btn-danger" @click="DeleteFacture(facture)" v-else>Supprimer</button>

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
                loading_id : 0,
                deleteActive : false
            }
        },
        methods: {
            DeleteFacture(facture) {
                this.$children[0].id_facture = facture.id;
                this.deleteActive = true;
            },
            closeDelete(){
                this.deleteActive = false;
            },
            getFactures(page_url){
                let vm = this;
                page_url = page_url || '/gestionfactureglobal/'+this.search;

                axios.get(page_url)
                    .then(response => {
                        this.factures = response.data.factures.data;
                        vm.makePagination(response.data.factures);
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
            search(){
                this.getFactures();
            }
        }
    }
</script>
