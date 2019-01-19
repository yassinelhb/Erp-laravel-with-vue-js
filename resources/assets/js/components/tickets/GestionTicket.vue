<template>
    <div class="section-ges">

        <div v-if="loadingsend" class="alert-success-top">{{loadingsend}}</div>
        <div v-if="messageAlert" class="alert-success-top">{{messageAlert}}</div>
        <div class="bladsh">
            <button class="btn btn-primary " @click="openCreate"> + Ajouter</button>
            <div class="b-select-search col-md-5 offset-md-7 row">
                <div class="b-select col-md-5">
                    <select v-model="filtersearch">
                        <option value="">Association</option>
                        <option v-for="av in avs" :value="av.code+'/'">{{av.code}}, {{av.libelle}}</option>
                    </select>
                </div>
                <div class="b-search col-md-7">
                  <i class="fa fa-search"></i>
                  <input type="text" v-model="search"/>
                </div>
            </div>
        </div>

        <addticket :openCard="createActive" @closeCard="closeCreate"></addticket>
        <deleteticket :openCard="deleteActive" @closeCard="closeDelete"></deleteticket>

        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>N°Ticket</th>
                    <th>Date P1</th>
                    <th>Date P2</th>
                    <th>Poids</th>
                    <th>Véhicule</th>
                    <th>Association Villegoise</th>
                    <th>File</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="ticket in tickets" v-if="tickets.length >0">
                    <td>{{ticket.nticket}}</td>
                    <td>{{ticket.dateP1}}</td>
                    <td>{{ticket.dateP2}}</td>
                    <td>{{ticket.poidsP2 - ticket.poidsP1}} Kg</td>
                    <td>{{ticket.vehicule}}</td>
                    <td>{{ticket.av.code}}, {{ ticket.av.libelle}}</td>
                    <td><a :href="ticket.file"><img src="img/pdf.png" width="100"/></a></td>
                    <td>
                        <button class="btn btn-primary" @click="EditTicket(ticket)">Modifier</button>
                        <i class="fas fa-spinner fa-spin " v-if="loading_id == ticket.id"></i>
                        <button class="btn btn-danger" @click="DeleteTicket(ticket.id)"  v-else>Supprimer</button>
                    </td>
                </tr>
                <tr v-if="tickets.length == 0">
                    <td colspan="7"><h5 class="text-center">Il n'y a aucun ticket</h5></td>
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
                <button class="btn btn-prev" @click="getTickets(pagination.prev_page_url)"
                        :disabled="!pagination.prev_page_url">
                    Previous
                </button>
            </li>
            <li class="page-item " v-for="item in pagination.last_page" >
                <button class="btn "  :class="{'active':(item === pagination.current_page)}" @click="getTickets('http://localhost:8080/gestiontickets/'+filtersearch+search+'?page='+item)">
                    {{item}}
                </button>
            </li>
            <li class="page-item ">
                <button class="btn btn-next" @click="getTickets(pagination.next_page_url)"
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
                tickets : {},
                createActive : false,
                deleteActive : false,
                loading_id : -1,
                pagination: {},
                loadingsend : "",
                messageAlert : "",
                search: "",
                avs: {},
                filtersearch: "",
                saison : ""
            }
        },
        methods: {
            hideAlert(){
                this.messageAlert = "";
            },
            openCreate() {
                this.$children[0].ticket = {
                    saison: this.saison.id,
                    caisse: "",
                    dateP1: "",
                    dateP2 : "",
                    poidsP1 : "",
                    poidsP2 : "",
                    peseur : "",
                    vehicule : "",
                    av : "",
                    observation: ""
                };
                this.$children[0].editticket = false;
                this.$children[0].errors = "";
                this.createActive = true;

            },
            closeCreate(){
                this.createActive = false;
            },
            EditTicket(ticket) {
                this.$children[0].ticket = {
                    id:ticket.id,
                    saison: ticket.saison.id,
                    caisse: ticket.caisse,
                    dateP1: ticket.dateP1,
                    dateP2 : ticket.dateP2,
                    poidsP1 : ticket.poidsP1,
                    poidsP2 : ticket.poidsP2,
                    peseur : ticket.peseur,
                    vehicule : ticket.vehicule,
                    av : ticket.av.id,
                    observation: ticket.observation
                };
                this.$children[0].editticket = true;
                this.$children[0].errors = "";
                this.createActive = true;
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 190;
            },
            DeleteTicket(key) {
                this.$children[1].id_ticket = key;
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
            getTickets(page_url){
                let vm = this;
                page_url = page_url || '/gestiontickets/'+this.filtersearch+this.search;

                axios.get(page_url)
                    .then(response => {
                        this.tickets = response.data.ticketspagination.data;
                        vm.makePagination(response.data.ticketspagination);
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
            getAvs(){
                axios.get('/gestionavs/')
                    .then(response => {
                        this.avs = response.data.avs;
                    }).catch(error => {
                    console.log(error);
                })
            },
        },
        mounted() {
            this.getTickets();
            this.getAvs();
            this.getSaison()
        },
        watch: {
            messageAlert(){
                setTimeout(this.hideAlert , 3000);
            },
            search(){
                this.getTickets();
            },
            filtersearch(){
                this.getTickets();
            },

         }
    }
</script>
