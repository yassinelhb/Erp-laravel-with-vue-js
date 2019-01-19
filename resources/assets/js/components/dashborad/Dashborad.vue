<template>
   <div class="section">
    <section class="dashboard-counts section-padding">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-4" v-if="userauth.role == 'Administrateur'">
                   <div class="alert alert-light">
                       <h2>Utilisateurs</h2>
                       <img src="img/chart.png"/>
                       <span>{{countusers}}</span>
                   </div>
                </div>
                <div class="col-xl-4" v-if="userauth.role == 'Administrateur'">
                    <div class="alert alert-light">
                        <h2>Articles</h2>
                        <img src="img/chart.png"/>
                        <span>{{countarticles}}</span>
                    </div>
                </div>
                <div class="col-xl-4" v-if="userauth.role == 'Administrateur'">
                    <div class="alert alert-light">
                        <h2>Association Villegoise</h2>
                        <img src="img/chart.png"/>
                        <span>{{countavs}}</span>
                    </div>
                </div>
                <div class="col-xl-4" v-if="userauth.role == 'Cozoc'">
                    <div class="alert alert-light">
                        <h2>Besoins</h2>
                        <img src="img/chart.png"/>
                        <span>{{countbesoins}}</span>
                    </div>
                </div>
                <div class="col-xl-4" v-if="userauth.role == 'Responsable CGI'">
                    <div class="alert alert-light">
                        <h2>Bordereaux</h2>
                        <img src="img/chart.png"/>
                        <span>{{countbordereau}}</span>
                    </div>
                </div>
                <div class="col-xl-4" v-if="userauth.role == 'Responsable CGI'">
                    <div class="alert alert-light">
                        <h2>Factures</h2>
                        <img src="img/chart.png"/>
                        <span>{{countfacture}}</span>
                    </div>
                </div>
                <div class="col-xl-4" v-if="userauth.role == 'Responsable Financier'">
                    <div class="alert alert-light">
                        <h2>Bordereaux Réglement</h2>
                        <img src="img/chart.png"/>
                        <span>{{countreglement}}</span>
                    </div>
                </div>
                <div class="col-xl-4" v-if="userauth.role == 'Responsable Financier'">
                    <div class="alert alert-light">
                        <h2>Factures Global</h2>
                        <img src="img/chart.png"/>
                        <span>{{countglobal}}</span>
                    </div>
                </div>
                <div class="col-xl-4" v-if="userauth.role == 'Gestionnaire de pont Bascule'">
                    <div class="alert alert-light">
                        <h2>Tickets</h2>
                        <img src="img/chart.png"/>
                        <span>{{countTicket}}</span>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- Header Section-->
    <section class="dashboard-header section-padding" v-if="userauth.role == 'Administrateur'">
        <div class="container-fluid">
            <div class="row d-flex align-items-md-stretch">
                <div class="col-lg-4">
                    <div class="card sales-report">
                        <h2 class="display h4">Association</h2>
                        <piecharts></piecharts>
                    </div>
                </div>
                <div class="col-lg-8">
                    <h2 class="display h4">Besoin</h2>
                    <linecharts></linecharts>
                </div>
            </div>
        </div>
    </section>
    <!-- Statistics Section-->
    <section class="statistics">
        <div class="container-fluid">
            <div class="row d-flex">
                <div class="col-lg-4">
                    <!-- Daily Feed Widget-->
                    <div id="daily-feeds" class="card updates daily-feeds">
                        <div id="feeds-header" class="card-header d-flex justify-content-between align-items-center">
                            <h2 class="h5 display">Utilisateurs</h2>

                        </div>
                        <div id="feeds-box" role="tabpanel" class="collapse show">
                            <div class="feed-box">
                                <ul class="feed-elements list-unstyled">
                                    <!-- List-->
                                    <li class="clearfix" v-for="user in users" v-if="user.id != userauth.id ">
                                        <div class="feed d-flex justify-content-between">
                                            <div class="feed-body d-flex justify-content-between"><a href="#" class="feed-profile"><img :src="'images/'+user.image" alt="person" class="img-fluid rounded-circle"></a>
                                                <div class="content"><strong>{{user.nom}}</strong><small>{{user.role}}</small>
                                                    <div class="full-connect">
                                                        <small  v-if="checkOnlineUsers(user)"><i class="fa fa-circle text-primary"></i> Online</small>
                                                        <small v-else><i class="fa fa-circle text-danger"></i> Hors ligne</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Daily Feed Widget End-->
                </div>

                <div class="col-lg-4">

                    <datepicker :value="new Date()"  inline="true" ></datepicker>
                </div>

                <div class="col-lg-4">
                    <div class="card data-usage" style="background-color: #0aa699">
                        <weather
                                api-key="0fe215033acb613844a864e9c34bbbb8"
                                latitude="14.786426"
                                longitude="19.397332"
                                language="fr"
                                text-color="#fff"
                                hide-header= "true"
                                units="uk">
                        </weather>
                    </div>
                </div>
            </div>
        </div>
    </section>

   </div>

