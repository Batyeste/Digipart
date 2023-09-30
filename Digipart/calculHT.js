var taux = 5.5;
var champPrixHT = document.getElementById("priceTaxExcl");
var champPrixTTC = document.getElementById("priceTaxIncl");

function calculTTC(prixHT){
    prixHT = prixHT.replace(",",".");
    var prixTTC = prixHT*(1+taux/100);
    return prixTTC.toFixed(2);
}

function aPrixTTC() {
    var prixHT = champPrixHT.value;
    if (prixHT !== "") {
        var prixTTC = calculTTC(prixHT);
        champPrixTTC.value = prixTTC;
    } else {
        champPrixTTC.value = "";
    }
}

champPrixHT.addEventListener("input", aPrixTTC);