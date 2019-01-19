<template>
    <div class="contactChat" v-if="openConversation">
        <div class="header-chat">
            <h3 class="chat-title">
                <span class="icon-online" v-if="connecticon"></span>
                <span class="icon-horsline" v-else></span>
                {{contact.nom}}
            </h3>
            <i class="fa fa-close" @click="Close"></i>
        </div>
        <div class="body-chat">
            <ul class="list-group list-msg"  v-chat-scroll>

                <li v-for="chat in chats" v-if="userid != chat.user_id">
                    <div class="row">
                        <div class="col-2">
                            <img :src="'images/'+contact.image" class="img-user"/>
                        </div>
                        <div class="col-10">
                            <span class="message-contact-like" @click="showtime(chat.id)" v-if="checkicon(chat.message)">
                                <img :src="'img/'+checkicon(chat.message)" width="45"/>
                            </span>
                            <span class="message-contact-like" @click="showtime(chat.id)" v-else-if="chat.message == '(Ok)'">
                                <img src="img/handwhite.png" width="45"/>
                            </span>
                            <span class="message-contact-img"  @click="showimg(chat.image)" v-else-if="chat.image">
                                <img :src="'images/chat/'+chat.image" class="msg-img"/>
                            </span>
                            <a class="message-contact" :href="chat.message" @click="showtime(chat.id)"  v-else-if="chat.message.substr(0,8) == 'https://' || chat.message.substr(0,7) == 'http://'" >
                              <small class="arrow"></small>
                                {{  chat.message }}
                            </a>
                            <span class="message-contact" @click="showtime(chat.id)"  v-else>
                              <small class="arrow"></small>
                                {{  chat.message }}
                            </span>
                            <h2 class="cotime"  v-if="time == chat.id">{{chat.created_at}}</h2>
                        </div>
                    </div>
                </li>

                <li v-else-if="userid == chat.user_id  ">
                    <div class="row">
                        <div class="col-12">
                              <span class="my-message-like" @click="showtime(chat.id)" v-if="checkicon(chat.message)">
                                 <img :src="'img/'+checkicon(chat.message)" width="45" />
                              </span>
                              <span class="my-message-like" @click="showtime(chat.id)" v-else-if="chat.message == '(Ok)'">
                                 <img src="img/hand.png" width="45"/>
                              </span>
                              <span class="my-message-img" @click="showimg(chat.image)" v-else-if="chat.image && !checkimage(chat.image)">
                                <img :src="'images/chat/'+chat.image" class="msg-img"/>
                              </span>
                              <span class="my-message-img" @click="showimg(chat.image)" v-else-if="chat.image">
                                <img :src="chat.image" class="msg-img"/>
                              </span>
                              <a class="my-message" :href="chat.message" @click="showtime(chat.id)"  v-else-if="chat.message.substr(0,8) == 'https://' || chat.message.substr(0,7) == 'http://'" >
                                {{  chat.message }}
                              </a>
                              <span class="my-message" @click="showtime(chat.id)" v-else>
                                 {{ chat.message }}
                              </span>

                              <h2 class="mytime"  v-if="time == chat.id">{{chat.created_at}}</h2>
                        </div>
                    </div>
                </li>

                <li v-if="chats.length == 0 ">
                    <h5 class="user-title" >Fait un petit Hello à {{contact.nom }}</h5>
                    <div class="send-msg">
                        <img src="img/iconhello.png" @click="sendHello" />
                    </div>
                </li>
                <div v-if=" this.chats.length> 0 && this.chats[this.chats.length-1].user_id == this.userid" class="iconsuccess">
                    <img :src="'images/'+contact.image" v-if="this.chats[this.chats.length-1].read == 1 " />
                    <i class="fa fa-check-circle-o" v-else-if="check"></i>
                    <i class="fa fa-circle-o" v-else></i>

                </div>

                <li >
                    <div class="row" v-if="typing">
                        <div class="col-2">
                            <img :src="'images/'+contact.image" class="img-user"/>
                        </div>
                        <div class="col-10">
                            <span class="typing">
                                <img src="img/typing.gif">
                            </span>
                        </div>
                    </div>
                </li>

            </ul>
        </div>

        <div class="footer-chat">
            <div class="file-picture">
                <input type="file" @change="GetImage"/>
                <img src="img/camera.png" />
            </div>
            <div class="input-chat">

                <textarea  v-model="message"  @keydown="submitHandler"  @focus="setRead" autofocus></textarea>

                <img src="img/sent.png" class="img-sent" @click="addChat" v-if="message" />
                <img src="img/minhand.png" class="img-hand" @click="addIcon"  v-else/>
            </div>
        </div>

        <div class="modal-img" v-if="imgchat">
            <i class="fa fa-close" @click="hideimg"></i>
            <img :src="'images/chat/'+imgchat " v-if="!checkimage(imgchat)"/>
            <img :src="imgchat" v-else/>

        </div>

    </div>
