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
<h1>BORDEREAU REGELEMENT</h1>
<div class="bl-title">
  <div class="bl-left">
    <p><span>Association : </span> {{ $facture['bordereaulivraison']['besoin']['av']['libelle'] }}</p>
    <p><span>Representant : </span>{{ $facture['bordereaulivraison']['besoin']['av']['representant'] }}</p>
    <p><span>Adresse : </span>{{ $facture['bordereaulivraison']['besoin']['av']['adresse'] }}</p>
    <p><span>Email : </span>{{ $facture['bordereaulivraison']['besoin']['av']['email'] }}</p>
    <p><span>Mobile : </span>{{ $facture['bordereaulivraison']['besoin']['av']['mobile'] }}</p>
    <p><span>N° Fix : </span>{{ $facture['bordereaulivraison']['besoin']['av']['numfix'] }}</p>
    @if($facture['bordereaulivraison']['besoin']['av']['fax'])
      <p><span>Fax : </span>{{ $facture['bordereaulivraison']['besoin']['av']['fax'] }}</p>
    @endif
  </div>

  <div class="bl-right">
    <p><span>Date Réglement : </span> {{ $bordereau['datereglement'] }}</p>
    <p><span>Modalite Paiement : </span>{{ $bordereau['modaliteP'] }}</p>
  </div>
</div>

<h3> Livraison N° : {{ $bordereau['reglement'] }}</h3>
<table class="table-article">

    <tr>
        <th>Facture</th>
        <th>Montant TTC</th>
        <th>Montant Paiement</th>
        <th>Reste</th>
    </tr>
     <tr>
        <td>{{ $facture['nfacture']}}</td>
        <td>{{ $facture['bordereaulivraison']['total']}}</td>
        <td>{{ $bordereau['montantP']}}</td>
        <td>{{ $bordereau['montantreglement']-$bordereau['montantP']}}</td>
     </tr>
</table>
<table class="table-total">
    <tr>
        <td>Montant</td>
        <td> {{ $bordereau['montantP'] }}</td>
    </tr>
</table>
</body>
</html>

