<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->registerJsFile('js/aiutils.js');
?>


<!--<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">-->


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="margin-left:10px!important">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Interrogazioni all'archivio con l'aiuto dell'intelligenza artificiale
<!--        <small>Pagina inziale</small>-->
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      
          <!-- Construct the card with style you want. Here we are using card-danger -->
                <!-- Then add the class direct-chat and choose the direct-chat-* contexual class -->
                <!-- The contextual class should match the card, so we are using direct-chat-danger -->
                <div class="card card-primary direct-chat direct-chat-primary" style="position: relative;height: auto;min-height: 100% !important;">
                  <div class="card-header">
                    <h3 class="card-title">Messaggi</h3>
<!--                    <div class="card-tools">
                      <span data-toggle="tooltip" title="3 New Messages" class="badge badge-light">3</span>
                      <button type="button" class="btn btn-tool" data-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle">
                        <i class="fas fa-comments"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-widget="remove"><i class="fas fa-times"></i>
                      </button>
                    </div>-->
                  </div>
                  <!-- /.card-header -->
                  <div  class="card-body">
                    <!-- Conversations are loaded here -->
                    <div id="chatbody" class="direct-chat-messages">
                        <div class="direct-chat-msg">
                            <div class="direct-chat-infos clearfix">
                              <span class="direct-chat-name float-left">AI-UTC-Manager</span>
                              <span class="direct-chat-timestamp float-right"><?= date("d/m/Y H:i:s", time()) ?></span>
                            </div>
                            <img class="direct-chat-img" src="img/user2-160x160.jpg" alt="message user image" />
                            <div class="direct-chat-text">
                              Buongiorno, sono il tuo assistente personale, formula una domanda inerente qualcosa che è in archivio!
                            </div>
                      </div>
                    <!--/.direct-chat-messages-->
                    <!-- Contatti vanno messi qui -->

                    </div>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <form action="" method="post">
                      <div class="input-group">
                        <input id="msguser" type="text" name="message" placeholder="digita la tua richiesta ..." class="form-control">
                        <span class="input-group-append">
                          <button type="button" onclick="airequest()" class="btn btn-primary">Invia</button>
                        </span>
                      </div>
                    </form>
                  </div>
                  <!-- /.card-footer-->
                </div>
                <!--/.direct-chat -->


            
      
    </section>
</div>
<?php
$script = <<< JS
$(document).ready(function () {
// Invio con il tasto "Enter"
    $("#msguser").keypress(function (event) {
        if (event.which === 13) {
            event.preventDefault();
            airequest();
        }
    });
});
JS;
$this->registerJs($script);        
    