</template>

<script>
    export default {
        props: ['openConversation','userid','contact'],
        data() {
            return {
                chats : {},
                message :"",
                connecticon : "",
                avatar : [],
                iconchat: [
                    { icon: ":*" , name: "kiss.png"},
                    { icon: ":)" , name: "happy.png"},
                    { icon: ":(" , name: "sad.png"},
                    { icon: ":D" , name: "lol.png"},
                    { icon: "*.*" , name: "love.png"},
                    { icon: ":'(" , name: "crying.png"},
                    { icon: "<3" , name: "heart.png"},
                    { icon: ":p" , name: "tongue.png"},
                    { icon: "?.?" , name: "puzzled.png"},
                    { icon: "3:(" , name: "evil.png"},
                ],
                check : true,
                typing : false,
                time : "",
                numero : 0,
                usernotify : "",
                imgchat : "",
            }
        },
        methods: {
            getChat() {

                axios.get('/chats/'+this.userid+'/'+this.contact.id)
                    .then(response => {
                       this.chats = response.data;
                    }).catch(error => {
                    console.log(error);
                })
            },
            GetImage(e) {
                this.check = false;
                let image = e.target.files[0];
                let reader = new FileReader();
                reader.readAsDataURL(image);
                reader.onload = e => {
                    var chat = {
                         user_id : this.userid,
                         contact_id : this.contact.id,
                         image : e.target.result,
                         read : 0
                     };
                    this.avatar.push(e.target.result);
                    this.chats.push(chat);
                    axios.post('/chats/image',chat).then((response)=>{
                        if(response.data.etat) {
                            this.check = true ;
                        }

                    })
                        .catch((error) => {
                            console.log(error);
                        })
                }
            },
            checkimage(image){
                return _.find(this.avatar, (u) => u === image);
            },
            checkicon(icon){
                var filtericon = _.find(this.iconchat, (u) => u.icon === icon);
                if(filtericon) {
                    return filtericon.name;

                }
            },
            addIcon(){
                this.check =false;
                var chat = {
                    user_id : this.userid,
                    contact_id : this.contact.id,
                    message : "(Ok)",
                    read : 0
                };
                this.chats.push(chat);
                axios.post('/chats/icon',chat).then((response)=>{
                    if(response.data.etat) {
                        this.check = true ;
                    }

                })
                    .catch((error) => {
                        console.log(error);
                    })
            },
            sendHello(){
                this.check = false;
                var chat = {
                    user_id : this.userid,
                    contact_id : this.contact.id,
                    message : 'Hello',
                    read : 0
                };
                this.chats.push(chat);
                axios.post('/chats',chat).then((response)=>{
                    if(response.data.etat) {
                        this.check = true ;
                    }

                })
                    .catch((error) => {
                        console.log(error);
                    })
            },

            addChat(){
                this.check = false;
                var chat = {
                        user_id : this.userid,
                        contact_id : this.contact.id,
                        message : this.message,
                        read : 0
                };
                if(this.message!=" " && this.message!=""){
                    this.chats.push(chat);
                }
                this.message = "";

                  axios.post('/chats',chat).then((response)=>{
                    if(response.data.etat) {
                        this.check = true ;
                    }

                    })
                    .catch((error) => {
                        console.log(error);
                    })

            },

            Close(){
                this.$parent.contact = "";
                this.$emit('close');
            },
            submitHandler(e){
                if(e.keyCode === 13 && !e.shiftKey){
                    e.preventDefault();
                    this.addChat();
                }

            },
            showtime(key){
                this.time = key;
            },
            showimg(img){
                this.imgchat = img;
            },
            hideimg(){
                this.imgchat = "";
            },
            setRead(){
                this.$parent.Read(this.contact);
                this.$parent.getChats();

            },

            scrollToEnd(){
                var container = document.querySelector('ul.list-msg')
                var currentScrollHeight =container.scrollHeight;
                container.scrollTop = currentScrollHeight;
            }
        },
        mounted:function () {
            Echo.private('ReadMessage')
                .listen('ReadMessageEvent', (e) => {

                    if(this.userid == e.contactid && this.contact.id == e.userid){
                        this.chats[this.chats.length-1].read = 1
                    }
                })

        },


        watch:{
            contact(){
                this.getChat();
            },
            message(){
                this.scrollToEnd();
                Echo.private('Chat')
                    .whisper('typing', {
                        name: this.message,
                        contact_id : this.contact.id,
                        user_id : this.userid
                    });
            },

    },








    }
</script>
