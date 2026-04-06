<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

 <!-- jQuery 3 -->
    <script src="/adminlte/components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="/adminlte/components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Slimscroll -->
    <script src="/adminlte/components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="/adminlte/components/fastclick/lib/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="/adminlte/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="/adminlte/dist/js/demo.js"></script>
    <!-- iCheck -->
    <script src="plugins/iCheck/icheck.min.js"></script>
    <!-- Page Script -->
    
 <!-- Ionicons -->
 <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css"> 
  <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
<!--        <div class="row">
            
            <div class="col-md-1">
            <a href="/index.php?r=email/index" class="btn btn-primary btn-block mb-3">Ritorna</a>
            </div>
        </div>-->
<!--            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Folders</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item active">
                    <a href="#" class="nav-link">
                      <i class="fas fa-inbox"></i> Inbox
                      <span class="badge bg-primary float-right">12</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-envelope"></i> Sent
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-file-alt"></i> Drafts
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="fas fa-filter"></i> Junk
                      <span class="badge bg-warning float-right">65</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-trash-alt"></i> Trash
                    </a>
                  </li>
                </ul>
              </div>
               /.card-body 
            </div>
          </div>-->
<div class="row">
    
<!--          <div class="card card-primary card-outline">-->
<!--            <div class="card-header">
              <h3 class="card-title">Messaggio EMAIL</h3>

              <div class="card-tools">
                <a href="#" class="btn btn-tool" title="Previous"><i class="fas fa-chevron-left"></i></a>
                <a href="#" class="btn btn-tool" title="Next"><i class="fas fa-chevron-right"></i></a>
              </div>
            </div>-->
            <!-- /.card-header -->
            <div class="box box-primary">
                    <div class="box-header with-border">
            
                        <div class="box-header with-border">
                        <h3 class="box-title">Oggetto: <?= $mail->subject ?></h3>
                        <h5>Da: <b><?= $mail->fromAddress ?></b></h5>
                        <h5>Data e ora di arrivo: <b><?= date('d-m-Y h:i:s', isset($mail->date) ? strtotime(preg_replace('/\(.*?\)/', '', $mail->date)) : time()) ?></b></h5>
<!--                        <div class="box-tools pull-right">
                            <div class="has-feedback">
                                <p><?= date('d-m-Y h:i:s', isset($mail->date) ? strtotime(preg_replace('/\(.*?\)/', '', $mail->date)) : time()) ?></p>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>-->
                        <!-- /.box-tools -->
                    </div>
                        
                        
<!--                        <div class="mailbox-controls">
                             Check all button 
                            <button type="button" class="btn btn-default btn-sm checkbox-toggle">
                                <i class="fa fa-square-o"></i>
                            </button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                            </div>
                             /.btn-group 
                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                            <div class="pull-right">
                                1-50/200
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                                </div>
                                 /.btn-group 
                            </div>
                             /.pull-right 
                        </div>-->
                        
                        
                        
<!--            <div class="card-body p-0">
              <div class="mailbox-read-info">
                <h5><?php // $mail->subject ?></h5>
                <h6>Da: <?php // $mail->fromAddress ?>
                  <span class="mailbox-read-time float-right"><?php // date('d-m-Y h:i:s', isset($mail->date) ? strtotime(preg_replace('/\(.*?\)/', '', $mail->date)) : time()) ?></span></h6>
              </div>
                
            </div>    -->
                
              <!-- /.mailbox-read-info -->
<!--              <div class="mailbox-controls with-border text-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm" data-container="body" title="Delete">
                    <i class="far fa-trash-alt"></i>
                  </button>
                  <button type="button" class="btn btn-default btn-sm" data-container="body" title="Reply">
                    <i class="fas fa-reply"></i>
                  </button>
                  <button type="button" class="btn btn-default btn-sm" data-container="body" title="Forward">
                    <i class="fas fa-share"></i>
                  </button>
                </div>
                 /.btn-group 
                <button type="button" class="btn btn-default btn-sm" title="Print">
                  <i class="fas fa-print"></i>
                </button>
              </div>-->
              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message">
                  <?php //echo $mail->textPlain; 
                        //echo "A******************A";
                        //$mailObject = $mailbox->getMailParts($mail);
                       // Array with IncomingMail objects
                        // Read mail parts (plain body, html body and attachments
                        //$mailObject = $mailbox->getMailParts($mail);
                        echo utf8_encode($mail->textPlain);
                        //print_r($mail->textPlain);
                        //echo var_dump($mail);
                        //echo "B******************B";
                        //echo var_dump($mail->getAttachments());
                        //echo print_r($mail->bo);
                        //echo print_r($mail);
                        //echo "C******************C";
                  
                  ?>
              </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer bg-white">
                <?php
                if (isset($mail->attachments)) {
                    foreach ($mail->attachments as $allegato) {
		?>
			 <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
                		<li>
                  		<span class="mailbox-attachment-icon"><i class="far fa-file-pdf"></i></span>

                  <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i><?php // $allegato->name ?></a>
                        <span class="mailbox-attachment-size clearfix mt-1">
                          <span>1,245 KB</span>
                          <a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                        </span>
                  </div>
		<?php }
                        
                }
                ?>
<!--              <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
                <li>
                  <span class="mailbox-attachment-icon"><i class="far fa-file-pdf"></i></span>

                  <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> Sep2014-report.pdf</a>
                        <span class="mailbox-attachment-size clearfix mt-1">
                          <span>1,245 KB</span>
                          <a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                        </span>
                  </div>
                </li>
                <li>
                  <span class="mailbox-attachment-icon"><i class="far fa-file-word"></i></span>

                  <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> App Description.docx</a>
                        <span class="mailbox-attachment-size clearfix mt-1">
                          <span>1,245 KB</span>
                          <a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                        </span>
                  </div>
                </li>
                <li>
                  <span class="mailbox-attachment-icon has-img"><img src="../../dist/img/photo1.png" alt="Attachment"></span>

                  <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name"><i class="fas fa-camera"></i> photo1.png</a>
                        <span class="mailbox-attachment-size clearfix mt-1">
                          <span>2.67 MB</span>
                          <a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                        </span>
                  </div>
                </li>
                <li>
                  <span class="mailbox-attachment-icon has-img"><img src="../../dist/img/photo2.png" alt="Attachment"></span>

                  <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name"><i class="fas fa-camera"></i> photo2.png</a>
                        <span class="mailbox-attachment-size clearfix mt-1">
                          <span>1.9 MB</span>
                          <a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                        </span>
                  </div>
                </li>
              </ul>-->
            </div>
<!--        </div>-->
            
            <!-- /.card-footer -->
            <div class="card-footer">
<!--              <div class="row">
                <button type="button" class="btn btn-default"> Rispondi</button>
                <button type="button" class="btn btn-default"> Seguente</button>
              
              <button type="button" class="btn btn-default"> Cancella</button>
              <button type="button" class="btn btn-default"> Stampa</button>
            </div>-->

                <div class="mailbox-controls with-border text-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm" data-container="body" title="Stampa">
                        <i class="fas fa-print"></i>
                        </button>
<!--                        <button type="button" class="btn btn-default btn-sm" data-container="body" title="Reply">
                          <i class="fas fa-reply"></i>
                        </button>
                        <button type="button" class="btn btn-default btn-sm" data-container="body" title="Forward">
                          <i class="fas fa-share"></i>
                        </button>-->
                    </div>
                </div>
            <!-- /.card-footer -->
          </div>
          <!-- /.card -->
       
        <!-- /.col -->
      </div>
      <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  
  
  
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
</body>
</html>