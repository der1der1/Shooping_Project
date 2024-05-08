function aside_fold(){

    if(document.getElementById("aside").style.display === "none"){
        document.getElementById("aside").style.display = "block";
    }
    else{
        document.getElementById("aside").style.display = "none";
    }
}


function fire_base(){
    const { initializeApp, applicationDefault, cert } = require('firebase-admin/app');
    const { getFirestore, Timestamp, FieldValue, Filter } = require('firebase-admin/firestore');
}