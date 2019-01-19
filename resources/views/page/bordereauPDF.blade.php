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
<h1>BON DE LIVRAISON</h1>
<div class="bl-title">
  <div class="bl-left">
    <p><span>Association : </span> {{ $bordereau['besoin']['av']['libelle'] }}</p>
    <p><span>Representant : </span>{{ $bordereau['besoin']['av']['representant'] }}</p>
    <p><span>Adresse : </span>{{ $bordereau['besoin']['av']['adresse'] }}</p>
    <p><span>Email : </span>{{ $bordereau['besoin']['av']['email'] }}</p>
    <p><span>Mobile : </span>{{ $bordereau['besoin']['av']['mobile'] }}</p>
    <p><span>N° Fix : </span>{{ $bordereau['besoin']['av']['numfix'] }}</p>
    @if($bordereau['besoin']['av']['fax'])
      <p><span>Fax : </span>{{ $bordereau['besoin']['av']['fax'] }}</p>
    @endif
  </div>

  <div class="bl-right">
    <p><span>Date Livraison : </span> {{ $bordereau['besoin']['date'] }}</p>
    <p><span>Date Paiement : </span>{{ $bordereau['datePaiement'] }}</p>
    <p><span>Modalite Paiement : </span>{{ $bordereau['modaliteP'] }}</p>
  </div>
</div>

<h3> Livraison N° : {{ $bordereau['nbordereau'] }}</h3>
<table class="table-article">

    <tr>
        <th>Article</th>
        <th>Designation</th>
        <th>Unité</th>
        <th>Prix Vente</th>
        <th>Qte</th>
        <th>Tva (%)</th>
        <th>Total</th>
    </tr>
    {{
       $priceTotal = 0,
       $price = 0
       }}
    {{$bordereau['detailbesoin']}}
@foreach($details as $detail)
     <tr>
        <td>{{ $detail['article']['code']}}</td>
        <td>{{ $detail['article']['libelle']}}</td>
        <td>{{ $detail['article']['unitevente']}}</td>
        <td>{{ $detail['article']['saisons'][0]['pivot']['prixvente']}}</td>
        <td>{{ $detail['quantite']}}</td>
        <td>{{ $detail['article']['saisons'][0]['pivot']['tva']}}</td>
        <td>{{ $price  = ($detail['article']['saisons'][0]['pivot']['prixvente'] * $detail['quantite'] * $detail['article']['saisons'][0]['pivot']['tva']) /100 +
        $detail['article']['saisons'][0]['pivot']['prixvente'] * $detail['quantite'] }}</td>
     </tr>
        {{  $priceTotal = $priceTotal + $price }}
    @endforeach
</table>
<table class="table-total">
    <tr>
        <td>Total Livraison</td>
        <td> {{ $priceTotal }}</td>
    </tr>
</table>
<div class="footer">
  @if($bordereau['observation'])
    <p> Remarque :  {{ $bordereau['observation'] }}</p>
  @endif
</div>
</body>
</html>

