<!doctype html>
<html >
<head>
    <title>Cotontchad</title>
<style>
 h1 {
     text-align: center;
     margin-bottom: 0px;
     font-size: 21px;
 }
 h3 {
     font-size: 17px;
     margin: 0 25px 10px;
     text-align: right;
 }
 .image-logo {
     width: 170px;
 }
 .table-article {
     width: 100%;
     margin-top: 25px;
     margin-bottom: 15px;
 }
 .table-article tr th {
     text-align: center;
     padding: 7px;
     background-color: #0aa699;
     color: #fff;
 }
 .table-article tr td{
     padding: 7px;
 }
 .bl-title {
     width: 100%;
     margin-top: 85px;
     margin-bottom: 0;
 }
 .bl-left {
     padding: 7px;
     border: 1px solid #000;
     border-radius: 5px;
     display: inline-block;
     width: 290px;
 }
 .bl-right {
     padding: 7px;
     border: 1px solid #000;
     border-radius: 5px;
     display: inline-block;
     width: 290px;
     float: right;
 }
 .bl-title p {
     padding: 0px;
 }
 .bl-title p span {
     padding-right: 15px;
 }
 .footer {
     position: absolute;
     bottom: 0px;
 }
 .footer p {
     text-align: center;
 }
 .table-total{
     margin-top: 15px;
     float: right;
 }
 .table-total{
     float: right;
 }
 .table-total tr td {
     padding: 7px;
     text-align: right;
 }

</style>
</head>
<body>
<img src="images/logo.png" class="image-logo">
<h1>Facture Global</h1>
<div class="bl-title">
  <div class="bl-left">
    <p><span>Association : </span> {{ $detail['bordereaulivraison']['besoin']['av']['libelle'] }}</p>
    <p><span>Representant : </span>{{ $detail['bordereaulivraison']['besoin']['av']['representant'] }}</p>
    <p><span>Adresse : </span>{{ $detail['bordereaulivraison']['besoin']['av']['adresse'] }}</p>
    <p><span>Email : </span>{{ $detail['bordereaulivraison']['besoin']['av']['email'] }}</p>
    <p><span>Mobile : </span>{{ $detail['bordereaulivraison']['besoin']['av']['mobile'] }}</p>
    <p><span>N° Fix : </span>{{ $detail['bordereaulivraison']['besoin']['av']['numfix'] }}</p>
    @if($detail['bordereaulivraison']['besoin']['av']['fax'])
      <p><span>Fax : </span>{{ $detail['bordereaulivraison']['besoin']['av']['fax'] }}</p>
    @endif
  </div>

  <div class="bl-right">
    <p><span>Date Livraison : </span> {{ $detail['bordereaulivraison']['besoin']['date'] }}</p>
    <p><span>Date Paiement : </span>{{ $detail['bordereaulivraison']['datePaiement'] }}</p>
    <p><span>Modalite Paiement : </span>{{ $detail['bordereaulivraison']['modaliteP'] }}</p>
  </div>
</div>

<h3> Livraison N° : {{ $facture['nglobal'] }}</h3>
<table class="table-article">
       {{ $credit = 0 }}
    <tr>
        <th>Facture Livraison</th>
        <th>Crédit</th>
        <th>Total</th>
    </tr>
    <tr>
       <td>{{$detail['nfacture']}}</td>
       @if(count($detail['reglements'])>0)

        <td>{{ $credit = $detail['reglements'][count($detail['reglements'])-1]['montantreglement']
               - $detail['reglements'][count($detail['reglements'])-1]['montantP']}}</td>
        @else
        <td>0</td>
       @endif
       <td>{{$detail['bordereaulivraison']['total']}}</td>
    </tr>
</table>
<table class="table-total">
    <tr>
        <td>Montant</td>
        <td> {{ $detail['bordereaulivraison']['total'] - $credit }}</td>

    </tr>
</table>

</body>
</html>

