<template>
    <span id="notify-icon" v-if="notify > 0">{{ notify }}</span>
</template>
<script>
    export default {
        data() {
            return {
                besoins: {},
                notify : 0
            }
        },
        methods: {
            getBesoins(){
                axios.get('/gestionbesoins/')
                    .then(response => {
                        this.besoins = response.data.besoins;

                    }).catch(error => {
                    console.log(error);
                })
            },
            Notify(){
                this.notify =_.filter(this.besoins, (u) => !u.bordereaux  || u.bordereaux.length  == 0 ).length;
            },

        },
        mounted(){
            this.getBesoins();
            Echo.private('Besoin')
                .listen('BesoinEvent', (e) => {
                    this.besoins.push(e.besoin)
                })
        },
        watch: {
            besoins(){
                this.Notify();
            }
        }
    }
</script>
