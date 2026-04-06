<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>


<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

<!-- Content Wrapper. Contains page content 
<div class="content-wrapper">-->
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Mailbox ingcampoli@pec.it 
            <small>contiene <?php echo $MailboxInfo->Nmsgs ?> messaggi per un totale di <?php $MB = $MailboxInfo->Size/1024/1024; echo number_format($MB,2,",","."); ?> MB</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Mailbox</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <a href="/Mailbox/Compose" class="btn btn-primary btn-block margin-bottom">Nuova</a>
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cartelle</h3>
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body no-padding">
                        <ul class="nav nav-pills nav-stacked">
                            <li class="active">
                                <a href="#">
                                    <i class="fa fa-inbox"></i> Posta in Arrivo
                                    <span class="label label-primary pull-right"><?php echo $MailboxInfo->Recent ?></span>
                                </a>
                            </li>
                            <li><a href="#"><i class="fa fa-envelope-o"></i> Inviata</a></li>
                            <li><a href="#"><i class="fa fa-file-text-o"></i> Bozze</a></li>
                            <li>
                                <a href="#"><i class="fa fa-filter"></i> Spam <span class="label label-warning pull-right">65</span></a>
                            </li>
                            <li><a href="#"><i class="fa fa-trash-o"></i> Cestino</a></li>
                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /. box -->
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Labels</h3>
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body no-padding">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="#"><i class="fa fa-circle-o text-red"></i> Important</a></li>
                            <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> Promotions</a></li>
                            <li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Social</a></li>
                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-md-10">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Inbox</h3>
                        <div class="box-tools pull-right">
                            <div class="has-feedback">
                                <input type="text" class="form-control input-sm" placeholder="Search Mail">
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <button type="button" class="btn btn-default btn-sm checkbox-toggle">
                                <i class="fa fa-square-o"></i>
                            </button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                            </div>
                            <!-- /.btn-group -->
                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                            <div class="pull-right">
                                1-50/200
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                                </div>
                                <!-- /.btn-group -->
                            </div>
                            <!-- /.pull-right -->
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped">
                                <tr><th>..</th><th>..</th><th>ID</th><th> Da  </th><th> Oggetto </th> <th>Data</th></tr>
                                <tbody>
                                <?php
                                    $mailOutput='';
                                    $i = 1;
                                    foreach($email as $mailId)
                                    {
                                        // Returns Mail contents
                                        $mail = $mailbox->getMail($mailId); 
                                        $mailOutput.= '<tr><td><input type="checkbox"></td>'
                                                . '<td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>'
                                                . '<td>'. $mail->id . '</td>'
                                                . '<td class="mailbox-name"><a href="/index.php?r=email/reademail&mailid=' . $mail->id  . '">'. $mail->fromAddress . '</a></td>'
                                                . '<td>'. $mail->subject . '</td> <td>' . date('Y-m-d H:i:s', isset($mail->date) ? strtotime(preg_replace('/\(.*?\)/', '', $mail->date)) : time()) .'</td></tr>';
                                        // Read mail parts (plain body, html body and attachments
                                        //$mailObject = $mailbox->getMailParts($mail);

                                        // Array with IncomingMail objects
                                        //print_r($mailObject);

                                        // Returns mail attachements if any or else empty array
//                                        $attachments = $mailObject->getAttachments(); 
//                                        foreach($attachments as $attachment){
//                                           // echo ' Attachment:' . $attachment->name . PHP_EOL;
//
//                                            // Delete attachment file
//                                            unlink($attachment->filePath);
                                         if ($i++ == 10) break; // leggo solo 10 email per test
                                    }
                                    echo $mailOutput;
                                ?>
                               
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                                        <td class="mailbox-name"><a href="/index.php?r=email/reademail?mailid=" . <?= 4608 ?>>Alexander Pierce</a></td>
                                        <td class="mailbox-subject">
                                            <b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                                        </td>
                                        <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                        <td class="mailbox-date">15 days ago</td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- /.table -->
                        </div>
                        <!-- /.mail-box-messages -->
                    </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-padding">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <button type="button" class="btn btn-default btn-sm checkbox-toggle">
                                <i class="fa fa-square-o"></i>
                            </button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                            </div>
                            <!-- /.btn-group -->
                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                            <div class="pull-right">
                                1-50/200
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                                </div>
                                <!-- /.btn-group -->
                            </div>
                            <!-- /.pull-right -->
                        </div>
                    </div>
                </div>
                <!-- /. box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
<!-- </div>
 /.content-wrapper -->







        <!-- /.content-wrapper -->
<!--        <footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0.0
    </div>
    <strong>ASP.NET Core Template &copy; 2018 <a href="https://coderush.co">CodeRush</a>.</strong>
</footer>-->
        <!-- Control Sidebar -->
     
<!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
     
<script>
$(function () {
    //Enable iCheck plugin for checkboxes
    //iCheck for checkbox and radio inputs
    $('.mailbox-messages input[type="checkbox"]').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
      radioClass: 'iradio_flat-blue'
    });

    //Enable check and uncheck all functionality
    $(".checkbox-toggle").click(function () {
      var clicks = $(this).data('clicks');
      if (clicks) {
        //Uncheck all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
      } else {
        //Check all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("check");
        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
      }
      $(this).data("clicks", !clicks);
    });

    //Handle starring for glyphicon and font awesome
    $(".mailbox-star").click(function (e) {
      e.preventDefault();
      //detect type
      var $this = $(this).find("a > i");
      var glyph = $this.hasClass("glyphicon");
      var fa = $this.hasClass("fa");

      //Switch states
      if (glyph) {
        $this.toggleClass("glyphicon-star");
        $this.toggleClass("glyphicon-star-empty");
      }

      if (fa) {
        $this.toggleClass("fa-star");
        $this.toggleClass("fa-star-o");
      }
    });
  });</script>

