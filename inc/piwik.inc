<!-- Piwik --> 
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://tumovida.com.uy/piwik/" : "http://tumovida.com.uy/piwik/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
<?php (isset($_SESSION['idusuario'])) ? $idUsuario = $_SESSION['idusuario'] : $idUsuario = "Anónimo"; ?> 
piwikTracker.setCustomVariable(1, "Usuario", "<?=$idUsuario?>","visit");
piwikTracker.discardHashTag(false);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://tumovida.com.uy/piwik/piwik.php?idsite=1" style="border:0" alt="" /></p></noscript>
<!-- End Piwik Tracking Code -->