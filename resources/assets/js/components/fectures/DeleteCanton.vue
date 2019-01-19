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
                        <p v-if="id_canton">Vous voulez vraiment supprimer cet Canton .</p>
                        <p v-else>Vous pouvez pas supprimer cet Canton .</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="hideCard" v-if="id_canton">Retour</button>
                        <button type="button" class="btn btn-primary" @click="deletecanton" v-if="id_canton">Supprimer</button>
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
                id_canton :""
            }
        },
        methods: {

            hideCard() {
                this.$emit('closeCard');
            },

            deletecanton() {
                this.$parent.loading_id = this.id_canton;
                axios.delete('/gestioncantons/'+this.id_canton).then((response)=>{
                    if(response.data.delete) {
                        this.$parent.loading_id =  -1;
                        this.hideCard();
                        this.$parent.getCantons();
                        this.$parent.messageAlert ="Canton à été supprimer avec success ...";

                    }

                })
                    .catch((error) => console.log(error));
            }
        },
    }

</script>
