<template>

    <div class="row" v-if="openCard" >
        <div class="modal fade show" id="exampleModalCenter" style="display: block ;background: rgba(0,0,0,.4);z-index: 9999"  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Supprimer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"  @click="hideCard">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p v-if="id_cgi">Vous voulez vraiment supprimer cet Centre de Gestion des Intrants .</p>
                        <p v-else>Vous pouvez pas supprimer cet Centre de Gestion des Intrants .</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="hideCard" v-if="id_cgi">Retour</button>
                        <button type="button" class="btn btn-primary" @click="deletecgi" v-if="id_cgi">Supprimer</button>
                        <button type="button" class="btn btn-primary" @click="hideCard" v-else>Ok</button>

                    </div>
                </div>
            </div>
        </div>
    </div>




</template>

<script>
    export default {
        props: ['openCard'],
        data() {
            return {
                id_cgi :""
            }
        },
        methods: {

            hideCard() {
                this.$emit('closeCard');
            },

            deletecgi() {
                this.$parent.loading_id = this.id_cgi;
                axios.delete('/gestioncgis/'+this.id_cgi).then((response)=>{
                    if(response.data.delete) {
                        this.$parent.loading_id =  -1;
                        this.hideCard();
                        this.$parent.getCgis();
                        this.$parent.messageAlert ="Centre de Gestion des Intrants à été supprimer avec success ...";

                    }

                })
                    .catch((error) => console.log(error));
            }
        },
    }

</script>
