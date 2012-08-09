<?php 
include "inc/bd_conn.php";
$conn = new Conectar();
$conn->TM();
if(!isset($_GET["mostrar"])){
    $mails      = true;
    $locales    = true;
    $usuarios   = true;
}else{
    $mostrar = explode("_", $_GET["mostrar"]);
    foreach($mostrar as $m){
        $$m     = true;
    }
}
$buff       = array();
$mailList   = array();
if(isset($mails)){
    $mailQuery  = $conn->query("SELECT id, Mail FROM mails WHERE Mail <> '' ORDER BY Mail");
    while($mail = mysql_fetch_assoc($mailQuery)){
        if(!in_array($mail["Mail"], $mailList)){
            $mailList[] = $mail["Mail"];
            $mail["Localizacion"] = "Mails";
            $buff[] = $mail;
        }
    }
}
if(isset($locales)){
    $mailQuery  = $conn->query("SELECT id, Nombre, Mail FROM locales WHERE Mail <> '' ORDER BY Mail");
    while($mail = mysql_fetch_assoc($mailQuery)){
        if(!in_array($mail["Mail"], $mailList)){
            $mailList[] = $mail["Mail"];
            $mail["Localizacion"] = "Locales";
            $buff[] = $mail;
        }
    }
}
$conn->Usuarios();
if(isset($usuarios)){
    $mailQuery  = $conn->query("SELECT id, Nombre, Apellido, Mail FROM usuarios WHERE Mail <> '' ORDER BY Mail");
    while($mail = mysql_fetch_assoc($mailQuery)){
        if(!in_array($mail["Mail"], $mailList)){
            $mailList[]          = $mail["Mail"];
            $mail["Localizacion"]= "Usuarios";
            $buff[]              = $mail;
        }
    }
}
?>
<?php include "inc/header.php"; ?>
<div class="content container_12">
    <div class="box grid_12">
        <div class="box-head"><h2>Mostrar</h2></div>
        <div class="box-content" style="height: 20px">
            <a class="button big black" href="<?php echo $_SERVER['PHP_SELF'];?>">Todo</a>
            <a class="button big grey" href="<?php echo $_SERVER['PHP_SELF'];?>?mostrar=mails">Mailist</a>
            <a class="button big grey" href="<?php echo $_SERVER['PHP_SELF'];?>?mostrar=locales">Locales</a>
            <a class="button big grey" href="<?php echo $_SERVER['PHP_SELF'];?>?mostrar=usuarios">Usuarios</a>
        </div>
    </div>
    <div class="box grid_12">
        <div class="box-head"><h2>Lista de mails</h2></div>
        <div class="box-content no-pad">
            <table class="display" id="dt3">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Localización</th>
                        <th>Mail</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($buff as $user): ?>
                    <tr class="odd gradeX">
                        <td class="center"><?=$user['id']?></td>
                        <td><?=$user['Localizacion']; ?></td>
                        <td><?=$user['Mail']?></td>
                    </tr>
                 <?php endforeach; ?>
                </tbody>
          </table>
        </div>
    </div>
</div>
<script type="text/javascript">
$('#dt3').dataTable( {
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"   
    });
</script>
<? include "inc/footer.php"; ?>