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
                        <p v-if="id_prefecture">Vous voulez vraiment supprimer cet Prefecture .</p>
                        <p v-else>Vous pouvez pas supprimer cet Prefecture .</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="hideCard" v-if="id_prefecture">Retour</button>
                        <button type="button" class="btn btn-primary" @click="deleteprefecture" v-if="id_prefecture">Supprimer</button>
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
                id_prefecture :""
            }
        },
        methods: {

            hideCard() {
                this.$emit('closeCard');
            },

            deleteprefecture() {
                this.$parent.loading_id = this.id_prefecture;
                axios.delete('/gestionprefectures/'+this.id_prefecture).then((response)=>{
                    if(response.data.delete) {
                        this.$parent.loading_id =  -1;
                        this.hideCard();
                        this.$parent.getPrefectures();
                        this.$parent.messageAlert ="Prefecture à été supprimer avec success ...";

                    }

                })
                    .catch((error) => console.log(error));
            }
        },
    }

</script>
