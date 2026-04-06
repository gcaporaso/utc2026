<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use app\models\Folder;

/** @var yii\web\View $this */
/** @var app\models\Folder[] $model */
/** @var int $ProjId */
/** @var app\models\Project $project */
/** @var app\models\File[] $FilesList */
/** @var mdm\admin\models\User $user */

$this->title = 'Index';
$csrf = Yii::$app->request->csrfToken;
//$selectedId = $selId ?? null; // lo passi dal controller
?>

<div class="container-fluid">

    <div class="row">
        <!-- Treeview delle cartelle -->
        <div class="col-md-2">
            <h4>Cartelle</h4>
            <div id="treefolder"></div>
        </div>

        <!-- Colonna destra: file nella cartella -->
        <div class="col-md-10">
                <h4>Contenuti</h4>
                <div id="grid-container">
                    <div class="alert alert-info">Seleziona una cartella nell’albero.</div>
                </div>
        </div>
       
    </div>
</div>



<script>
        
    var pid = <?php echo $projId; ?>;
    var selectedNodeId = <?= $selId??"'root'"; ?>;
    $(document).ready(function() {
    jQuery.noConflict();    
        // $("#input-700").fileinput({
        //     uploadUrl: "folder/multiple-files-in-project",
        //     maxFileCount: 5
        // });
    

    //var selectedNodeId = "{$selectedId}";
    
        
    $('#treefolder').jstree({
        
        'core' : {
            'data' : {
                'url' : 'index.php?r=folder/tree-data&projectId='+pid,
                'data' : function(node) {
                    
                    return { 'id' : node.id };
                }
            },
        'themes': { 'responsive': true }
        },
        'plugins' : ['wholerow']

    });

    $('#treefolder').on('loaded.jstree', function () {
        // // Apri subito il nodo root (supponendo che sia il primo restituito dal server)
        // var rootNode = $('#treefolder').jstree('get_node', '#').children[0]; 
        // if (rootNode) {
        //     $('#treefolder').jstree('open_node', rootNode);
        //     // Se vuoi anche selezionarlo:
        //     $('#treefolder').jstree('select_node', rootNode);
        // }
        
        var tree = $('#treefolder').jstree(true);
        tree.open_all();
    });
    $('#treefolder').on('ready.jstree', function () {
        var tree = $('#treefolder').jstree(true);
            if (selectedNodeId && selectedNodeId !== "" && selectedNodeId !== "root") {
                //alert('nodo='+selectedNodeId);
                // seleziona il nodo specificato da $id
                tree.deselect_all();
                tree.select_node(selectedNodeId);

                // // apri la catena di genitori per renderlo visibile
                // var node = tree.get_node(selectedNodeId);
                // if (node) {
                //     tree.open_node(node.parents);
                // }
            } else {
                // comportamento di default: apri root
                var rootNode = tree.get_node('#').children[0]; 
                if (rootNode) {
                    //tree.open_node(rootNode);
                    tree.deselect_all();
                    tree.select_node(rootNode);
                }
            }


        
    });

    $('#treefolder').on("select_node.jstree", function (e, data) {
        let folderId = data.node.id;
        console.log("Selezionato", folderId);
        
        $.ajax({
            url: 'index.php?r=folder/folder-content&projectId='+pid,
            data: {id: folderId, _csrf: '<?=$csrf ?>'},
            success: function(res) {
                //alert('ok');
                // let numfile = JSON.stringify(JSON.parse({$files}),null,2);
                // alert('files='+numfile);
                //alert(data.node.text);
                let html = "<table class='table table-sm' style='text-align:center'>";
                html += "<thead><tr>";
                html += "<th></th>"; // simbolo file cartella
                html += "<th style='text-align:left'>Nome";
                html += "</th><th>Data Creazione</th>";
                html += "<th>Autore</th>";
                html += "<th colspan='3' style='text-align:center'>Azioni</th>";
                html += "</tr></thead><tbody>";
                // cartelle
                if (Array.isArray(res.folders)) {
                res.folders.forEach(function(f){
                    html += "<tr><td><img src='/img/folder-48.png' style='width:25px'/></i></td>";
                    //var link ="<a href='index.php?r=folder/inner-det&projectId="+pid+"&id=" + f.Id+"'>"+f.Name+ "</a>";
                    var link ="<a href='#' class='content-folder-link' data-folder-id='" + f.Id+"'>"+f.Name+ "</a>";
                    html += "<td style='text-align:left'>"+link+"</td>";
                    html += "<td>" + (formatDateTime(f.CreationDate) || '') + "</td>";
                    html += "<td>" + (f.CreatorName || '') + "</td>";
                    html += "<td style='text-align:right;width:2%'>";
                    if (f.UserId == res.cuid) {   // currentUserId lo passi da PHP in JS
                        html += "<a href='/index.php?r=folder/edit&id=" + f.Id + "'>";
                        html += "<i class='fa-solid fa-pen-to-square fa-lg'></i></a>";
                    }
                    html += "</td>";
                    html += "<td style='text-align:right;width:2%'>";
                    html += "<a href='/index.php?r=folder/delete&id=" + f.Id + "&amp;projectId=<?=$projId ?>'>";
                    html += "<i class='fas fa-trash fa-lg'></i></a>";
                    html += "</td>";
                        html += "</tr>";
                    // html += "<tr><td>📁 Cartella</td><td>"+ (f.Name || ("Folder " + f.Id)) +"</td></tr>";
                });
                }

                // files
                if (Array.isArray(res.files)) {
                res.files.forEach(function(f){
                    let icon = getFileIcon(f.Type);
                    html += "<tr>";
                    html += "<td>" + icon + "</td>";
                    html += "<td style='text-align:left'><a href='/index.php?r=folder%2Fviewfile&amp;id="+f.Id+"'>"+f.Name +"</a></td>";
                    html += "<td style='text-align:center'>"+formatDateTime(f.UploadDate)+"</td>";
                    html += "<td style='text-align:center'>"+f.CreatorName+"</td>";
                    html += "<td style='text-align:right;width:2%'>";
                    //html += "<td style='text-align:right;width:2%'>";
                    html += "<a href='/index.php?r=folder%2Fdeletefilefromproject&amp;id="+f.Id+"&amp;projectId=<?=$projId ?>'>";
                    html += "<i class='fas fa-trash fa-lg'></i></a>";
                    html += "</td><td style='text-align:right;width:2%'>";    
                    html += "<a href='/index.php?r=folders%2Fdownload-file&amp;fileName="+f.Name+"'>";
                    html += "<i class='fas fa-download fa-lg'></i></a>";
                    html += "</td></tr>";
                });
                }
                
                    html += "</tbody></table>";

                $("#grid-container").html(html);
            }
        });
    });

    });

    $(document).on('click', '.content-folder-link', function(e){
        e.preventDefault();  // evita reload pagina
        var folderId = $(this).data('folder-id');

        // forza selezione del nodo corrispondente nell'albero
        $('#treefolder').jstree('deselect_all');
        $('#treefolder').jstree('select_node', folderId);
    });



    function getFileIcon(fileType) {
        let icon = '<i class="fa-solid fa-file fa-lg"></i>'; // icona default

        switch (String(fileType).toLowerCase()) {
            case '.pdf':
                icon = "<img src='/img/pdf_2.png' style='width:25px' />";
                break;
            case '.jpg':
            case '.jpeg':
            case '.png':
                icon = '<i class="fa-solid fa-image fa-lg"></i>';
                break;
            case '.mp4':
            case '.vlc':
                icon = '<i class="fa-solid fa-video fa-lg"></i>';
                break;
            case '.ifc':
                icon = "<img src='/img/ifc.png' style='width:25px' />";
                break;
            case '.dwg':
                icon = "<img src='/img/Autocad.png' style='width:30px' />";
                break;
            case '.xlsx':
                icon = "<i class='fa-solid fa-file-excel fa-lg'></i>";
                break;
            case '.docx':
                icon = "<i class='fa-solid fa-file-word fa-lg'></i>";
                break;
            case '.csv':
                icon = "<i class='fa-solid fa-file-csv'></i>";
                break;
            case '.rvt':
                icon = "<img src='/Images/Revit.png' style='width:25px' />";
                break;
        }

        return icon;
    }

    $('#btnNewFolder').on('click', function(){
        var selected = $('#treefolder').jstree('get_selected', true);
        if (selected.length > 0) {
            var parentId = selected[0].id;
            window.location.href = 'index.php?r=folder/create&parentId=' + parentId + '&projectId=' + pid;
        } else {
            alert("Seleziona una cartella dove creare la nuova sotto-cartella.");
        }
    });


    function formatDateTime(dateString) {
        if (!dateString) return '';

        const d = new Date(dateString);
        if (isNaN(d)) return dateString;

        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0'); // mesi da 0 a 11
        const year = d.getFullYear();

        const hours = String(d.getHours()).padStart(2, '0');
        const minutes = String(d.getMinutes()).padStart(2, '0');

        return `${day}/${month}/${year} ${hours}:${minutes}`;
    }


    $('#input-700').on('click', function(e) {
        e.preventDefault();

    // recupero il nodo selezionato in jstree
        var selected = $('#treefolder').jstree('get_selected', true);
        if (selected.length > 0) {
            var folderId = selected[0].id;
            $('#upload-folder-id').val(folderId); // hidden input nel form
        } else {
            alert("Seleziona una cartella prima di caricare file.");
            return;
        }


        $('#hidden-file-input').click();
    });

    // quando vengono scelti i file, sottometti il form
    $('#hidden-file-input').on('change', function() {
        var formData = new FormData($('#upload-form')[0]);

        $.ajax({
                url: $('#upload-form').attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    // se l'upload è andato a buon fine
                    var folderId = res.folderId;

                    // 1) seleziona il nodo
                    $('#treefolder').jstree('deselect_all');
                    $('#treefolder').jstree('select_node', folderId);

                    // // 2) opzionale: ricarica i figli
                    // $('#treefolder').jstree('refresh_node', folderId);
                },
                error: function() {
                    alert("Errore durante l'upload.");
                }
            });
            // $('#upload-form').submit();
        });

    </script> 
    <?php $style= <<< CSS
            /* riduci il padding-left solo per il root */
        #treefolder > ul > li.jstree-node > .jstree-anchor {
            padding-left: 0px !important;
        }
        #treefolder > ul > li.jstree-node > .jstree-icon {
           margin-left: 0 !important;          /* icona più a sinistra */
        }
    CSS;
    $this->registerCss($style);
    ?>












