/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */



function airequest2 (evt) {
    
let msg = document.getElementById("msguser").value;
const apiKey = process.env.OPENAI_API_KEY; //"LA_TUA_API_KEY"; // Inserisci la tua API Key
const url = "https://api.openai.com/v1/chat/completions";

async function getOpenAIResponse(prompt) {
    const response = await fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${apiKey}`
        },
        body: JSON.stringify({
            model: "gpt-4",  // Cambia con "gpt-3.5-turbo" se vuoi usare il modello precedente
            messages: [{ role: "user", content: prompt }],
            max_tokens: 100,
            temperature: 0.7
        })
    });

    const data = await response.json();
    console.log(data);
    let msgu = data.choices[0].message.content;
    if (msgu) {
        addmsg(msgu);    
    }
    
}
}


function dataoggi(){
    var oggi = new Date();
    var hh = oggi.getHours();
    var mi = oggi.getMinutes();
    var se = oggi.getSeconds();
    var gg = oggi.getDay();
    var mm = oggi.getMonth()+1;
    var aa = oggi.getFullYear();
var datas = +gg+'/'+mm+'/'+aa+' '+hh+':'+mi+':'+se;
return datas;
}

function addusrmsg(msg, utente) {
    //let msg = document.getElementById("msguser").value;
    let cmsgs =document.getElementById("chatbody");
    let messaggio = document.createElement('DIV');
//    messaggio.class="direct-chat-msg right";
//    let usr = document.createElement('DIV');
//    //usr.class="direct-chat-infos clearfix";
    
    let msgHTML = "<div class='direct-chat-msg right'>";
    msgHTML=msgHTML+"<div class='direct-chat-infos clearfix'>";
    msgHTML=msgHTML+"<span class='direct-chat-name float-right'>"+utente+"</span>";
    msgHTML=msgHTML+"<span class='direct-chat-timestamp float-left'>"+dataoggi()+"</span>";
    msgHTML=msgHTML+"</div>";
    msgHTML=msgHTML+"<img class='direct-chat-img float-right' src='img/user3-128x128.jpg' alt='message user image'>";
    msgHTML=msgHTML+"<div class='direct-chat-text left'>"+msg+"</div>";
    msgHTML=msgHTML+"</div>";
    messaggio.innerHTML = msgHTML;
    
    
    //alert(msgHTML);
    cmsgs.appendChild(messaggio);
    cmsgs.scrollTop = cmsgs.scrollHeight;

}

function addassmsg(msgt) {
    //let msg = document.getElementById("msguser").value;
    let cmsgs =document.getElementById("chatbody");
    let messaggio = document.createElement('DIV');
    let text = msgt.replace(/\n/g, "<br>");
    messaggio.class="direct-chat-msg";
    let msgHTML = "<div class='direct-chat-infos clearfix'>";
    msgHTML=msgHTML+"<span class='direct-chat-name float-left'>AI-UTC-Manager</span>";
    msgHTML=msgHTML+"<span class='direct-chat-timestamp float-right'>"+dataoggi()+"</span>";
    msgHTML=msgHTML+"</div>";
    msgHTML=msgHTML+"<img class='direct-chat-img' src='img/user2-160x160.jpg' alt='immagine utente'>";
    msgHTML=msgHTML+"<div class='direct-chat-text'>"+text+"</div>";
    msgHTML=msgHTML+"</div>";
    messaggio.innerHTML = msgHTML;
    cmsgs.appendChild(messaggio);
    cmsgs.scrollTop = cmsgs.scrollHeight;

}

function airequest (evt) {
    
    let msginp = document.getElementById("msguser");
    let msg = msginp.value;
    addusrmsg(msg,"Giuseppe Caporaso");
    msginp.value = "";
    //alert("Qui dovrei aver cancellato input utente!")
//    console.log("funzione airequest: msg="+msg);
            $.ajax({
                       url:'index.php?r=chat%2Fchat',
                       type:'POST',
                       dataType:'json',
                       data:{msguser:msg}
                       }).done(function(result) {
                           //const datit = result.decode(result);
                           //console.log("richiesta ha avuto esito positivo"+result);
                           //const datit = JSON.parse(result);
                           addassmsg(result['response']);
                           //alert(result['response']);
                           //console.log("funzione airequest: result="+datit);
                           // aggiungere risposta
                           //addmsg(result);
                            
                        }); // fine ajax
                                
}