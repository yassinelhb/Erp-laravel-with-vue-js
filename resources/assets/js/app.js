
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue'
import VueWeatherWidget from 'vue-weather-widget';
import 'vue-weather-widget/dist/css/vue-weather-widget.css';
import Datepicker from 'vuejs-datepicker';
import VueChatScroll from 'vue-chat-scroll'
Vue.use(VueChatScroll)



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('linecharts',require('./components/dashborad/LineCharts.vue'));
Vue.component('piecharts',require('./components/dashborad/PieCharts.vue'));
Vue.component('weather',VueWeatherWidget);
Vue.component('datepicker',Datepicker);
Vue.component('dashborad', require('./components/dashborad/Dashborad.vue'));
Vue.component('chat', require('./components/chats/Chat.vue'));
Vue.component('conversation', require('./components/chats/Conversation.vue'));
Vue.component('gestionuser', require('./components/users/GestionUser.vue'));
Vue.component('adduser', require('./components/users/AddUser.vue'));
Vue.component('deleteuser', require('./components/users/DeleteUser.vue'));
Vue.component('gestionsaison', require('./components/saisons/GestionSaison.vue'));
Vue.component('addsaison', require('./components/saisons/AddSaison.vue'));
Vue.component('deletesaison', require('./components/saisons/DeleteSaison.vue'));
Vue.component('gestionarticle', require('./components/articles/GestionArticle.vue'));
Vue.component('addarticle', require('./components/articles/AddArticle.vue'));
Vue.component('deletearticle', require('./components/articles/DeleteArticle.vue'));
Vue.component('gestionprefectures', require('./components/fectures/GestionPrefecture.vue'));
Vue.component('addprefectures', require('./components/fectures/AddPrefecture.vue'));
Vue.component('deleteprefectures', require('./components/fectures/DeletePrefecture.vue'));
Vue.component('gestionsousprefectures', require('./components/fectures/GestionSousPrefecture.vue'));
Vue.component('addsousprefectures', require('./components/fectures/AddSousPrefecture.vue'));
Vue.component('deletesousprefectures', require('./components/fectures/DeleteSousPrefecture.vue'));
Vue.component('gestioncanton', require('./components/fectures/GestionCanton.vue'));
Vue.component('addcanton', require('./components/fectures/AddCanton.vue'));
Vue.component('deletecanton', require('./components/fectures/DeleteCanton.vue'));
Vue.component('gestioncgi', require('./components/fectures/GestionCgi.vue'));
Vue.component('addcgi', require('./components/fectures/AddCgi.vue'));
Vue.component('deletecgi', require('./components/fectures/DeleteCgi.vue'));
Vue.component('gestionav', require('./components/fectures/GestionAv.vue'));
Vue.component('addav', require('./components/fectures/AddAv.vue'));
Vue.component('deleteav', require('./components/fectures/DeleteAv.vue'));
Vue.component('profil', require('./components/Profil.vue'));
Vue.component('gestionbesoin', require('./components/besoins/GestionBesoin.vue'));
Vue.component('addbesoin', require('./components/besoins/AddBesoin.vue'));
Vue.component('deletebesoin', require('./components/besoins/DeleteBesoin.vue'));
Vue.component('detailbesoin', require('./components/besoins/DetailBesoin.vue'));
Vue.component('addarticlebesoin', require('./components/besoins/AddArticleBesoin.vue'));
Vue.component('deletearticlebesoin', require('./components/besoins/DeleteArticleBesoin.vue'));
Vue.component('gestionbordereau', require('./components/bordereau/GestionBordereau.vue'));
Vue.component('addbordereau', require('./components/bordereau/AddBordereau.vue'));
Vue.component('factureslivraison', require('./components/factures/Factureslivraison.vue'));
Vue.component('gestionticket', require('./components/tickets/GestionTicket.vue'));
Vue.component('gestionreglement', require('./components/reglement/GestionReglement.vue'));
Vue.component('addreglement', require('./components/reglement/AddReglement.vue'));
Vue.component('deletereglement', require('./components/reglement/DeleteReglement.vue'));
Vue.component('facturesglobal', require('./components/factures/Facturesglobal.vue'));
Vue.component('deletefacturesglobal', require('./components/factures/DeleteFactureglobal.vue'));
Vue.component('addticket', require('./components/tickets/AddTicket.vue'));
Vue.component('deleteticket', require('./components/tickets/DeleteTicket.vue'));
Vue.component('notify', require('./components/Notify.vue'));
const app = new Vue({
    el: '#app',
});