</template>

<script>
    export default {
        props: ['userauth'],
        data(){
            return {
                users : {},
                usersOnline : {},
                countusers : "",
                countarticles : "",
                countavs : "",
                countbesoins : "",
                countreglement : "",
                countglobal : "",
                countbordereau : "",
                countfacture : "",
                countTicket : "",


            }
        },
        methods: {
            checkOnlineUsers(user){
                return _.find(this.usersOnline, (u) => u.id === user.id);
            },
            getUsers(){
                axios.get('/gestionusers/')
                    .then(response => {
                        this.users = response.data.users;
                        this.countusers = response.data.users.length -1;
                    }).catch(error => {
                    console.log(error);
                })
            },

            getArticles(){
                axios.get('/gestionarticles/')
                    .then(response => {
                        this.countarticles = response.data.articles.length;
                    }).catch(error => {
                    console.log(error);
                })
            },

            getAvs(){
                axios.get('/gestionavs/')
                    .then(response => {
                        this.countavs = response.data.avs.length;
                    }).catch(error => {
                    console.log(error);
                })
            },
            getBesoins(){
                axios.get('/gestionbesoins/')
                    .then(response => {
                        this.countbesoins = response.data.besoins.length;
                    }).catch(error => {
                    console.log(error);
                })
            },
            getBordereau(){
                axios.get('/gestionbordereau/')
                    .then(response => {
                        this.countbordereau = response.data.bordereaux.data.length;
                    }).catch(error => {
                    console.log(error);
                })
            },
            getFacture(){
                axios.get('/gestionfacturelivraisons/')
                    .then(response => {
                        this.countfacture = response.data.factures.length;
                    }).catch(error => {
                    console.log(error);
                })
            },
            getRegelement(){
                axios.get('/gestionreglement/')
                    .then(response => {
                        this.countreglement = response.data.bordereaux.data.length;
                    }).catch(error => {
                    console.log(error);
                })
            },
            getGlobal(){
                axios.get('/gestionfactureglobal/')
                    .then(response => {
                        this.countglobal = response.data.factures.data.length;
                    }).catch(error => {
                    console.log(error);
                })
            },
            getTickets(){
                axios.get('/gestiontickets/')
                    .then(response => {
                        this.countTicket = response.data.ticketspagination.data.length;
                    }).catch(error => {
                    console.log(error);
                })
            },
        },
        mounted(){
            this.getUsers();
            this.getArticles();
            this.getAvs();
            this.getBesoins();
            this.getBordereau();
            this.getFacture();
            this.getRegelement();
            this.getGlobal();
            this.getTickets();
            Echo.join('Online')
                .here((users) => {
                    this.usersOnline = users ;
                })
                .joining((user)=> {
                    this.usersOnline.push(user);


                })
                .leaving((user)=> {
                    this.usersOnline = this.usersOnline.filter((u) => {
                        return user != u ;
                    })


                });
        }



    }
</script>
