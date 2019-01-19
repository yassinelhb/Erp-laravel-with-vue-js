<script>
import { Line } from 'vue-chartjs';

export default {
    extends: Line,
     mounted() {
         let Saison = [];
         let Qtebesoin = [];
         axios.get('/gestionsaisons/')
             .then(response => {
                 let data = response.data.saison;
                 for(let i = 0;i<data.length;i++){
                     Saison.unshift(data[i].compagne)
                     let Quantite = 0;
                     for(let j = 0;j<data[i].besoins.length;j++) {
                         for(let k = 0;k<data[i].besoins[j].articles.length;k++){
                             Quantite += data[i].besoins[j].articles[k].pivot.quantite;
                         }
                     }
                     Qtebesoin.unshift(Quantite)
                 }
                 this.renderChart({
                     labels: Saison,
                     datasets: [{
                         label: 'Besoin',
                         backgroundColor: 'rgba(10, 166, 153, 0.63)',
                         borderColor: '#0aa699',
                         pointBackgroundColor: '#fff',
                         borderWidth: 1.5,
                         pointBorderColor: '#0aa699',
                         data: Qtebesoin,

                     }]
                 }, {responsive: true, maintainAspectRatio: false})

         })

    }



}
</script>