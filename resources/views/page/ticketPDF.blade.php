<!doctype html>
<html >
<head>
    <title>Cotontchad</title>
<style>
 .content {
     width: 320px;
     padding: 10px 10px 20px;
     border: 1px solid #000;
 }
 h1 {
     text-align: center;
     font-size: 21px;
 }
 h5 {
     text-align: center;
     font-size: 17px;
 }
    table tr td {
        padding: 5px 9px;
    }
</style>
</head>
<body>
<div class="content">
<h1>TICKET</h1>
<h5>{{$ticket->id}}</h5>
<table>
    <tr>
        <td>Caisse</td>
        <td>{{$ticket->caisse}}</td>
    </tr>
    <tr>
        <td>Date Poids 1</td>
        <td>{{$ticket->dateP1}}</td>
    </tr>
    <tr>
        <td>Date Poids 2</td>
        <td>{{$ticket->dateP2}}</td>
    </tr>
    <tr>
        <td>Poids 1</td>
        <td>{{$ticket->poidsP1}}</td>
    </tr>
    <tr>
        <td>Poids 2</td>
        <td>{{$ticket->poidsP2}}</td>
    </tr>
    <tr>
        <td>Peseur</td>
        <td>{{$ticket->peseur}}</td>
    </tr>
    <tr>
        <td>Véhicule</td>
        <td>{{$ticket->vehicule}}</td>

    <tr>
        <td colspan="2" class="text-center">{{$ticket->observation}}</td>
    </tr>



</table>
</div>

</body>
</html>

