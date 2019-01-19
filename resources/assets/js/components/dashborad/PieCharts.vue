<script>
import { Pie } from 'vue-chartjs';

export default {
    extends: Pie,
     mounted() {
         let Avs = [];
         let Countbesoin = [];

         axios.get('/gestionavs/')
             .then(response => {
                 let data = response.data.avs;
                 for(let i = 0;i<data.length;i++){
                     if(data[i].besoins.length >= Countbesoin){
                         Avs.unshift(data[i].libelle)
                         Countbesoin.unshift(data[i].besoins.length)
                     }else{
                         Avs.push(data[i].libelle)
                         Countbesoin.push(data[i].besoins.length)
                     }

                 }

                 let FirstThreeAvs = [];
                 let FirstThreeCountbesoin = [];

                 for(let i = 0;i< 3;i++) {
                     FirstThreeAvs.push(Avs[i])
                     FirstThreeCountbesoin.push(Countbesoin[i])
                 }


                 this.renderChart({
                     labels: FirstThreeAvs,
                     datasets: [{
                         label: 'Besoin',
                         borderColor: '#fff',
                         pointBackgroundColor: '#fff',
                         borderWidth: 3,
                         data: FirstThreeCountbesoin,
                         backgroundColor: [
                             "rgba(10, 166, 153, 0.63)",
                             "rgb(240, 244, 244)",
                             "rgb(182, 191, 201)"
                         ],
                         hoverBackgroundColor: [
                             "rgba(10, 166, 153, 0.63)",
                             "rgb(240, 244, 244)",
                             "rgb(182, 191, 201)"
                         ]

                     }]
                 }, {responsive: true, maintainAspectRatio: false})

         })

    }



}
</script>