<template>
    <div class="chat-navbar-wrapper">
      <div class="contactChat" v-if="actContact">
        <div class="header-chat">
         <input type="text" class="input-search-friend"  v-model="search" placeholder="search .."/>
        </div>
        <div class="body-chat">
            <h1 class="title-f">Online Friends</h1>

            <ul class="list-group" >
                <li v-for="user in usersOnline" v-if="user.id != userid " @click="Conversation(user)">
                    <div class="row">
                        <div class="col-2">
                            <img :src="'images/'+user.image" class="img-user"/>
                        </div>
                        <div class="col-8">
                            <h5 class="user-title" >{{user.nom}}</h5>
                            <span class="icon-message" v-if="countChat(user)">{{countChat(user)}}</span>
                            <span class="icon-online" v-else></span>
                        </div>
                    </div>
                </li>
                <li v-if=" usersOnline.length === 1">
                    <h5 class="user-title">Aucun utilisateur connécte</h5>
                </li>


            </ul>

            <h1 class="title-f">More Friends</h1>

            <ul class="list-group" >
                <li v-for="user in users" v-if="user.id != userid " @click="Conversation(user)">
                    <div class="row">
                        <div class="col-2">
                            <img :src="'images/'+user.image" class="img-user"/>
                        </div>
                        <div class="col-8">
                            <h5 class="user-title" >{{user.nom}}</h5>
                            <span class="icon-message" v-if="countChat(user)">{{countChat(user)}}</span>
                            <span class="icon-online" v-else-if="checkOnlineUsers(user)"></span>
                            <span class="icon-hors-line" v-else></span>
                        </div>
                    </div>
                </li>

                <li v-if=" users.length === 0">
                    <h5 class="user-title">Aucun utilisateur trouve</h5>
                </li>

            </ul>
        </div>
      </div>
      <conversation :openConversation="actConversation" :userid="userid" :contact="contact" @close="closeConversation"></conversation>
        <div class="alert-notify-message row" v-if="alertmessage" @click="Conversation(alertmessage)">
            <div class="col-sm-5">
                <img :src="'images/'+alertmessage.image" >
            </div>
            <div class="col-sm-7">
                <i class="fa fa-close" @click="hideAlert"></i>
                <h2>{{alertmessage.nom }}</h2>
                <span>Envoi un message ...</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['userid'],
        data() {
            return {
                users : {},
                usersOnline : {},
                chats : {},
                search : "",
                contact : "",
                actContact : true,
                actConversation : false,
                alertmessage : ""

            }
        },
        methods: {
            getUsers(){
                axios.get('/gestionusers/'+this.search)
                    .then(response => {
                        this.users = response.data.users;
                    }).catch(error => {
                    console.log(error);
                })
            },
            getChats() {

                axios.get('/chats').then(response => {


                    this.chats = response.data;
                       if(this.chats.length > 0) {
                           document.getElementById("icon-msg").style.visibility='visible';
                           document.getElementById("msg-icon").style.visibility='visible';
                       }else{
                           document.getElementById("icon-msg").style.visibility='hidden';
                           document.getElementById("msg-icon").style.visibility='hidden';


                       }
                }).catch(error => {
                    console.log(error);
                })

            },
            checkOnlineUsers(user){
                return _.find(this.usersOnline, (u) => u.id === user.id);
            },
            countChat(user){
               var filter  ;
               filter = _.filter(this.chats, (u) => (u.user_id == user.id && u.contact_id == this.userid ));
               return filter.length;
            },
            Conversation(user){
                this.Read(user);
                this.getChats();
                this.$children[0].typing = false;
                this.actContact = false ;
                this.contact = user;
                this.$children[0].connecticon = this.checkOnlineUsers(user)
                this.actConversation = true;
                if(document.getElementsByClassName('chat-navbar showchat').length == 0){
                    $('nav.chat-navbar').toggleClass('showchat');
                    $('.content').toggleClass('mv-right');
                }
                this.hideAlert();
            },
            closeConversation() {
                this.actContact = true ;
                this.actConversation = false;
            },
            hideAlert(){
                this.alertmessage = ""
            },
            Read(user){
                 axios.patch('/chats/'+this.userid+'/'+user.id)
                     .catch((error) =>{
                 console.log(error)
                 })
            },
        },
        mounted() {
            this.getUsers();
            this.getChats();
            Echo.join('Online')
                .here((users) => {
                    this.usersOnline = users ;
                })
                .joining((user)=> {
                    if(this.contact.id === user.id){
                        this.$children[0].connecticon = user
                    }
                    this.usersOnline.push(user);


                })
                .leaving((user)=> {
                    if(this.contact.id === user.id){
                        this.$children[0].connecticon = false
                    }
                    this.usersOnline = this.usersOnline.filter((u) => {
                        return user != u ;
                    })


                });

                Echo.private('Chat')
                    .listen('ChatEvent', (e) => {
                        if(this.userid == e.chat.contact_id ){
                            this.getChats();
                            document.getElementById('Audio').play();
                        }
                        if(this.userid == e.chat.contact_id && this.contact.id != e.chat.user_id) {
                            this.alertmessage = e.user

                        }
                        if(this.userid == e.chat.contact_id && this.contact.id == e.chat.user_id){
                            this.$children[0].chats.push(e.chat)
                        }
                    })
                    .listenForWhisper('typing', (e) => {
                        if(this.userid == e.contact_id && this.contact.id == e.user_id) {
                            if(e.name.length > 0){
                                this.$children[0].typing = true;
                            }else{
                                this.$children[0].typing = false;
                            }
                        }
                    });


        },
        watch:{
            search(){
                this.getUsers();
            },
            alertmessage(){
                setTimeout(this.hideAlert , 10000);
            },
        }
    }
</script>
