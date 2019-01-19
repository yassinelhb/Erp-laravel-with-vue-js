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
                       <p> Vous voulez vraiment supprimer cet Facture .</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="hideCard">Retour</button>
                        <button type="button" class="btn btn-primary" @click="deletefacture">Supprimer</button>
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
                id_facture :""
            }
        },
        methods: {

            hideCard() {
                this.$emit('closeCard');
            },

            deletefacture() {
                this.$parent.loading_id = this.id_facture;
                axios.delete('/gestionfactureglobal/'+this.id_facture).then((response)=>{
                    if(response.data.delete) {
                        this.$parent.loading_id =  0;
                        this.hideCard();
                        this.$parent.getFactures();
                        this.$parent.messageAlert ="Facture à été supprimer avec success ...";

                    }

                })
                    .catch((error) => console.log(error));
            }
        },
    }

</script>
